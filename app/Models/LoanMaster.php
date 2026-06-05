<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\LoanType;
use App\Models\Employee;

class LoanMaster extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'LoanTypeID',
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
    public function loanType()
    {
        return $this->belongsTo(LoanType::class, 'LoanTypeID');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmpNbr', 'EmpNbr');
    }
    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('EmpNbr', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($q) use ($search) {
                        $q->where('FullName', 'like', "%{$search}%");
                    })
                    ->orWhereHas('loanType', function ($q) use ($search) {
                        $q->where('LoanType', 'like', "%{$search}%");
                    });
            });
        });
    }
}
