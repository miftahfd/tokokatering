<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService) {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request) {
        $permissions = $this->permissionService->getPermission();

        return view('pages.settings.permission-management.index', compact('permissions'));
    }

    public function create(Request $request) {
        return view('pages.settings.permission-management.create');
    }

    public function store(Request $request) {
        $this->permissionService->createPermission($request);

        return redirect()->route('permission.index')->with('flash', 'success|Success!|Create permission');
    }

    public function edit(Request $request, Permission $permission) {
        return view('pages.settings.permission-management.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission) {
        $this->permissionService->updatePermission($permission, $request);

        return redirect()->route('permission.index')->with('flash', 'success|Success!|Update permission');
    }
}
