<?php namespace App\Lagoon\Client;

use App\Exceptions\LagoonClientInitializeRequiredToInteract;
use App\Exceptions\LagoonClientTokenRequiredToInitialize;

class Client {
    protected $config;
    protected $graphqlClient;
    private $sshPrivateKey;
    private $sshPrivateKeyFile;
    protected $lagoonSshUser;
    protected $lagoonSshServer;
    protected $lagoonSshPort;
    protected $lagoonToken;
    protected $lagoonApiEndpoint;

    public function __construct(string $sshPrivateKey, array $config = [])
    {
        $this->sshPrivateKey = $sshPrivateKey;

        $this->config = $config;
        $this->lagoonSshUser = $config['sshuser'] ?? 'lagoon';
        $this->lagoonSshServer = $config['sshserver'] ?? 'ssh.lagoon.amazeeio.cloud';
        $this->lagoonSshPort = $config['sshport'] ?? '32222';

        $this->lagoonApiEndpoint = $config['endpoint'] ?? 'https://api.lagoon.amazeeio.cloud/graphql';

        $this->sshPrivateKeyFile = tempnam(sys_get_temp_dir(), 'lagoon-ssh-tmp');
        file_put_contents($this->sshPrivateKeyFile, $this->sshPrivateKey);
    }

    public function initGraphqlClient()
    {
        if(empty($this->lagoonToken)) {
            throw new LagoonClientTokenRequiredToInitialize();
        }

        $this->graphqlClient = \Softonic\GraphQL\ClientBuilder::build($this->lagoonApiEndpoint, [
            'headers' => [
                'Authorization'     => 'Bearer ' . $this->lagoonToken
            ]
        ]);
    }

    public function setLagoonToken($token)
    {
        $this->lagoonToken = $token;
    }

    public function getLagoonToken()
    {
        return $this->lagoonToken;
    }

    public function getLagoonTokenOverSSH($refresh = false)
    {

        if($this->lagoonToken && !$refresh) {
            return $this->lagoonToken;
        }

        $ssh = LagoonSsh::create($this->lagoonSshUser, $this->lagoonSshServer)
            ->usePort($this->lagoonSshPort)
            ->usePrivateKey($this->sshPrivateKeyFile)
            ->disableStrictHostKeyChecking()
            ->removeBash()
            ->enableQuietMode();

        $token = $ssh->executeLagoonGetToken();
        $this->setLagoonToken($token);

        return $token;
    }

    public function pingLagoonAPI() : bool
    {
        if(empty($this->lagoonToken) || empty($this->graphqlClient)) {
            throw new LagoonClientInitializeRequiredToInteract();
        }

        /**
         * Query Example
         */
        $query = "
          query q {
            lagoonVersion
            me {
              id
            }
          }";

        $response = $this->graphqlClient->query($query);

        if($response->hasErrors()) {
            return false;
        }
        else {
            // Returns an array with all the data returned by the GraphQL server.
            $data = $response->getData();

            return isset($data['lagoonVersion']) && isset($data['me']['id']);
        }

        return true;
    }

    public function createLagoonProject(
        string $projectName,
        string $gitUrl,
        string $deployBranch,
        string $clusterId,
        string $privateKey)
    {

        if(empty($this->lagoonToken) || empty($this->graphqlClient)) {
            throw new LagoonClientInitializeRequiredToInteract();
        }

        $mutation = "mutation {
            addProject(
                input: {
                    name: \"{$projectName}\"
                    gitUrl: \"{$gitUrl}\"
                    kubernetes: {$clusterId}
                    branches: \"{$deployBranch}\"
                    productionEnvironment: \"{$deployBranch}\"
                    privateKey: \"{$privateKey}\"
                }
            ) {
                id
                name
                gitUrl
                branches
                productionEnvironment
            }
        }";

        $response = $this->graphqlClient->query($mutation);

