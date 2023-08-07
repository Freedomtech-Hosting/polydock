<?php

namespace App\Providers;

use App\Models\PolydockAppInstance;
use App\Observers\PolydockAppInstanceObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\PolydockAppInstanceReadyForLagoonCreation;
use App\Listeners\PolydockAppInstanceQueueLagoonCreation;

use App\Events\PolydockAppInstanceReadyForLagoonDeployment;
use App\Listeners\PolydockAppInstanceQueueLagoonDeployment;

use App\Events\PolydockAppInstanceReadyForLagoonRemoval;
use App\Listeners\PolydockAppInstanceQueueLagoonRemoval;

use App\Events\PolydockAppInstanceDeploymentSuccess ;
use App\Listeners\PolydockAppInstanceCompleteDeploymentSucceeded ;

use App\Events\PolydockAppInstanceDeploymentFailure;
use App\Listeners\PolydockAppInstanceCompleteDeploymentFailed;

use App\Events\PolydockAppInstanceDeploymentStillInProgress;
use App\Listeners\PolydockAppInstanceCompleteDeploymentOngoing;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PolydockAppInstanceReadyForLagoonCreation::class => [
            PolydockAppInstanceQueueLagoonCreation::class,
        ],
        PolydockAppInstanceReadyForLagoonDeployment::class => [
            PolydockAppInstanceQueueLagoonDeployment::class
        ],
        PolydockAppInstanceReadyForLagoonRemoval::class => [
            PolydockAppInstanceQueueLagoonRemoval::class
        ],
        PolydockAppInstanceDeploymentSuccess::class => [
            PolydockAppInstanceCompleteDeploymentSucceeded::class
        ],
        PolydockAppInstanceDeploymentFailure::class => [
            PolydockAppInstanceCompleteDeploymentFailed::class
        ],
        PolydockAppInstanceDeploymentStillInProgress::class => [
            PolydockAppInstanceCompleteDeploymentOngoing::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        PolydockAppInstance::observe(new PolydockAppInstanceObserver);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
