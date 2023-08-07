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
        Schema::create('polydock_app_instance_variables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PolydockAppInstance::class);
            $table->string("key")->notNull();
            $table->string("value")->notNull();
            $table->string("lagoon_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polydock_app_instance_variables');
    }
};
