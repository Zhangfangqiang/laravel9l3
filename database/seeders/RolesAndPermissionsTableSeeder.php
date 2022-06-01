<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 先创建权限
        Permission::create(['name' => 'manage_contents']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'edit_settings']);

        // 创建站长角色，并赋予权限
        $founder = Role::create(['name' => 'Founder']);
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTo('edit_settings');

        // 创建管理员角色，并赋予权限
        $maintainer = Role::create(['name' => 'Maintainer']);
        $maintainer->givePermissionTo('manage_contents');

        $user = User::find(1);
        $user->assignRole('Founder');

        // 将 2 号用户指派为『管理员』
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
