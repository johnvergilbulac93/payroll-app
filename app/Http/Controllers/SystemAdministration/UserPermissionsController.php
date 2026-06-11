<?php

namespace App\Http\Controllers\SystemAdministration;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class UserPermissionsController extends Controller
{
    public function index(string $id)
    {
        $user = User::findByPublicId($id);
        $permissions = Permission::treeWithChecked(
            $user->permissions()->pluck('permissions.id')
        );
        return response()->json(['data' => $permissions, 'status' => 'success']);
    }
    public function userPermissionUpdate(Request $request, string $id)
    {
        $user = User::findByPublicId($id);
        $user->permissions()->sync($request->permission_ids);
        return redirect()->route('users.accounts.index')
            ->with('message', 'Successfully saved.');
    }
    private function attachChecked(object $nodes, object $assignedIds)
    {
        return $nodes->map(function ($node) use ($assignedIds) {
            $node->checked = $assignedIds->contains($node->id);

            if (optional($node->children)->isNotEmpty()) {
                $node->children = $this->attachChecked($node->children, $assignedIds);
            }

            return $node;
        });
    }
}
