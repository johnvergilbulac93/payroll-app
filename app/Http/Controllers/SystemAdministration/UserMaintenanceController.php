<?php

namespace App\Http\Controllers\SystemAdministration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\User;

class UserMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        // $page = $request->input("page");
        $limit = $request->input("limit");

        $users = User::filter($request)
            ->orderBy('updated_at', 'desc')
            ->paginate($limit)
            ->withQueryString(); // keep query string during pagination

        return inertia('user/User', [
            'users' => $users,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }
}
