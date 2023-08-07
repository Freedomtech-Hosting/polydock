<?php namespace App\Polydock\AppEngine;

use App\Models\PolydockAppInstance;
use App\Models\PolydockAppInstanceDeployment;
use App\Models\PolydockLagoonCluster;
use App\Polydock\AppEngineInterface;
use Closure;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Validation\Rule;

abstract class BaseAppEngine implements AppEngineInterface {
    protected $config = [];
    const POLYDOCK_ENGINE_NAME = "POLYDOCK_ABSTRACT_CLASS";

    protected $lagoonProjectPrefix;

    public function __construct($config = [])
    {
        $this->config = $config;

        $this->lagoonProjectPrefix = $config[$this->getMachineName()]['lagoonProjectPrefix']
            ?? $config['lagoonProjectPrefix']
            ?? "polydoc";
    }

    public function preCreateAppInstance(PolydockAppInstance $instance): bool
    {
        $instance->logInfo("pre-create called");
        $instance->refresh();

        // By default, ping the lagoon API using the Team credentials of the App Instance
        // to make sure we have Lagoon connectivity

        $ping = false;

        try {
            $lagoonClient = $instance->team->getLagoonClient();
            $lagoonClient->initGraphqlClient();
            $ping = $lagoonClient->pingLagoonAPI();
        } catch(Exception $ex) {
            $instance->logError("Pre-create error: " . $ex->getMessage());
        }

        if(! $ping) {
            $instance->logError("Pre-create error: Could not ping the Lagoon API");
            return false;
        } else {
            $instance->logDebug("Successful ping of the Lagoon API");
        }

        if(!empty($instance->lagoon_project) || !empty($instance->lagoon_project_id)) {
            $instance->logError("Pre-create error: Lagoon project and/or id is already set.");
            return false;
        }

        if(empty($instance->lagoon_project)) {
            $instance->lagoon_project = Str::slug($this->lagoonProjectPrefix
            . " " . $instance->polydockLagoonCluster->country_code
            . " " . uniqid()
            . " " . $instance->id);
        }

        if(empty($instance->lagoon_environment)) {
            $instance->lagoon_environment = $instance->polydockAppType->default_deploy_branch;
        }

        if(empty($instance->deploy_branch)) {
            $instance->deploy_branch = $instance->polydockAppType->default_deploy_branch;
        }

        if(empty($instance->giturl)) {
            $instance->giturl = $instance->polydockAppType->giturl;
        }

        $instance->save();


        return $ping;
    }

    public function createAppInstance(PolydockAppInstance $instance) : bool
    {
        $instance->logInfo("create called");
        $instance->refresh();

        // By default, we create the Lagoon Project in the requested cluster
        try {
            $lagoonClient = $instance->team->getLagoonClient();
            $lagoonClient->initGraphqlClient();

            if(!$lagoonClient->projectExistsByName($instance->lagoon_project)) {
                $data = $lagoonClient->createLagoonProject(
                    $instance->lagoon_project,
                    $instance->giturl,
                    $instance->deploy_branch,
                    $instance->polydockLagoonCluster->lagoon_cluster_code,
                    Str::replace("\n",'\n',$instance->team->git_deploy_ssh_priv_key)
                );

                if(isset($data['error'])) {
                    $instance->logError("Pre-create error: ", $data);
                    return false;
                } else {
                    $instance->logDebug("Lagoon project created", $data);
                    $instance->lagoon_project_id = $data['addProject']['id'];
                    $instance->save();
                }

            } else {
                $data = $lagoonClient->getProjectByName($instance->lagoon_project);
                $instance->logError("Pre-create error: The project already exists.", $data);
                return false;
            }
        } catch(Exception $ex) {
            $instance->logError("Pre-create error: " . $ex->getMessage());
            return false;
        }

        return true;
    }

    public function postCreateAppInstance(PolydockAppInstance $instance): bool
    {
        $instance->logInfo("post create called");
        $instance->refresh();

        try {
            $lagoonClient = $instance->team->getLagoonClient();
            $lagoonClient->initGraphqlClient();

            if(!$lagoonClient->projectExistsByName($instance->lagoon_project)) {
                $instance->logError("Post-create error: Lagoon project not found");
                return false;
            } else {
                $data = $lagoonClient->getProjectByName($instance->lagoon_project);
                $instance->logDebug("Storing project metadata.", $data);
                $instance->lagoon_project_json = $data;
                if(!$instance->lagoon_project_id) {
                    $instance->lagoon_project_id = $data['projectByName']['id'];
                }

                $instance->save();
            }
        } catch(Exception $ex) {
            $instance->logError("Pre-create error: " . $ex->getMessage());
            return false;
        }

        return true;
    }

