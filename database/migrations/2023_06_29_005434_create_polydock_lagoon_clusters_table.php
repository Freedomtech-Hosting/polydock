<?php

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
        Schema::create('polydock_lagoon_clusters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("lagoon_cluster_code");
            $table->string("name");
            $table->string("engine_name");
            $table->string("country_code");
            $table->string("infra_code");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polydock_lagoon_clusters');
    }
};
