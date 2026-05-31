<?php

namespace App\Http\Controllers\SystemAdministration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserMaintenanceController extends Controller
{
    public function index()
    {
        return inertia('user/User');
    }
    
}
