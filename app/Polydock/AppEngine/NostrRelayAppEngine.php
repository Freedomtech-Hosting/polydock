<?php namespace App\Polydock\AppEngine;

use App\Polydock\AppEngineInterface;
use Illuminate\Http\Request;


class NostrRelayAppEngine extends BaseAppEngine implements AppEngineInterface  {
    const POLYDOCK_ENGINE_NAME = "NostrRelay";
    
    public function validateNewRequest(Request $request) {
        $request->validate([
            'name' => 'required|max:35'
        ]);   
    }
}
