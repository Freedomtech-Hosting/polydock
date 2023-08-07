<?php namespace App\Polydock\AppEngine;

use App\Models\PolydockAppInstance;
use App\Polydock\AppEngineInterface;
use Closure;
use Illuminate\Http\Request;

class LightningNodeAppEngine extends BaseAppEngine implements AppEngineInterface  {
    const POLYDOCK_ENGINE_NAME = "LightningNode";

    public function preCreateAppInstance(PolydockAppInstance $instance): bool
    {
        $pResult = parent::preCreateAppInstance($instance);

        if(!$pResult) {
            $instance->logError("Failing to base pre-create");
            return false;
        }

        $instance->logDebug("Base pre-create ok");

        $varsPresent = true;
        $requireVars = [
            "LND_ALIAS",
            "BITCOIN_NETWORK",
            "LND_WALLETPASSWORD",
        ];

        foreach($requireVars as $reqVar) {
            if(empty($instance->getVariableValue($reqVar))) {
                $instance->logError("Missing variable: " . $reqVar);
                $varsPresent = false;
            } else {
                $instance->logDebug("Variable located: " . $reqVar);
            }
        }

        if(!$varsPresent) {
            $instance->logError("One or more required variables missing");
            return false;
        } else {
            $instance->logDebug("Required variables nominal");
        }

        return true;
    }

    public function validateNewRequest(Request $request) {
        $baserules = $this->getBaseAppValidationRules();

        $request->validate(array_merge($baserules, [
            'polydock_var_lnd_wallet_password' => [
                'required',
                'min:8',
                'max:25',
                'bail'
            ],
            'polydock_var_lnd_wallet_password_repeat' => [
                'same:polydock_var_lnd_wallet_password'
            ],
            'polydock_var_lnd_alias' => [
                'required',
                'min:8',
                'max:35',
            ],
            'polydock_var_bitcoin_network' => [
                'required',
                'in:mainnet,testnet'
            ]
        ]));   
    }
}
