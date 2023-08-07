<?php

use App\Models\PolydockAppType;
use App\Models\PolydockLagoonCluster;
use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('polydock_app_instances', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description")->nullable();

            $table->foreignIdFor(Team::class);
            $table->foreignIdFor(PolydockAppType::class);
            $table->foreignIdFor(PolydockLagoonCluster::class);

            $table->string("status")->default("new");

            $table->string("lagoon_project")->nullable();
            $table->bigInteger("lagoon_project_id")->nullable();
            $table->json("lagoon_project_json")->nullable();

            $table->string("lagoon_environment")->nullable();
            $table->bigInteger("lagoon_environment_id")->nullable();
            $table->json("lagoon_environment_json")->nullable();

            $table->json("lagoon_routes_json")->nullable();

            $table->string("giturl")->nullable();
            $table->string("deploy_branch")->nullable();

            $table->string("latest_deploy_name")->nullable();

            $table->json("metadata")->nullable();

            $table->dateTime("decommission_at")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polydock_app_instances');
    }
};