    public function preDeployAppInstance(PolydockAppInstance $instance): bool
    {
        $instance->logInfo("pre deploy called");
        $instance->refresh();

        if(empty($instance->lagoon_project)) {
            $instance->logError("The lagoon project name is not set");
            return false;
        }
       
        if(in_array($instance->status, [
            PolydockAppInstance::STATUS_REMOVED,
            PolydockAppInstance::STATUS_REMOVE,
            PolydockAppInstance::STATUS_PRE_REMOVE,
            PolydockAppInstance::STATUS_POST_REMOVE
        ])) {
            $instance->logError("The lagoon project environment has already been removed.");
            return false;
        }

        try {
            $lagoonClient = $instance->team->getLagoonClient();
            $lagoonClient->initGraphqlClient();

            if(!$lagoonClient->projectExistsByName($instance->lagoon_project)) {
                $instance->logError("The lagoon project does not exist");
                return false;
            }

            $lagoonVars = $lagoonClient->getProjectVariablesByName($instance->lagoon_project);
            $instance->logDebug("Current Lagoon variables", $lagoonVars);

            foreach($instance->variables as $polydockVariable) {
                $instance->logDebug("Checking lagoon for variable: " . $polydockVariable->key . "=" . $polydockVariable->value);
                if(!isset($lagoonVars[$polydockVariable->key]) || $lagoonVars[$polydockVariable->key]['value'] != $polydockVariable->value) {
                    $instance->logDebug("Setting lagoon for variable: " . $polydockVariable->key . "=" . $polydockVariable->value);
                    $vdata = $lagoonClient->addOrUpdateGlobalVariableForProject($instance->lagoon_project, $polydockVariable->key, $polydockVariable->value);
                    if(isset($vdata['error'])) {
                        $instance->logError("Pre-deploy error: ", $vdata);
                        return false;
                    } else {
                        $instance->logDebug("Variable set in lagoon", $vdata);
                    }
                } else {
                    $instance->logDebug("Lagoon variable already set: " . $polydockVariable->key . "=" . $polydockVariable->value);
                }
            }

        } catch(Exception $ex) {
            $instance->logError("Pre-deploy error: " . $ex->getMessage());
            return false;
        }

        return true;
    }

    public function deployAppInstance(PolydockAppInstance $instance) : bool
    {
        $instance->logInfo("deploy called");
        $instance->refresh();

        try {
            $lagoonClient = $instance->team->getLagoonClient();
            $lagoonClient->initGraphqlClient();

            $data = $lagoonClient->deployProjectEnvironmentByName($instance->lagoon_project, $instance->deploy_branch);
            if(isset($data['error'])) {
                $instance->logError("Deploy error: ", $data);
                return false;
            } else {
                $instance->logDebug("Lagoon deployment triggered", $data);
                $instance->latest_deploy_name = $data['deployEnvironmentBranch'];
                $instance->save();

                if(! $this->updateOrCreateLagoonDeploymentDetailsByDeploymentName($instance, $data['deployEnvironmentBranch'])) {
                    $instance->logError("Polydock deployment creation error");
                    return false;
                } else {
                    $instance->logDebug("Polydock deployment created", $data);
                }
            }

        } catch(Exception $ex) {
            $instance->logError("Deploy error: " . $ex->getMessage());
            return false;
        }

        return true;
    }

    public function postDeployAppInstance(PolydockAppInstance $instance): bool
    {
        $instance->logInfo("post deploy called");
        $instance->refresh();

        try {
            $lagoonClient = $instance->team->getLagoonClient();
            $lagoonClient->initGraphqlClient();

            if(!$lagoonClient->projectEnvironmentExistsByName($instance->lagoon_project, $instance->lagoon_environment)) {
                $instance->logError("Post-deploy error: Lagoon project envrionment not found");
                return false;
            } else {
                $data = $lagoonClient->getProjectEnvironmentByName($instance->lagoon_project, $instance->lagoon_environment);
                $instance->logDebug("Storing project environment metadata.", $data);
                $instance->lagoon_environment_json = $data;
                $instance->lagoon_environment_id = $data['id'];
                if($data['routes']) {
                    $instance->lagoon_routes_json = explode(",", $data['routes']);
                } else if($data['route']) {
                    $instance->lagoon_routes_json = [$data['route']];
                }
                $instance->save();
            }
        } catch(Exception $ex) {
            $instance->logError("Post-deploy error: " . $ex->getMessage());
            return false;
        }

        return true;
    }

