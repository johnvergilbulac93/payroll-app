<?php

namespace App\Trait;


use App\Models\LoanType;

use App\Models\Group;

use App\Models\Employee;

trait SettingDataHelper
{
    public function loanTypeData($search)
    {
        $result = LoanType::query()
            ->when($search, function ($query) use ($search) {
                $query->where('LoanType', 'like', "%{$search}%");
            })
            ->get()
            ->map(fn($item) => [
                'value' => strval($item->id),
                'label' => $item->LoanType,
            ]);
        return response()->json($result);
    }

    public function employeesData($search)
    {
        $result = Employee::query()
            ->when($search, function ($query) use ($search) {
                $query->where('FullName', 'like', "%{$search}%");
            })
            ->get()
            ->map(fn($item) => [
                'value' => $item->EmpNbr,
                'label' => $item->FullName,
            ]);
        return response()->json($result);
    }
}
