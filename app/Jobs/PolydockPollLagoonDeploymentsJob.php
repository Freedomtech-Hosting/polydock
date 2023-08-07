<?php

namespace App\Jobs;

use App\Models\PolydockAppInstance;
use App\Models\PolydockAppInstanceDeployment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PolydockPollLagoonDeploymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $deployments = PolydockAppInstanceDeployment::whereIn('status', [
            PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_NEW,
            PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_QUEUED,
            PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_PENDING,
            PolydockAppInstanceDeployment::DEPLOYMENT_STATUS_RUNNING,
        ])->get();

        foreach($deployments as $deployment) {
            PolydockPollLagoonDeploymentJob::dispatch($deployment);
        }
    }
}
