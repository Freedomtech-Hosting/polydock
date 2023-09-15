<?php namespace App\Polydock\AppEngine;

use App\Models\PolydockAppInstance;
use App\Polydock\AppEngineInterface;
use Closure;
use Illuminate\Http\Request;

class D10DemoAppEngine extends BaseAppEngine implements AppEngineInterface  {
    const POLYDOCK_ENGINE_NAME = "D10Demo";

    public function preCreateAppInstance(PolydockAppInstance $instance): bool
    {
        $pResult = parent::preCreateAppInstance($instance);

        if(!$pResult) {
            $instance->logError("Failing to base pre-create");
            return false;
        }

        $instance->logDebug("Base pre-create ok");

        return true;
    }

    public function validateNewRequest(Request $request) {
        $request->validate([
            'name' => 'required|max:35'
        ]);   
    }
}
