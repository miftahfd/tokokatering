<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AccountService;
use App\Services\RoleService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected AccountService $accountService, protected RoleService $roleService) {
        $this->accountService = $accountService;
        $this->roleService = $roleService;
    }

    public function selectRole(Request $request) {
        $role_id = $request->role_id;

        $this->accountService->updateActiveRole($role_id);

        return ResponseHelper::response200('Berhasil select role');
    }

    public function index(Request $request) {
        $users = $this->accountService->getAccount();

        return view('pages.settings.user-management.index', compact('users'));
    }

    public function create(Request $request) {
        $roles = $this->roleService->getRole();

        return view('pages.settings.user-management.create', compact('roles'));
    }

    public function store(Request $request) {
        $this->accountService->createAccount($request);

        return redirect()->route('user.index')->with('flash', 'success|Success!|Create user');
    }

    public function edit(Request $request, User $user) {
        $roles = $this->roleService->getRole();

        return view('pages.settings.user-management.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user) {
        $this->accountService->updateAccount($user, $request);

        return redirect()->route('user.index')->with('flash', 'success|Success!|Update user');
    }
}
