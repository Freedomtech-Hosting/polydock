<?php

namespace Database\Seeders;

use App\Events\PolydockAppInstanceReadyForLagoonCreation;
use App\Models\PolydockAppType;
use App\Models\PolydockAppInstance;
use App\Models\PolydockLagoonCluster;
use App\Models\Team;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DevFFD10OwnRepo extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PolydockLagoonClusterSeeder::class);
        $this->call(PolydockAppTypeFFSeeder::class);
        $this->call(PolydockUserSeeder::class);

        $team = Team::first();

        $lightningApp = PolydockAppInstance::factory()
        ->createOne([
            'polydock_app_type_id' => PolydockAppType::where('engine_name','D10Demo')->first()->id,
            'polydock_lagoon_cluster_id' => PolydockLagoonCluster::all()->random()->id,
            'team_id' => $team->id
        ]);

        $lightningApp->setVariableValue("SOMEVAR","someval");
        PolydockAppInstanceReadyForLagoonCreation::dispatch($lightningApp);
    }
}
