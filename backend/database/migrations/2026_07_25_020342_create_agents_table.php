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
        Schema::create('server-agents', function (Blueprint $table) {
            $table->id();

            $table->string('agent_id')->unique();

            $table->string('hostname')->nullable();
            $table->string('os')->nullable();
            $table->string('arch')->nullable();

            $table->double('cpu_usage')->default(0);
            $table->double('memory_percent')->default(0);
            $table->double('disk_percent')->default(0);

            $table->string('status')->default('offline');

            $table->timestamp('last_seen')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
