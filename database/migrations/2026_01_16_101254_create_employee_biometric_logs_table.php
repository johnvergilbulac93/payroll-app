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
        Schema::create('employee_biometric_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('EmpID')->nullable();
            $table->dateTime('DateTime')->nullable();
            $table->smallInteger('VerificationMode')->nullable();
            $table->smallInteger('InOut')->nullable();
            $table->smallInteger('WordCode')->nullable();
            $table->smallInteger('Reserved')->nullable();
            $table->string('FileName')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_biometric_logs');
    }
};
