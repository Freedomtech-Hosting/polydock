<?php

namespace App\Listeners;

use App\Events\PolydockAppInstanceReadyForLagoonRemoval;
use App\Jobs\PolydockPreRemoveAppJob;
use App\Polydock\Engine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PolydockAppInstanceQueueLagoonRemoval
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
    public function handle(PolydockAppInstanceReadyForLagoonRemoval $event): void
    {
        $event->polydockAppInstance->logInfo('Lagoon removal triggered');
        PolydockPreRemoveAppJob::dispatch($event->polydockAppInstance);
    }
}
