<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\SettingDataHelper;
use App\Http\Requests\Group\GroupRequest;
use App\Http\Requests\Helper\LoanTypeRequest;

use App\Models\Group;
use App\Models\LoanType;

class HelperController extends Controller
{
    use SettingDataHelper;

    public function newGroup(GroupRequest $request)
    {
        Group::create($request->validated());
        return redirect()->route($request->Route)
            ->with('message', 'Successfully saved.');
    }

    public function newLoanType(LoanTypeRequest $request)
    {
        LoanType::create($request->validated());
        return redirect()->route($request->Route)
            ->with('message', 'Successfully saved.');
    }

    public function getLoanType(Request $request)
    {
        return $this->loanTypeData($request->search);
    }
    public function getEmployees(Request $request)
    {
        return $this->employeesData($request->search);
    }
}
