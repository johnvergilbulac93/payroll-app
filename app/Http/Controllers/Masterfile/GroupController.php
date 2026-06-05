<?php

namespace App\Http\Controllers\Masterfile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\GroupRequest;
use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    public function store(GroupRequest $request)
    {
        Group::create($request->validated());
        return redirect()->route('employees.create')
            ->with('message', 'Successfully saved.');
    }
}
