<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolydockUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $team = $this->createTeam();

        $user = $team->owner;
        $user->email = "newuser@polydock.io";
        $user->save();
    }

    public function createTeam() {
        $team = Team::factory()
            ->createOne();

        if(file_exists("lagoon/ssh_test_keys/lagoon_id_rsa"))
        {
            $team->lagoon_ssh_priv_key = file_get_contents("lagoon/ssh_test_keys/lagoon_id_rsa");
        }

        if(file_exists("lagoon/ssh_test_keys/lagoon_id_rsa.pub"))
        {
            $team->lagoon_ssh_pub_key = file_get_contents("lagoon/ssh_test_keys/lagoon_id_rsa.pub");
        }

        if(file_exists("lagoon/ssh_test_keys/git_deploy_id_rsa"))
        {
            $team->git_deploy_ssh_priv_key = file_get_contents("lagoon/ssh_test_keys/git_deploy_id_rsa");
        }

        if(file_exists("lagoon/ssh_test_keys/git_deploy_id_rsa.pub"))
        {
            $team->git_deploy_ssh_pub_key = file_get_contents("lagoon/ssh_test_keys/git_deploy_id_rsa.pub");
        }

        $team->save();

        return $team;
    }
}
