<?php namespace App\Polydock\GitEngine;

use App\Polydock\GitEngineInterface;
use CzProject\GitPhp\Git;

abstract class BaseGitEngine implements GitEngineInterface {
    protected $config = [];
    const POLYDOCK_ENGINE_NAME = "POLYDOCK_ABSTRACT_CLASS";
    protected $git;
    protected $ssh;

    public function __construct($config = [])
    {
        $this->config = $config;
        $this->git = new Git;
        $this->ssh = isset($this->config['polydockOwnGitSsh']) ? $this->config['polydockOwnGitSsh'] : "/usr/bin/ssh";
        
        if($this->config['polydockOwnGitKey']) {
            putenv('GIT_SSH_COMMAND=' . $this->ssh . ' -i ' . $this->config['polydockOwnGitKey'] . ' -o IdentitiesOnly=yes');
        } else {
            putenv('GIT_SSH_COMMAND=' . $this->ssh . ' -o IdentitiesOnly=yes');
        }
    }

    public static function getMachineName(): string
    {
        return static::POLYDOCK_ENGINE_NAME;
    }
}