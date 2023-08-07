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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->string('name');
            $table->boolean('personal_team');
            $table->text('lagoon_ssh_pub_key')->nullable();
            $table->text('lagoon_ssh_priv_key')->nullable();
            $table->text('lagoon_token')->nullable();
            $table->dateTime('lagoon_token_expires')->nullable();
            $table->text('git_deploy_ssh_pub_key')->nullable();
            $table->text('git_deploy_ssh_priv_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
