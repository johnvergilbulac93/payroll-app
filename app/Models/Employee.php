<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes, HasUlids;

    protected $fillable = [
        'EmpNbr',
        'Group',
        'FirstName',
        'MidName',
        'LastName',
        'Suffix',
        'FullName',
        'Address',
        'CityProv',
        'BirthDate',
        'EmployDate',
        'RegularDate',
        'Position',
        'Assignment',
        'SalaryGrade',
        'BasicPay',
        'DailyRate',
        'HourlyRate',
        'Status',
        'SSSNbr',
        'PHICNbr',
        'HDMFNbr',
        'TIN',
        'Degree',
        'AllowReg',
        'ResignDate',
        'BPIATM',
        'BPIEmpCode',
        'PIN',
        'PERAAID'
    ];

    protected $hidden = [
        'id'
    ];
    public static function findByPublicId(string $id): self
    {
        return static::where('public_id', '=', $id)->firstOrFail();
    }

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
    protected function casts(): array
    {
        return [
            'Status' => 'boolean',
        ];
    }

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->orWhere('FullName', 'like', "%{$search}%")
                    ->orWhere('EmpNbr', 'like', "%{$search}%");
            });
        });
    }
}
