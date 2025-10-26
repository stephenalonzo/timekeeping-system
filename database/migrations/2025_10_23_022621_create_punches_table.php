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
        // Schema::create('punches', function (Blueprint $table) {
        //     $table->id();
        //     $table->bigInteger('employeeId');
        //     $table->bigInteger('refNo');
        //     $table->timestamp('day_in')->nullable();
        //     $table->timestamp('lunch_out')->nullable();
        //     $table->timestamp('lunch_in')->nullable();
        //     $table->timestamp('day_out')->nullable();
        //     $table->bigInteger('time_worked')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punches');
    }
};
