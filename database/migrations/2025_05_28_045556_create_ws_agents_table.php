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
        Schema::create('ws_agents', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('agent_id', 255)->unique();
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('type', 255);
            $table->string('status')->default('disconnect');
            $table->text('profile');
            
            $table->unsignedBigInteger('application_id')->unique();
            $table->foreign('application_id')->references('id')->on('ws_applications')->onDelete('cascade');
            
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ws_agents');
    }
};
