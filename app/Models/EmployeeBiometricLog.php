<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeBiometricLog extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'EmpID',
        'DateTime',
        'VerificationMode',
        'InOut',
        'WordCode',
        'Reserved',
        'FileName'
    ];
}
