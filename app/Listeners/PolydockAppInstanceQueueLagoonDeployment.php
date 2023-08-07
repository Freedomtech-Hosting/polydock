<?php

namespace App\Listeners;

use App\Events\PolydockAppInstanceReadyForLagoonCreation;
use App\Events\PolydockAppInstanceReadyForLagoonDeployment;
use App\Jobs\PolydockPreDeployAppJob;
use App\Polydock\Engine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PolydockAppInstanceQueueLagoonDeployment
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
    public function handle(PolydockAppInstanceReadyForLagoonDeployment $event): void
    {
        $event->polydockAppInstance->logInfo('Lagoon deployment triggered');
        PolydockPreDeployAppJob::dispatch($event->polydockAppInstance);
    }
}
