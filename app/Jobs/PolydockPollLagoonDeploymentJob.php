<?php

namespace App\Jobs;

use App\Events\PolydockAppInstanceDeploymentFailure;
use App\Events\PolydockAppInstanceDeploymentStillInProgress;
use App\Events\PolydockAppInstanceDeploymentSuccess;
use App\Models\PolydockAppInstanceDeployment;
use App\Polydock\Engine;
use Database\Factories\PolydockAppInstanceDeploymentFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PolydockPollLagoonDeploymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $polydockAppInstanceDeployment;

    /**
     * Create a new job instance.
     */
    public function __construct(PolydockAppInstanceDeployment $polydockAppInstanceDeployment)
    {
        $this->polydockAppInstanceDeployment = $polydockAppInstanceDeployment;
    }

    /**
     * Execute the job.
     */
    public function handle(Engine $polydockEngine): void
    {
        $appEngine = $polydockEngine->getAppEngine($this->polydockAppInstanceDeployment->polydockAppInstance->polydockAppType->engine_name);
        $appEngine->updateOrCreateLagoonDeploymentDetailsByDeploymentName(
            $this->polydockAppInstanceDeployment->polydockAppInstance, 
            $this->polydockAppInstanceDeployment->lagoon_name
        );

        $this->polydockAppInstanceDeployment->refresh();
        switch($this->polydockAppInstanceDeployment->status) {
            case PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_NEW:
                PolydockAppInstanceDeploymentStillInProgress::dispatch($this->polydockAppInstanceDeployment);
                break;
            case PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_QUEUED:
                PolydockAppInstanceDeploymentStillInProgress::dispatch($this->polydockAppInstanceDeployment);
                break;
            case PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_PENDING:
                PolydockAppInstanceDeploymentStillInProgress::dispatch($this->polydockAppInstanceDeployment);
                break;
            case PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_RUNNING:
                PolydockAppInstanceDeploymentStillInProgress::dispatch($this->polydockAppInstanceDeployment);
                break;
            case PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_CANCELLED:
                PolydockAppInstanceDeploymentFailure::dispatch($this->polydockAppInstanceDeployment);
                break;
            case PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_FAILED:
                PolydockAppInstanceDeploymentFailure::dispatch($this->polydockAppInstanceDeployment);
                break;
            case PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_COMPLETE:
                PolydockAppInstanceDeploymentSuccess::dispatch($this->polydockAppInstanceDeployment);
                break;
        }
    }
}
