<?php

namespace Database\Seeders;

use App\Models\PolydockAppInstance;
use App\Models\PolydockAppType;
use App\Models\PolydockLagoonCluster;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PolydockUserLightningSeeder extends PolydockUserSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $team = $this->createTeam();

        $user = $team->owner;
        $user->email = "lightninguser@polydock.io";
        $user->save();

        $this->makeOne($team);
    }

    public function makeOne(Team $team) : PolydockAppInstance 
    {
        $lightningApp = PolydockAppInstance::factory()
        ->createOne([
            'polydock_app_type_id' => PolydockAppType::where('engine_name','LightningNode')->first()->id,
            'polydock_lagoon_cluster_id' => PolydockLagoonCluster::all()->random()->id,
            'team_id' => $team->id
        ]);

        $lightningApp->setVariableValue("LND_ALIAS",Str::slug(Str::lower($lightningApp->name)));
        $lightningApp->setVariableValue("BITCOIN_NETWORK","testnet");
        $lightningApp->setVariableValue("LND_WALLETPASSWORD","freedomtech");

        return $lightningApp;
    }
}
