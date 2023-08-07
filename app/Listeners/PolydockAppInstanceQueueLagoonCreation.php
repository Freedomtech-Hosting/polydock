<?php

namespace App\Listeners;

use App\Events\PolydockAppInstanceReadyForLagoonCreation;
use App\Jobs\PolydockPreCreateAppJob;
use App\Polydock\Engine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PolydockAppInstanceQueueLagoonCreation
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
    public function handle(PolydockAppInstanceReadyForLagoonCreation $event): void
    {
        $event->polydockAppInstance->logInfo('Lagoon creation triggered');
        PolydockPreCreateAppJob::dispatch($event->polydockAppInstance);
    }
}
