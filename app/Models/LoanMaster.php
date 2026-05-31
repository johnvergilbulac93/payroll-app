<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanMaster extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'LoanType',
        'EmpNbr',
        'OrigBal',
        'DedAmt',
        'StartDate',
        'Frequency',
        'BalanceAmt',
        'BalanceasofDate',
        'Crtd_Date',
        'Crtd_User',
        'LUpd_Date'
    ];
}
