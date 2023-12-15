<?php namespace App\Polydock\GitEngine;

use App\Polydock\GitEngineInterface;
use CzProject\GitPhp\GitRepository;
use Exception;
use GrahamCampbell\GitHub\Facades\GitHub;

class GitHubGitEngine extends BaseGitEngine implements GitEngineInterface {

    public function __construct($config = [])
    {
        parent::__construct($config);        
    }

    public function createOwnGit(String $sourceGitUri, String $targetGitUri, $branch="main") : bool {
        $targetParts = $this->getGitUriParts($targetGitUri);

        $targetGHRepoDetails = GitHub::repo()->create($targetParts["repo"],
            "", // description
            "", // homepage
            false, // not-public
            $organization = $targetParts["org"]);

        if(! $targetGHRepoDetails['id']) {
            throw new Exception("Could not create the new target repo in the GitHub Org");
        }

        $repo = $this->cloneRepoLocally($sourceGitUri);
        if(!$repo) {
            throw new Exception("Could not clone the source repository locally");
        }

        $repo->addRemote('polydockcopy', $targetGitUri);
        if(!in_array($branch, $repo->getBranches())) {
            $repo->createBranch($branch, TRUE);
        } else {
            $repo->checkout($branch);
        }

        if($repo->push(['polydockcopy', $branch])) {
            return TRUE;
        }

        return FALSE;
    }

    public function validateGitUri(String $gitUri) : bool {
        return preg_match("|^git@github.com:([\w,\-,\_]+)/([\w,\-,\_]+).git$|", $gitUri);
    }

    public function getGitUriParts(String $gitUri) : array {
        $parts = [];
        
        if(preg_match("|^git@github.com:([\w,\-,\_]+)/([\w,\-,\_]+).git$|", $gitUri, $parts)) {
            return [
                "org" => $parts[1],
                "repo" => $parts[2]
            ];
        }
        
        return [
            "org" => null,
            "repo" => null
        ];
    }

    public function cloneRepoLocally(String $sourceGitUri) : GitRepository {
        $localDir = $this->tempdir();
        echo $localDir;
        $repo = $this->git->cloneRepository($sourceGitUri, $localDir);

        return $repo;
    }

    function tempdir() {
        $tempfile=tempnam(sys_get_temp_dir(),'');
        // tempnam creates file on disk
        if (file_exists($tempfile)) { unlink($tempfile); }
        mkdir($tempfile);
        if (is_dir($tempfile)) { return $tempfile; }
    } 
}