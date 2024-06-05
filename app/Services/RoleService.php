<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class RoleService {
    public function getRole() {
        return Role::all();
    }

    public function createRole($data) {
        $role = Role::create([
            'name' => $data->name,
            'guard_name' => 'web'
        ]);
        $role->givePermissionTo($data->permissions);

        return $role;
    }

    public function updateRole($role, $data) {
        $role->update(['name' => $data->name]);
        $role->syncPermissions($data->permissions);

        return $role;
    }
}