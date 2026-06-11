<?php

namespace App\Http\Controllers\SystemAdministration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use Illuminate\Http\Request;

use App\Models\User;
use Inertia\Inertia;

use App\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $limit = $request->input("limit");

        $roles = Role::filter($request->all())
            ->orderBy('updated_at', 'desc')
            ->paginate($limit)
            ->withQueryString(); // keep query string during pagination
        return inertia('user/roles/Roles', [
            'roles' => $roles,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }
    public function store(RoleRequest $request)
    {
        Role::create($request->validated());
        return redirect()->route('users.roles.index')
            ->with('message', 'Successfully saved.');
    }
    public function update(RoleRequest $request, string $id)
    {
        $role = Role::findByPublicId($id);
        $role->update($request->validated());
        return redirect()->route('users.roles.index')
            ->with('message', 'Successfully saved.');
    }
    public function destroy(string $id)
    {
        $role = Role::findByPublicId($id);
        $role->delete();
        return redirect()->route('users.roles.index')
            ->with('message', 'Successfully deleted.');
    }
}
