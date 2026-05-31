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
        Schema::create('loan_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('LoanType');
            $table->string('EmpNbr');
            $table->decimal('OrigBal', 10, 2);
            $table->decimal('DedAmt', 10, 2);
            $table->dateTime('StartDate');
            $table->string('Frequency')->nullable();
            $table->decimal('BalanceAmt', 10, 2);
            $table->dateTime('BalanceasofDate');
            $table->dateTime('Crtd_Date');
            $table->string('Crtd_User');
            $table->dateTime('LUpd_Date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_masters');
    }
};
