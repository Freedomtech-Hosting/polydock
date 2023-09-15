<?php

namespace App\Providers;

use App\Polydock\AppEngine\FedimintAppEngine;
use App\Polydock\AppEngine\LightningNodeAppEngine;
use App\Polydock\AppEngine\NostrRelayAppEngine;
use App\Polydock\AppEngine\D10DemoAppEngine;
use App\Polydock\Engine;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class PolydockEngineProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        Engine::class => Engine::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $engine = app(Engine::class);

        $polyconfig = config('polydock.engines');

        $engine->registerAppType(LightningNodeAppEngine::getMachineName(), new LightningNodeAppEngine($polyconfig));
        $engine->registerAppType(NostrRelayAppEngine::getMachineName(), new NostrRelayAppEngine($polyconfig));
        $engine->registerAppType(FedimintAppEngine::getMachineName(), new FedimintAppEngine($polyconfig));
        $engine->registerAppType(D10DemoAppEngine::getMachineName(), new D10DemoAppEngine($polyconfig));
    }
}
