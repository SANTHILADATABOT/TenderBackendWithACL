<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\menu_module;
use App\Models\sub_module_menu;

class NewMenuCreationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        menu_module::create(['role_id'=>1, 'name'=>'Master'])
    }
}