        if($response->hasErrors()) {
            return ['error' => $response->getErrors()];
        }
        else {
            // Returns an array with all the data returned by the GraphQL server.
            $data = $response->getData();
            return $data;
        }
    }

    public function addOrUpdateGlobalVariableForProject(
        string $projectName,
        string $key,
        string $value
    )
    {
        if(empty($this->lagoonToken) || empty($this->graphqlClient)) {
            throw new LagoonClientInitializeRequiredToInteract();
        }

        $mutation = "
        mutation m {
            addOrUpdateEnvVariableByName(input: {
                project: \"{$projectName}\"
                name: \"{$key}\"
                scope: GLOBAL
                value: \"{$value}\"
            }) {
              id
              name
              value
              scope
            }
          }
        ";

        $response = $this->graphqlClient->query($mutation);

        if($response->hasErrors()) {
            return ['error' => $response->getErrors()];
        }
        else {
            // Returns an array with all the data returned by the GraphQL server.
            $data = $response->getData();
            return $data;
        }
    }

    public function projectExistsByName(string $projectName) : bool
    {
        $data = $this->getProjectByName($projectName);
        return(isset($data['projectByName']['id']));
    }

    public function projectEnvironmentExistsByName(string $projectName, $environmentName) : bool
    {
        $data = $this->getProjectEnvironmentsByName($projectName);
        return(isset($data[$environmentName]));
    }

    public function getProjectEnvironmentByName(string $projectName, $environmentName) : array
    {
        $data = $this->getProjectEnvironmentsByName($projectName);

        return($data[$environmentName] ?? []);
    }

    public function getProjectEnvironmentsByName(string $projectName) : array
    {
        $data = $this->getProjectByName($projectName);
        $environment = $data['projectByName']['environments'];
        $retenvs = [];

        foreach($environment as $environment) {
            $retenvs[$environment['name']] = $environment;
        }

        return($retenvs);
    }

    public function getProjectVariablesByName(string $projectName) : array
    {
        $data = $this->getProjectByName($projectName);
        $lagoonVars = $data['projectByName']['envVariables'] ?? [];
        $retvars = [];

        foreach($lagoonVars as $lagoonVar) {
            $retvars[$lagoonVar['name']] = [
                'value' => $lagoonVar['value'],
                'scope' => $lagoonVar['scope']
            ];
        }

        return $retvars;
    }

    public function getProjectByName(string $projectName) : array
    {
        if(empty($this->lagoonToken) || empty($this->graphqlClient)) {
            throw new LagoonClientInitializeRequiredToInteract();
        }

        /**
         * Query Example
         */
        $query = "
          query q {
            projectByName(name: \"$projectName\") {
                id
                name
                productionEnvironment
                branches
                gitUrl
                openshift {
                  id
                  name
                  cloudProvider
                  cloudRegion
                }
                created
                metadata
                envVariables {
                  id
                  name
                  value
                  scope
                }
                publicKey
                privateKey
                availability
                environments {
                    id
                    name
                    created
                    updated
                    deleted
                    environmentType
                    route
                    routes
                }
              }
          }";

        $response = $this->graphqlClient->query($query);

        if($response->hasErrors()) {
            return ['error' => $response->getErrors()];
        }
        else {
            // Returns an array with all the data returned by the GraphQL server.
            $data = $response->getData();
            return $data;
        }

        return true;
    }

    public function deployProjectEnvironmentByName(
        string $projectName,
        string $deployBranch,
    )
    {
        if(empty($this->lagoonToken) || empty($this->graphqlClient)) {
            throw new LagoonClientInitializeRequiredToInteract();
        }

        $mutation = "
        mutation m {
            deployEnvironmentBranch(input: {
                project: {name: \"{$projectName}\"}
                branchName: \"{$deployBranch}\"
                returnData: true
            })
        }
        ";

        $response = $this->graphqlClient->query($mutation);

        if($response->hasErrors()) {
            return ['error' => $response->getErrors()];
        }
        else {
            // Returns an array with all the data returned by the GraphQL server.
            $data = $response->getData();
            return $data;
        }
    }

    public function getProjectDeploymentByProjectIdDeploymentName(string $projectId, string $environmentName, string $deploymentName)  : array
    {
        if(empty($this->lagoonToken) || empty($this->graphqlClient)) {
            throw new LagoonClientInitializeRequiredToInteract();
        }

        /**
         * Query Example
         */
        $query = "
        query q {
            environmentByName(project: {$projectId}, name: \"{$environmentName}\") {
              deployments(name: \"{$deploymentName}\") {
                id
                remoteId
                name
                status
                created
                started
                completed
              }
            }
          }";

        $response = $this->graphqlClient->query($query);

        if($response->hasErrors()) {
            return ['error' => $response->getErrors()];
        }
        else {
            // Returns an array with all the data returned by the GraphQL server.
            $data = $response->getData();
            if(isset($data['environmentByName']['deployments'][0])) {
                return $data['environmentByName']['deployments'][0];
            }

            return ['error' => 'Deployment not found: ' . $deploymentName, 'errorData' => $data];
        }

        return true;
    }

    public function deleteProjectEnvironmentByName(
        string $projectName,
        string $environmentName,
    )
    {
        if(empty($this->lagoonToken) || empty($this->graphqlClient)) {
            throw new LagoonClientInitializeRequiredToInteract();
        }

        $mutation = "
        mutation m {
            deleteEnvironment(input: {
                    project: \"{$projectName}\",
                    name: \"{$environmentName}\",
                    execute: true
                }
            )
          }
        ";

        $response = $this->graphqlClient->query($mutation);

        if($response->hasErrors()) {
            return ['error' => $response->getErrors()];
        }
        else {
            // Returns an array with all the data returned by the GraphQL server.
            $data = $response->getData();
            return $data;
        }
    }
}

