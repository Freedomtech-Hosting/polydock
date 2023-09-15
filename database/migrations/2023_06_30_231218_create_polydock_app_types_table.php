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
        Schema::create('polydock_app_types', function (Blueprint $table) {
            $table->id();
            $table->string("name")->notNull();
            $table->text("description")->notNull();
            $table->string("engine_name")->notNull();
            $table->string("icon_path")->default("/polydock-assets/engines/app-icon.jpg");
            $table->string("giturl")->nullable();
            $table->string("default_deploy_branch")->default("main");
            $table->boolean("create_app_fork")->default(false);
            $table->string("fork_org")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polydock_app_types');
    }
};
