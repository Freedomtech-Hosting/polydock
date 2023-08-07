<?php

use App\Models\PolydockAppInstance;
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
        Schema::create('polydock_app_instance_deployments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PolydockAppInstance::class);
            $table->string("status")->nullable();
            $table->string("lagoon_name")->nullable();
            $table->string("lagoon_remote_id")->nullable();
            $table->dateTime("lagoon_created_at")->nullable();
            $table->dateTime("lagoon_started_at")->nullable();
            $table->dateTime("lagoon_completed_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polydock_app_instance_deployments');
    }
};
