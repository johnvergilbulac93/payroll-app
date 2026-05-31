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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('EmpNbr', 15)->nullable();
            $table->char('Group', 2)->nullable();
            $table->string('FirstName')->nullable();
            $table->string(column: 'MidName')->nullable();
            $table->string(column: 'LastName')->nullable();
            $table->string(column: 'Suffix')->nullable();
            $table->string(column: 'FullName')->nullable();
            $table->string(column: 'Address')->nullable();
            $table->string(column: 'CityProv')->nullable();
            $table->date(column: 'BirthDate')->nullable();
            $table->date(column: 'EmployDate')->nullable();
            $table->date(column: 'RegularDate')->nullable();
            $table->string(column: 'Position')->nullable();
            $table->string(column: 'Assignment')->nullable();
            $table->string(column: 'SalaryGrade')->nullable();
            $table->decimal('BasicPay', 10, 2)->nullable();
            $table->decimal('DailyRate', 10, 2)->nullable();
            $table->decimal('HourlyRate', 10, 2)->nullable();
            $table->string(column: 'Status')->nullable();
            $table->string(column: 'SSSNbr')->nullable();
            $table->string(column: 'PHICNbr')->nullable();
            $table->string(column: 'HDMFNbr')->nullable();
            $table->string(column: 'TIN')->nullable();
            $table->string(column: 'Degree')->nullable();
            $table->decimal('AllowReg', 10, 2)->nullable();
            $table->date(column: 'ResignDate')->nullable();
            $table->string(column: 'BPIATM')->nullable();
            $table->string(column: 'BPIEmpCode')->nullable();
            $table->string(column: 'PIN')->nullable();
            $table->string(column: 'PERAAID')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
