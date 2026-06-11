<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\LoanType;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class LoanMaster extends Model
{
    use SoftDeletes, HasUlids;

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

    protected $hidden = [
        'id'
    ];

    // Direct Laravel to look for 'public_id' instead of 'id' in URLs
    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    // Direct the trait to fill 'public_id' instead of the default 'id'
    public function uniqueIds(): array
    {
        return ['public_id'];
    }

    public function loanType()
    {
        return $this->belongsTo(LoanType::class, 'LoanTypeID');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmpNbr', 'EmpNbr');
    }
    public static function findByPublicId(string $id): self
    {
        return static::where('public_id', '=', $id)->firstOrFail();
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
