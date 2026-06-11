<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permission::truncate();

        Permission::create([
            'name' => 'Dashboard',
            'slug' => 'dashboard-show',
            'parent_id' => null,
            'type' => 'action',
        ]);

        $employeeManagement = Permission::create([
            'name' => 'Employee Management',
            'slug' => 'employee-management-show',
            'parent_id' => null,
            'type' => 'module',
        ]);
        $employee201 = Permission::create([
            'name' => 'Employee 201',
            'slug' => 'employee-201-show',
            'parent_id' => $employeeManagement->id,
            'type' => 'group',
        ]);
        Permission::insert([
            [
                'name' => 'View Employee 201',
                'slug' => 'employee-201-view',
                'parent_id' => $employee201->id,
                'type' => 'action',
            ],
            [
                'name' => 'Add Employee 201',
                'slug' => 'employee-201-create',
                'parent_id' => $employee201->id,
                'type' => 'action',
            ],
            [
                'name' => 'Update Employee 201',
                'slug' => 'employee-201-edit',
                'parent_id' => $employee201->id,
                'type' => 'action',
            ],
            [
                'name' => 'Delete Employee 201',
                'slug' => 'account-201-delete',
                'parent_id' => $employee201->id,
                'type' => 'action',
            ],
        ]);
        $employeeLoan = Permission::create([
            'name' => 'Employee Loan',
            'slug' => 'employee-loan-show',
            'parent_id' => $employeeManagement->id,
            'type' => 'group',
        ]);
        Permission::insert([
            [
                'name' => 'View Employee Loan',
                'slug' => 'employee-loan-view',
                'parent_id' => $employeeLoan->id,
                'type' => 'action',
            ],
            [
                'name' => 'Add Employee Loan',
                'slug' => 'employee-loan-create',
                'parent_id' => $employeeLoan->id,
                'type' => 'action',
            ],
            [
                'name' => 'Update Employee Loan',
                'slug' => 'employee-loan-edit',
                'parent_id' => $employeeLoan->id,
                'type' => 'action',
            ],
            [
                'name' => 'Delete Employee Loan',
                'slug' => 'employee-loan-delete',
                'parent_id' => $employeeLoan->id,
                'type' => 'action',
            ],
        ]);

        $userManagement = Permission::create([
            'name' => 'User Management',
            'slug' => 'user-management-show',
            'parent_id' => null,
            'type' => 'module',
        ]);

        $accounts = Permission::create([
            'name' => 'Accounts',
            'slug' => 'accounts-show',
            'parent_id' => $userManagement->id,
            'type' => 'group',
        ]);
        Permission::insert([
            [
                'name' => 'View Account',
                'slug' => 'account-view',
                'parent_id' => $accounts->id,
                'type' => 'action',
            ],
            [
                'name' => 'Add Account',
                'slug' => 'account-create',
                'parent_id' => $accounts->id,
                'type' => 'action',
            ],
            [
                'name' => 'Update Account',
                'slug' => 'account-edit',
                'parent_id' => $accounts->id,
                'type' => 'action',
            ],
            [
                'name' => 'Delete Account',
                'slug' => 'account-delete',
                'parent_id' => $accounts->id,
                'type' => 'action',
            ],
            [
                'name' => 'Reset Account',
                'slug' => 'account-reset',
                'parent_id' => $accounts->id,
                'type' => 'action',
            ],
            [
                'name' => 'Account Permission',
                'slug' => 'permission-account',
                'parent_id' => $accounts->id,
                'type' => 'action',
            ],
        ]);
        $archives = Permission::create([
            'name' => 'Archives',
            'slug' => 'archives-show',
            'parent_id' => $userManagement->id,
            'type' => 'group',
        ]);
        Permission::insert([
            [
                'name' => 'View Archives',
                'slug' => 'archives-view',
                'parent_id' => $archives->id,
                'type' => 'action',
            ],
            [
                'name' => 'Add Archives',
                'slug' => 'archives-create',
                'parent_id' => $archives->id,
                'type' => 'action',
            ],
            [
                'name' => 'Update Archives',
                'slug' => 'archives-edit',
                'parent_id' => $archives->id,
                'type' => 'action',
            ],
            [
                'name' => 'Delete Archives',
                'slug' => 'archives-delete',
                'parent_id' => $archives->id,
                'type' => 'action',
            ]

        ]);
        $role = Permission::create([
            'name' => 'Role',
            'slug' => 'role-show',
            'parent_id' => $userManagement->id,
            'type' => 'group',
        ]);
        Permission::insert([
            [
                'name' => 'View Role',
                'slug' => 'role-view',
                'parent_id' => $role->id,
                'type' => 'action',
            ],
            [
                'name' => 'Add Role',
                'slug' => 'role-create',
                'parent_id' => $role->id,
                'type' => 'action',
            ],
            [
                'name' => 'Update Role',
                'slug' => 'role-edit',
                'parent_id' => $role->id,
                'type' => 'action',
            ],
            [
                'name' => 'Delete Role',
                'slug' => 'role-delete',
                'parent_id' => $role->id,
                'type' => 'action',
            ]

        ]);

        Permission::create([
            'name' => 'Attendanace Processing',
            'slug' => 'attendance-processing-show',
            'parent_id' => null,
            'type' => 'action',
        ]);
        Permission::create([
            'name' => 'Payroll Processing',
            'slug' => 'payroll-processing-show',
            'parent_id' => null,
            'type' => 'action',
        ]);
    }
}
