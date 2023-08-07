<?php namespace App\Polydock;

use App\Models\PolydockAppInstance;
use Illuminate\Http\Request;

interface AppEngineInterface {
    public function __construct($config = []);

    public static function getMachineName() : string;

    public function preCreateAppInstance(PolydockAppInstance $instance) : bool;
    public function createAppInstance(PolydockAppInstance $instance) : bool;
    public function postCreateAppInstance(PolydockAppInstance $instance) : bool;

    public function preDeployAppInstance(PolydockAppInstance $instance) : bool;
    public function deployAppInstance(PolydockAppInstance $instance) : bool;
    public function postDeployAppInstance(PolydockAppInstance $instance) : bool;

    public function preRemoveAppInstance(PolydockAppInstance $instance) : bool;
    public function removeAppInstance(PolydockAppInstance $instance) : bool;
    public function postRemoveAppInstance(PolydockAppInstance $instance) : bool;

    public function updateOrCreateLagoonDeploymentDetailsByDeploymentName(PolydockAppInstance $instance, $deploymentName) : bool;

    public function validateNewRequest(Request $request);
}
