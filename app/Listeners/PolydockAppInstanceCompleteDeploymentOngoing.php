<?php

namespace App\Listeners;

use App\Events\PolydockAppInstanceDeploymentStillInProgress;
use App\Models\PolydockAppInstance;
use App\Polydock\Engine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PolydockAppInstanceCompleteDeploymentOngoing
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
    public function handle(PolydockAppInstanceDeploymentStillInProgress $event): void
    {
        $deployment = $event->polydockAppInstanceDeployment;
        $instance = $deployment->polydockAppInstance;
        $instance->logDebug("Deployment " . $deployment->lagoon_name . " still in progress.");

        if($instance->latest_deploy_name == $deployment->lagoon_name) {
            $instance->saveStatus(PolydockAppInstance::STATUS_DEPLOY);
        }
    }
}
