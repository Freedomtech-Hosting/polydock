<?php namespace App\Polydock\AppEngine;

use App\Polydock\AppEngineInterface;
use Illuminate\Http\Request;


class FedimintAppEngine extends BaseAppEngine implements AppEngineInterface  {
    const POLYDOCK_ENGINE_NAME = "Fedimint";

    public function validateNewRequest(Request $request) {
        $request->validate([
            'name' => 'required|max:35'
        ]);   
    }
}
