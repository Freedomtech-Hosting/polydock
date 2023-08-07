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

class PolydockPreCreateAppJob implements ShouldQueue
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

        $this->polydockAppInstance->saveStatus(PolydockAppInstance::STATUS_PRE_CREATE);

        if($appEngine->preCreateAppInstance($this->polydockAppInstance)) {
            PolydockCreateAppJob::dispatch($this->polydockAppInstance);
        } else {
            $this->polydockAppInstance->logError("Failed to execute pre-create");
        }
    }
}