    public function preRemoveAppInstance(PolydockAppInstance $instance): bool
    {
        $instance->logInfo("pre remove called");
        $instance->refresh();

        if(empty($instance->lagoon_project)) {
            $instance->logError("Pre-remove error: The lagoon project name is not set");
            return false;
        }

        try {
            $lagoonClient = $instance->team->getLagoonClient();
            $lagoonClient->initGraphqlClient();

            if(!$lagoonClient->projectExistsByName($instance->lagoon_project)) {
                $instance->logError("Pre-remove error: The lagoon project does not exist");
                return false;
            }

            if(!$lagoonClient->projectEnvironmentExistsByName($instance->lagoon_project, $instance->lagoon_environment)) {
                $instance->logError("Pre-remove error: : Lagoon project envrionment not found");
                return false;
            }

        } catch(Exception $ex) {
            $instance->logError("Pre-remove error: " . $ex->getMessage());
            return false;
        }

        return true;
    }

    public function removeAppInstance(PolydockAppInstance $instance) : bool
    {
        $instance->logInfo("remove called");
        $instance->refresh();

        try {
            $lagoonClient = $instance->team->getLagoonClient();
            $lagoonClient->initGraphqlClient();

            $data = $lagoonClient->deleteProjectEnvironmentByName($instance->lagoon_project, $instance->lagoon_environment);
            if(isset($data['error'])) {
                $instance->logError("Remove error: ", $data);
                return false;
            } else {
                $instance->logDebug("Lagoon environment remove triggered", $data);
            }

        } catch(Exception $ex) {
            $instance->logError("Deploy Remove: " . $ex->getMessage());
            return false;
        }

        return true;
    }

    public function postRemoveAppInstance(PolydockAppInstance $instance): bool
    {
        $instance->logInfo("post remove called");
        return true;
    }

    public static function getMachineName(): string
    {
        return static::POLYDOCK_ENGINE_NAME;
    }

    public function updateOrCreateLagoonDeploymentDetailsByDeploymentName(PolydockAppInstance $instance, $deploymentName) : bool
    {
        try {
            $lagoonClient = $instance->team->getLagoonClient();
            $lagoonClient->initGraphqlClient();

            $data = $lagoonClient->getProjectDeploymentByProjectIdDeploymentName($instance->lagoon_project_id, $instance->lagoon_environment, $deploymentName);
            if(isset($data['error'])) {
                $instance->logError("Update deployment details error: ", $data);
                return false;
            }

            $deployment = $instance->deployments()->where('lagoon_name', $deploymentName)->first();
            if(!$deployment) {
                $instance->logDebug("Created deployment details: ", $data);
                $instance->deployments()->create([
                    'lagoon_name'  => $deploymentName,
                    'lagoon_remote_id' => $data['remoteId'],
                    'status'  => $data['status'],
                    'lagoon_created_at'  => $data['created'],
                    'lagoon_started_at'  => $data['started'],
                    'lagoon_completed_at' => $data['completed'],
                ]);
            } else {
                $instance->logDebug("Update deployment details: ", $data);
                $deployment->update([
                    'lagoon_remote_id' => $data['remoteId'],
                    'status'  => $data['status'],
                    'lagoon_created_at'  => $data['created'],
                    'lagoon_started_at'  => $data['started'],
                    'lagoon_completed_at' => $data['completed'],
                ]);
            }
        } catch(Exception $ex) {
            $instance->logError("Update deployment details error: " . $ex->getMessage());
            return false;
        }

        return true;
    }
    
    public function getBaseAppValidationRules()
    {
        return [
            'name' => [
                'required',
                'max:150',
                function (string $attribute, mixed $value, Closure $fail) {
                    $words = count(explode(' ', trim($value)));
                    if ($words > 5) {
                        $fail("The name should not exceed 5 words");
                    }
                },
            ],
            'description' => [
                'required',
                'max:1024'  
            ],
            'polydock_lagoon_cluster_id' => [
                'required',
                'exists:' . PolydockLagoonCluster::class . ',id'
            ]
        ];
    }
}
