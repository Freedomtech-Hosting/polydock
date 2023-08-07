<?php

namespace App\Jobs;

use App\Models\PolydockAppInstance;
use App\Polydock\Engine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PolydockPreDeployAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $polydockAppInstance;

    /**
     * Create a new job instance.
     */
    public function __construct(PolydockAppInstance $polydockAppInstance)
    {
        $this->polydockAppInstance = $polydockAppInstance;
    }

   /**
     * Execute the job.
     */
    public function handle(Engine $polydockEngine): void
    {
        $appEngine = $polydockEngine->getAppEngine($this->polydockAppInstance->polydockAppType->engine_name);

        if(in_array($this->polydockAppInstance->status, [
            PolydockAppInstance::STATUS_REMOVED,
            PolydockAppInstance::STATUS_REMOVE,
            PolydockAppInstance::STATUS_PRE_REMOVE,
            PolydockAppInstance::STATUS_POST_REMOVE
        ])) {
            $this->polydockAppInstance->logError("The lagoon project environment has already been removed.");
            return;
        }

        $this->polydockAppInstance->saveStatus(PolydockAppInstance::STATUS_PRE_DEPLOY);

        if($appEngine->preDeployAppInstance($this->polydockAppInstance)) {
            $this->polydockAppInstance->saveStatus(PolydockAppInstance::STATUS_DEPLOY);
            PolydockDeployAppJob::dispatch($this->polydockAppInstance);
        } else {
            $this->polydockAppInstance->logError("Failed to execute pre-deploy");
        }
    }
}
