<?php namespace App\Polydock;

interface GitEngineInterface {
    public function __construct($config = []);

    public static function getMachineName() : string;

    public function validateGitUri(String $gitUri) : bool;
    public function getGitUriParts(String $gitUri) : array;

    public function createOwnGit(String $sourceGitUri, String $targetGitUri, $branch="main") : bool;
}