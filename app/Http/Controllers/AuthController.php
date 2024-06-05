<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected LoginService $loginService) {
        $this->loginService = $loginService;
    }

    public function index() {
        return view('login');
    }

    public function login(Request $request) {
        $username = $request->username;
        $password = $request->password;
        
        $user = $this->loginService->getUser($username);

        if(!$user) {
            return back()->withErrors(['username' => 'Akun tidak ditemukan']);
        }

        if(password_verify($password, $user->password)) {
            if(!$user->is_active) {
                return back()->withErrors(['username' => 'Akun tidak aktif']);
            }

            Auth::login($user);
            return redirect()->route('home');
        }

        return back()->withErrors(['username' => 'Username atau password salah']);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();

        return redirect()->route('login');
    }
}
