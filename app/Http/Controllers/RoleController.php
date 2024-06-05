<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(protected RoleService $roleService, protected PermissionService $permissionService) {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    public function index(Request $request) {
        $roles = $this->roleService->getRole();

        return view('pages.settings.role-management.index', compact('roles'));
    }

    public function create(Request $request) {
        $permissions = $this->permissionService->getPermission();

        return view('pages.settings.role-management.create', compact('permissions'));
    }

    public function store(Request $request) {
        $this->roleService->createRole($request);

        return redirect()->route('role.index')->with('flash', 'success|Success!|Create role');
    }

    public function edit(Request $request, Role $role) {
        $permissions = $this->permissionService->getPermission();

        return view('pages.settings.role-management.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role) {
        $this->roleService->updateRole($role, $request);

        return redirect()->route('role.index')->with('flash', 'success|Success!|Update role');
    }
}
