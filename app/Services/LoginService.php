<?php

namespace App\Services;

use App\Models\User;

class LoginService {
    public function getUser($username) {
        return User::where('username', $username)->first();
    }
}