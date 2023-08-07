<?php

namespace Database\Seeders;

use App\Models\PolydockAppType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolydockAppTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PolydockAppType::create([
            "name" => "Lightning Node",
            "description" => "Participate in the lightning network",
            "icon_path" => "/polydock-assets/engines/lightning-node/app-icon.jpg",
            "engine_name" => "LightningNode",
            "giturl" => "git@github.com:freedomtech-hosting/boltup-lagoon.git",
            "default_deploy_branch" => "dev"
        ]);

        PolydockAppType::create([
            "name" => "Nostr Relay",
            "description" => "Participate as a Nostr relay",
            "icon_path" => "/polydock-assets/engines/nostr-relay/app-icon.jpg",
            "engine_name" => "NostrRelay",
            "giturl" => "",
            "default_deploy_branch" => "dev"
        ]);

        PolydockAppType::create([
            "name" => "Fedimint",
            "description" => "Run a Fedimint",
            "icon_path" => "/polydock-assets/engines/fedimint/app-icon.jpg",
            "engine_name" => "Fedimint",
            "giturl" => "",
            "default_deploy_branch" => "dev"
        ]);
    }
}
