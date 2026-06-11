<?php

namespace App\Http\Controllers\SystemAdministration;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserMaintenanceController extends Controller
{


    public function listUsers(Request $request)
    {
        $search = $request->input('search');
        $limit = $request->input("limit");

        $users = User::filter($request->all())
            ->orderBy('updated_at', 'desc')
            ->paginate($limit)
            ->withQueryString(); 

        return inertia('user/accounts/User', [
            'users' => $users,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }
    public function create()
    {
        return inertia('user/accounts/Form');
    }
    public function store(UserRequest $request)
    {
        $user = $request->validated();
        $user['password'] = Hash::make(12345678);
        User::create($user);
        return redirect()->route('users.accounts.index')
            ->with('message', 'Successfully saved.');
    }
    public function show(string $id)
    {
        $user = User::findByPublicId($id);
        return inertia('user/accounts/Form', [
            'user' => $user
        ]);
    }
    public function update(UserRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();
        $user->update($data);
        return redirect()->route('users.accounts.index')
            ->with('message', 'Successfully updated.');
    }
    public function destroy(string $id)
    {
        $user = User::findByPublicId($id);
        $user->delete();
        return redirect()->route('users.accounts.index')
            ->with('message', 'Successfully deleted.');
    }
    public function listArchives(Request $request)
    {
        $search = $request->input('search');
        $limit = $request->input("limit");

        $users = User::filter($request->all())
            ->onlyTrashed()
            ->orderBy('updated_at', 'desc')
            ->paginate($limit)
            ->withQueryString();

        return inertia('user/archives/Archives', [
            'users' => $users,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }
    public function resetPassword(string $id)
    {
        $user = User::findByPublicId($id);
        $user->update(['password' => Hash::make('12345678')]);
        return redirect()->route('users.accounts.index')
            ->with('message', 'Successfully deleted.');
    }
    public function restore(string $id)
    {
        $user = User::withTrashed()->where('public_id', $id);
        $user->restore();
        return redirect()->route('users.archives.index')
            ->with('message', 'Successfully deleted.');
    }
    public function deleteArchives(string $id)
    {
        $user  = User::withTrashed()->where('public_id', $id);
        $user->forceDelete();
        return redirect()->route('users.archives.index')
            ->with('message', 'Successfully deleted.');
    }
}
