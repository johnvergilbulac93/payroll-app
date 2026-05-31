<?php

namespace App\Http\Controllers\Masterfile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeFormRequest;
use App\Models\Employee;
use App\Trait\DateTraitHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{

    use DateTraitHelper;
    public function index(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input("page");
        $limit = $request->input("limit");


        $employees = Employee::query()
            ->when($search, function ($query, $search) {
                $query->where('FullName', 'like', "%{$search}%")
                    ->orWhere('EmpNbr', 'like', "%{$search}%");
            })
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
        return inertia('employee/Form');
    }

    public function store(EmployeeFormRequest $request)
    {
        $employee = $request->validated();
        $employee['FullName'] = $request->LastName . ', ' . $request->FirstName . ' ' . $request->MidName . ' ' . $request->Suffix;
        $employee['BirthDate'] =  $this->formatToMDY($request->BirthDate);
        $employee['EmployDate'] =  $this->formatToMDY($request->EmployDate);
        $employee['RegularDate'] =  $this->formatToMDY($request->RegularDate);
        $employee['ResignDate'] =  $this->formatToMDY($request->ResignDate);
        Employee::create($employee);
        return redirect()->route('employees.index')
            ->with('message', 'Successfully saved.');
    }
    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return inertia('employee/Form', [
            'employee' => $employee,
        ]);
    }
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->route('employees.index')
            ->with('message', 'Successfully deleted.');
    }

    public function update(EmployeeFormRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $data = $request->validated();
        $data['FullName'] = $request->LastName . ', ' . $request->FirstName . ' ' . $request->MidName . ' ' . $request->Suffix;
        $data['BirthDate'] =  $this->formatToMDY($request->BirthDate);
        $data['EmployDate'] =  $this->formatToMDY($request->EmployDate);
        $data['RegularDate'] =  $this->formatToMDY($request->RegularDate);
        $data['ResignDate'] =  $this->formatToMDY($request->ResignDate);
        $employee->update($data);
        return redirect()->route('employees.index')
            ->with('message', 'Successfully updated.');
    }
}
