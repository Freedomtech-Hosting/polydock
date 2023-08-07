<?php

namespace App\Observers;

use App\Models\PolydockAppInstance;

class PolydockAppInstanceObserver
{
    /**
     * Handle the PolydockAppInstance "created" event.
     */
    public function created(PolydockAppInstance $polydockAppInstance): void
    {
        $polydockAppInstance->logInfo('App instance created');
    }
}
