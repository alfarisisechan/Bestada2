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
        Schema::create('slots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vehicle_number', 10);
            $table->integer('block_code');
            $table->enum('status', ['in', 'out']);
            $table->date('time_in')->nullable();;
            $table->date('time_out')->nullable();;
            $table->foreign('vehicle_number')->references('vehicle_number')->on('vehicles');
            $table->foreign('block_code')->references('block_code')->on('blocks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
