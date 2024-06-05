<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    protected $guarded = [];

    public static function hasActiveRolePermission($user, $ability) {
        if($user->activeRole) {
            return $user->activeRole->hasPermissionTo($ability);
        }
        
        return null;
    }

    public function activeRole() {
        return $this->belongsTo(Role::class, 'active_role_id', 'id');
    }
}
