<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

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
}
