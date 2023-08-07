<?php

namespace App\Http\Controllers;

use App\Models\PolydockAppInstance;
use App\Models\PolydockAppType;
use App\Models\PolydockLagoonCluster;
use App\Polydock\Engine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PolydockDashboardController extends Controller
{
    public function index() 
    {
        return Inertia::render('Dashboard', ['stats' => $this->getStats()]);
    }
 

    public function pollDashboardStats()
    {
        return json_encode(['stats' => $this->getStats()]);
    }

    public function getStats() {
        return [
            [
                'name' => 'Number of apps',
                'value' => Auth::user()->currentTeam->polydockAppInstances->count(),
                'unit' => 'apps' 
            ],
            [
                'name' => 'Total deployments',
                'value' => Auth::user()->currentTeam->polydockAppInstanceDeployments->count(),
                'unit' => 'deploys' 
            ],
            [
                'name' => 'Deployments in progress',
                'value' => Auth::user()->currentTeam->polydockAppInstanceDeploymentsInProgress->count(),
                'unit' => 'deploys' 
            ],  
        ];  
    }
}
