<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;

class PermissionService {
    public function getPermission() {
        return Permission::all();
    }

    public function createPermission($data) {
        return Permission::create([
            'name' => $data->name,
            'guard_name' => 'web'
        ]);
    }

    public function updatePermission($permission, $data) {
        $permission->update(['name' => $data->name]);

        return $permission;
    }
}