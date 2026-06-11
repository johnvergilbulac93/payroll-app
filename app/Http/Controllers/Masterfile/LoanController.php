<?php

namespace App\Http\Controllers\Masterfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Loan\LoanRequest;
use App\Models\LoanMaster;
use App\Models\LoanType;
use App\Trait\DateTraitHelper;

use App\Models\Employee;
use App\Models\Group;

class LoanController extends Controller
{
    use DateTraitHelper;

    public function index(Request $request)
    {
        $limit = $request->input("limit");
        $loanMaster = LoanMaster::with('employee', 'loanType')
            ->filter($request)
            ->orderBy('updated_at', 'desc')
            ->paginate($limit)
            ->withQueryString();

        return inertia('loan/LoanMaster', [
            'loans' => $loanMaster,
            'filters' => [
                'search' => $request->search,
            ]
        ]);
    }
    public function create()
    {
        return inertia('loan/Form', ['loanTypes' => LoanType::select(['id as value', 'LoanType as label'])->get()]);
    }

    public function store(LoanRequest $request)
    {
        $loanMaster = $request->validated();
        LoanMaster::create($loanMaster);
        return redirect()->route('loan.index')
            ->with('message', 'Successfully saved.');
    }
    public function show(string $id)
    {
        $loanMaster = LoanMaster::findByPublicId($id);
        return inertia('loan/Form', [
            'loans' => $loanMaster,
        ]);
    }
    public function destroy(string $id)
    {
        $employee = LoanMaster::findByPublicId($id);
        $employee->delete();
        return redirect()->route('loan.index')
            ->with('message', 'Successfully deleted.');
    }
    public function update(LoanRequest $request, string $id)
    {
        $loanMaster = LoanMaster::findByPublicId($id);
        $data = $request->validated();
        $loanMaster->update($data);
        return redirect()->route('loan.index')
            ->with('message', 'Successfully updated.');
    }
}
