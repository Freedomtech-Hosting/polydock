<?php

namespace Database\Seeders;

use App\Models\PolydockAppType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolydockAppTypeAIOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PolydockAppType::create([
            "name" => "Drupal 10 Demo",
            "description" => "Get an amazee.io Drupal 10 demo instance",
            "icon_path" => "/polydock-assets/engines/d10demo/app-icon.png",
            "engine_name" => "D10Demo",
            "giturl" => "https://github.com/lagoon-examples/drupal10-base.git",
            "default_deploy_branch" => "main"
        ]);
    }
}
