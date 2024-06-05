<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('logs', [LogViewerController::class, 'index'])->name('log-viewer')->middleware('can:log-viewer.read');

    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role.index')->middleware('can:role.read');
        Route::middleware('can:role.create')->group(function () {
            Route::get('/create', [RoleController::class, 'create'])->name('role.create');
            Route::post('/create', [RoleController::class, 'store']);
        });
        Route::middleware('can:role.update')->group(function () {
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
            Route::put('/{role}/edit', [RoleController::class, 'update']);
        });
    });

    Route::prefix('permission')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permission.index')->middleware('can:permission.read');
        Route::middleware('can:permission.create')->group(function () {
            Route::get('/create', [PermissionController::class, 'create'])->name('permission.create');
            Route::post('/create', [PermissionController::class, 'store']);
        });
        Route::middleware('can:permission.update')->group(function () {
            Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
            Route::put('/{permission}/edit', [PermissionController::class, 'update']);
        });
    });

    Route::prefix('user')->group(function () {
        Route::post('/select-role', [UserController::class, 'selectRole'])->name('user.select-role');
        Route::get('/', [UserController::class, 'index'])->name('user.index')->middleware('can:user.read');
        Route::middleware('can:user.create')->group(function () {
            Route::get('/create', [UserController::class, 'create'])->name('user.create');
            Route::post('/create', [UserController::class, 'store']);
        });
        Route::middleware('can:user.update')->group(function () {
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::put('/{user}/edit', [UserController::class, 'update']);
        });
    });
});