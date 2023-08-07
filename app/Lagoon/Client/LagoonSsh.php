<?php namespace App\Lagoon\Client;

use Spatie\Ssh\Ssh;
use Closure;
use Exception;
use Symfony\Component\Process\Process;

class LagoonSsh extends Ssh {
    /**
     * @param string|array $command
     *
     * @return \Symfony\Component\Process\Process
     **/
    public function executeLagoonGetToken(): string
    {
        $extraOptions = implode(' ', $this->getExtraOptions());
        $target = $this->getTargetForSsh();

        $sshCommand = "ssh {$extraOptions} {$target} token";

        $process = $this->run($sshCommand);

        $token = $process->getOutput();
        return ltrim(rtrim($token));
    }
}
