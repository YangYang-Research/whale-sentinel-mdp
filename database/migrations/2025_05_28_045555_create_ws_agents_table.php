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
            $table->text('description')->nullable();
            $table->text('profile');
            $table->unsignedBigInteger('instance_id');
            $table->foreign('instance_id')->references('id')->on('ws_instances')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Agents');
    }
};
