<?php

namespace Database\Seeders;

use App\Models\PolydockLagoonCluster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolydockLagoonClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Australia	Amazon	AU2	132
     * Switzerland 	Google	CH4	131
     * Germany	Amazon	DE3	115
     * Finland	Google	FI2	135
     * United Kingdom	Amazon	UK3	122
     * United States	Amazon	US2	126
     * United States	Google	US3	141
     */
    public function run(): void
    {
        PolydockLagoonCluster::create([
            'lagoon_cluster_code' => '132',
            'name' => 'Australia',
            'engine_name' => 'au2.amazee.io',
            'country_code' => 'au',
            'infra_code' => 'aws'
        ]);

        PolydockLagoonCluster::create([
            'lagoon_cluster_code' => '131',
            'name' => 'Switzerland',
            'engine_name' => 'ch4.amazee.io',
            'country_code' => 'ch',
            'infra_code' => 'gcp'
        ]);

        PolydockLagoonCluster::create([
            'lagoon_cluster_code' => '115',
            'name' => 'Germany',
            'engine_name' => 'de3.amazee.io',
            'country_code' => 'de',
            'infra_code' => 'aws'
        ]);

        PolydockLagoonCluster::create([
            'lagoon_cluster_code' => '135',
            'name' => 'Finland',
            'engine_name' => 'fi2.amazee.io',
            'country_code' => 'fi',
            'infra_code' => 'gcp'
        ]);

        PolydockLagoonCluster::create([
            'lagoon_cluster_code' => '122',
            'name' => 'United Kingdom',
            'engine_name' => 'uk3.amazee.io',
            'country_code' => 'uk',
            'infra_code' => 'aws'
        ]);

        PolydockLagoonCluster::create([
            'lagoon_cluster_code' => '126',
            'name' => 'United States',
            'engine_name' => 'us2.amazee.io',
            'country_code' => 'us',
            'infra_code' => 'aws'
        ]);

        PolydockLagoonCluster::create([
            'lagoon_cluster_code' => '141',
            'name' => 'United States',
            'engine_name' => 'us3.amazee.io',
            'country_code' => 'us',
            'infra_code' => 'gcp'
        ]);
    }
}
