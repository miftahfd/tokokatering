<?php

namespace Database\Seeders;

use App\Helpers\Enums\RoleId;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # permission
        $permissions = [
            'permission.read', 'permission.create', 'permission.update', 'permission.delete',
            'role.read', 'role.create', 'role.update', 'role.delete',
            'user.read', 'user.create', 'user.update', 'user.delete',
            'log-viewer.read',
            'menu.read', 'menu.create', 'menu.update', 'menu.delete',
            'order.read'
        ];
        $super_admin_give_permissions = [];
        $merchant_give_permissions = [];
        $customer_give_permissions = [];
        foreach($permissions as $permission) {
            $create_permission = Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);

            if(!in_array($permission, ['menu.read', 'menu.create', 'menu.update', 'menu.delete', 'order.read'])) {
                $super_admin_give_permissions[] = $create_permission;
            } elseif(in_array($permission, ['menu.read', 'menu.create', 'menu.update', 'menu.delete', 'order.read'])) {
                $merchant_give_permissions[] = $create_permission;
            } elseif(in_array($permission, ['order.read'])) {
                $customer_give_permissions[] = $create_permission;
            }
        }

        # role
        Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'Merchant',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'Customer',
            'guard_name' => 'web'
        ]);
        Role::find(RoleId::SUPERADMIN)->givePermissionTo($super_admin_give_permissions);
        Role::find(RoleId::MERCHANT)->givePermissionTo($merchant_give_permissions);
        Role::find(RoleId::CUSTOMER)->givePermissionTo($customer_give_permissions);

        # user
        User::create([
            'email' => 'superadmin@tokokatering.com',
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'name' => 'Admin Tokokatering.com',
            'active_role_id' => RoleId::SUPERADMIN,
        ])->assignRole([RoleId::SUPERADMIN]);
    }
}
