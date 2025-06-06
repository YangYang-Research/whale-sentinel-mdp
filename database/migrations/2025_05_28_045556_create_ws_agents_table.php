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
            $table->string('key', 255);
            $table->text('description')->nullable();
            $table->string('ipaddress')->nullable();
            $table->string('type', 255);
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
