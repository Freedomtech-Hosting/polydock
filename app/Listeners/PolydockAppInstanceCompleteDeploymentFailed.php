<?php

namespace App\Listeners;

use App\Events\PolydockAppInstanceDeploymentFailure;
use App\Jobs\PolydockPostDeployAppJob;
use App\Models\PolydockAppInstance;
use App\Polydock\Engine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PolydockAppInstanceCompleteDeploymentFailed
{
    public $polydockEngine;

    /**
     * Create the event listener.
     */
    public function __construct(Engine $polydockEngine)
    {
        $this->polydockEngine = $polydockEngine;
    }

    /**
     * Handle the event.
     */
    public function handle(PolydockAppInstanceDeploymentFailure $event): void
    {
        $deployment = $event->polydockAppInstanceDeployment;
        $instance = $deployment->polydockAppInstance;
        $instance->logDebug("Deployment " . $deployment->lagoon_name . " failed.");

        if(! in_array($instance->status, [
            PolydockAppInstance::STATUS_FAILED, 
            PolydockAppInstance::STATUS_REMOVE, 
            PolydockAppInstance::STATUS_REMOVED,
            PolydockAppInstance::STATUS_PRE_REMOVE,
            PolydockAppInstance::STATUS_POST_REMOVE,
        ])) {
            if($instance->latest_deploy_name == $deployment->lagoon_name) {
                $instance->saveStatus(PolydockAppInstance::STATUS_FAILED);
            }    
        }    
    }
}
