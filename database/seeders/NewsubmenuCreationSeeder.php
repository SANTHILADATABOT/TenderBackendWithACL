<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\menu_module;
use App\Models\sub_module_menu;
use App\Models\role_has_permission;

class NewsubmenuCreationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // menu_module::create(['role_id'=>1, 'name'=>'NewMenuName_With_Permission_Check', 'icoClass'=>'FontAwsome_calssname_for_this_menu','status'=>'0/1','menuLink'=>'#','aliasName'=>'MenuNameToDisplay','sorting_order'=>'MenuListingOrder' ]);

        $menu=menu_module::create(['user_role_id'=>1, 'name'=>'Expenses', 'icoClass'=>'fas fa-money','status'=>1,'menuLink'=>'#','aliasName'=>'Expenses','sorting_order'=>6]);


        $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>$menu->id, 'sorting_order'=>'1','name'=>'OtherExpenses','menuLink'=>'/tender/otherExpense','aliasName'=>'Other Expenses','status'=>'1', 'createdby'=>'1' ]);

        $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>$menu->id, 'sorting_order'=>'2','name'=>'Reimbursement','menuLink'=>'/tender/reimbursement','aliasName'=>'Reimbursement','status'=>'1', 'createdby'=>'1' ]);

        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>'4', 'sorting_order'=>'2','name'=>'call_to_bdm','menuLink'=>'/tender/calllog/calltobdm/','aliasName'=>'Call to BDM','status'=>'1', 'createdby'=>'1' ]);

        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>1, 'sorting_order'=>'16','name'=>'expense_type','menuLink'=>'/tender/master/expensetype/','aliasName'=>'Expense Type','status'=>'1', 'createdby'=>'1' ]);
        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>5, 'sorting_order'=>'2','name'=>'attendance_report','menuLink'=>'/tender/hr/attendancereport','aliasName'=>'Attendance Report','status'=>'1', 'createdby'=>'1' ]);
        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>$menu->id, 'sorting_order'=>'18','name'=>'attendance_type','menuLink'=>'/tender/hr/attendancetype','aliasName'=>'Attendance Type Master','status'=>'1', 'createdby'=>'1' ]);


        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>$menu->id, 'sorting_order'=>'18','name'=>'attendance_type','menuLink'=>'/tender/hr/attendancetype','aliasName'=>'Attendance Type Master','status'=>'1', 'createdby'=>'1' ]);
        

        // $role_has_permission = role_has_permission::create(['permission_id' => 0,'role_id'=>1, 'menu_modules_id'=> 1,'submenu_modules_id'=> $submenuid->id, 'can_view'=> 1,'can_add'=> 1,'can_edit'=> 1,'can_delete'=> 1]);

    }

    //seeding commend   
    // php artisan db:seed --class=NewsubmenuCreationSeeder
}

