<?php

namespace App\Http\Controllers\Masterfile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeFormRequest;
use App\Models\Employee;
use App\Trait\DateTraitHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Group;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        // $page = $request->input("page");
        $limit = $request->input("limit");

        $employees = Employee::filter($request)
            ->orderBy('updated_at', 'desc')
            ->paginate($limit)
            ->withQueryString(); // keep query string during pagination

        return inertia('employee/Employee', [
            'employees' => $employees,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }

    public function create()
    {
        return inertia('employee/Form', ['groups' => Group::select(['Code as value', 'Description as label'])->get()]);
    }

    public function store(EmployeeFormRequest $request)
    {
        $employee = $request->validated();
        $employee['FullName'] = $request->LastName . ', ' . $request->FirstName . ' ' . $request->MidName . ' ' . $request->Suffix;
        Employee::create($employee);
        return redirect()->route('employees.index')
            ->with('message', 'Successfully saved.');
    }
    public function show(string $id)
    {
        $employee = Employee::findByPublicId($id);
        return inertia('employee/Form', [
            'employee' => $employee,
            'groups' => Group::select(['Code as value', 'Description as label'])->get(),
        ]);
    }
    public function destroy(string $id)
    {
        $employee = Employee::findByPublicId($id);
        $employee->delete();
        return redirect()->route('employees.index')
            ->with('message', 'Successfully deleted.');
    }

    public function update(EmployeeFormRequest $request, string $id)
    {
        $employee = Employee::findByPublicId($id);
        $data = $request->validated();
        $data['FullName'] = $request->LastName . ', ' . $request->FirstName . ' ' . $request->MidName . ' ' . $request->Suffix;
        $employee->update($data);
        return redirect()->route('employees.index')
            ->with('message', 'Successfully updated.');
    }
}
