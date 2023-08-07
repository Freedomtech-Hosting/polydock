<?php

namespace App\Http\Controllers;

use App\Events\PolydockAppInstanceReadyForLagoonCreation;
use App\Events\PolydockAppInstanceReadyForLagoonRemoval;
use App\Models\PolydockAppInstance;
use App\Models\PolydockAppType;
use App\Models\PolydockLagoonCluster;
use App\Polydock\Engine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PolydockAppInstanceController extends Controller
{
    public function index() 
    {
        $appInstances = Auth::user()
            ->currentTeam
            ->polydockAppInstances
            ->load('polydockAppType')
            ->load('polydockLagoonCluster');
            
        return Inertia::render('PolydockAppInstances', [
            'polydockAppInstances' => $appInstances
        ]); 
    }

    public function new(PolydockAppType $polydockAppType)
    {
        $polydockLagoonClusters = PolydockLagoonCluster::all();
        return Inertia::render("Polydock/AppTypes/{$polydockAppType->engine_name}/New", [
            'polydockAppType' => $polydockAppType,
            'polydockLagoonClusters' => $polydockLagoonClusters
        ]);
    }

    public function create(PolydockAppType $polydockAppType, Request $request, Engine $polydockEngine)
    {
        $appEngine = $polydockEngine->getAppEngine($polydockAppType->engine_name);
        $validated = $appEngine->validateNewRequest($request);
        
        $appInstance = Auth::user()
        ->currentTeam
        ->polydockAppInstances()
        ->create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'polydock_lagoon_cluster_id' => $request->get('polydock_lagoon_cluster_id'),
            'polydock_app_type_id' => $polydockAppType->id,
        ]);

        $appInstance->setVariableValue('LND_ALIAS', $request->get('polydock_var_lnd_alias'));
        $appInstance->setVariableValue('BITCOIN_NETWORK', $request->get('polydock_var_bitcoin_network'));
        $appInstance->setVariableValue('LND_WALLETPASSWORD', $request->get('polydock_var_lnd_wallet_password'));

        PolydockAppInstanceReadyForLagoonCreation::dispatch($appInstance);

        return to_route('polydock.instances');
    }

    public function pollAppStatus(PolydockAppInstance $polydockAppInstance)
    {
        return json_encode(["status" => $polydockAppInstance->status]);
    }

    public function view(PolydockAppInstance $polydockAppInstance)
    {
        $polydockAppInstance->load('polydockAppType')
            ->load('polydockLagoonCluster')
            ->load('variables');
    
        return Inertia::render("Polydock/AppTypes/{$polydockAppInstance->polydockAppType->engine_name}/View", [
            'polydockAppInstance' => $polydockAppInstance,
        ]);
    }

    public function remove(PolydockAppInstance $polydockAppInstance)
    {
        PolydockAppInstanceReadyForLagoonRemoval::dispatch($polydockAppInstance);
        return to_route('polydock.instances');
    }
}
