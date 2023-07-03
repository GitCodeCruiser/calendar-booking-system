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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('buffer_time')->default('00:00:00')->comment('break time between each appointment');;
            $table->time('duration');
            $table->integer('scheduling_window')->default(7)->comment('Number of days allowed to schedule in advance');;
            $table->integer('max_appointments_per_slot')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
