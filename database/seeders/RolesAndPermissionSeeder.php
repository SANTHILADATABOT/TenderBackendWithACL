<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Reset cached roles and permissions
         app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //  // create permissions
        // $viewPer= Permission::create(['name' => 'view']);
        // $Perde =Permission::create(['name' => 'delete']);
        // $Ceate= Permission::create(['name' => 'create']);
        // $edit=Permission::create(['name' => 'edit']);
 

        // //  // create roles and assign created permissions
 
        // //  // this can be done as separate statements
        //  $role = Role::create(['name' => 'Guest']);
        //  $role->givePermissionTo('view');
 
        // //  // or may be done by chaining
        //  $role = Role::create(['name' => 'Field-Executive'])
        //      ->givePermissionTo(['create', 'view']);
 
        //  $role = Role::create(['name' => 'Admin']);
        //  $role->givePermissionTo(Permission::all());

        //  User::create([
        //     'name' => 'Prabakaran',
        //     'email' => 'prabakaran@santhila.com',
        //     'password' => bcrypt('123'),
        // ]);

        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@santhila.com',
        //     'password' => bcrypt('123'),
        // ]);

        USER::find(1)->assignRole("Field-Executive");
        USER::find(2)->assignRole("Admin");



    }
}
