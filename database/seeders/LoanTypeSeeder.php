<?php

namespace Database\Seeders;

use App\Models\LoanType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loanTypes = [
            ['Code' => 10, 'LoanType' => 'SSS Salary Loan'],
            ['Code' => 11, 'LoanType' => 'SSS Calamity Loan'],
            ['Code' => 20, 'LoanType' => 'PAG-IBIG Loan'],
            ['Code' => 30, 'LoanType' => 'PERAA Loan'],
            ['Code' => 40, 'LoanType' => 'CA SALARY/PERSONAL'],
            ['Code' => 41, 'LoanType' => 'CA - HEALTH CARD'],
            ['Code' => 42, 'LoanType' => 'CA - SCHOLARSHIP'],
            ['Code' => 43, 'LoanType' => 'CA - Others'],
        ];
        LoanType::insert($loanTypes);
    }
}
