<?php

namespace App\Services;

use App\Helpers\NumberHelper;
use App\Models\User;
use Carbon\Carbon;

class AccountService {
    public function updateActiveRole($role_id) {
        return User::find(auth()->id())->update(['active_role_id' => $role_id]);
    }

    public function getAccount() {
        return User::get();
    }

    public function createAccount($data) {
        $roles = $data->roles;

        $data = $data->merge(['active_role_id' => $roles[0]]);
        $user = User::create($data->only('m_karyawan_id', 'nik', 'name', 'active_role_id'));
        $user->assignRole($roles);

        return $user;
    }

    public function updateAccount($user, $data) {
        $roles = $data->roles;

        $data = $data->merge(['active_role_id' => $roles[0]]);
        $new_user = tap($user)->update($data->only('m_karyawan_id', 'nik', 'name', 'active_role_id'))->fresh();
        $new_user->syncRoles($data->roles);

        return $new_user;
    }
}