<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // DB::table('users')->insert([
        //     'name' => 'Super Admin',
        //     'username' => 'phantanloi',
        //     'email' => 'phantanloi@admin.com',
        //     'password' => Hash::make('loiprosotrue'),
        //     'super' => true,
        //     'status' => true,
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now(),
        // ]);
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'view']);
        Permission::create(['name' => 'public']);
        Permission::create(['name' => 'edit']);
        Permission::create(['name' => 'delete']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'course-admin']);
        $role->givePermissionTo(Permission::all());
        $role = Role::create(['name' => 'course-view']);
        $role->givePermissionTo('view');
        $role = Role::create(['name' => 'course-edit']);
        $role->givePermissionTo(['edit','view']);

        // this can be done as separate statements
        $role = Role::create(['name' => 'user-admin']);
        $role->givePermissionTo(Permission::all());
        $role = Role::create(['name' => 'user-view']);
        $role->givePermissionTo('view');
        $role = Role::create(['name' => 'user-edit']);
        $role->givePermissionTo(['edit','view']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        $superadmin = User::find(1);

        $superadmin->assignRole(Role::all());
    }
}
