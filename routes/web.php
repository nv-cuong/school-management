<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'authLogin']);
Route::get('logout', [AuthController::class, 'logout']);

Route::get('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('change-password', [AuthController::class, 'changePassword']);
Route::post('reset/{token}', [AuthController::class, 'reset']);
Route::post('reset-password/{token}', [AuthController::class, 'resetPassword']);

Route::get('admin/admin/index', function () {
    return view('admin.admin.index');
});

Route::group(['middleware' => 'admin'], function () {
    Route::get('admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.admin.dashboard');
    Route::get('admin/admin/index', [AdminController::class, 'index'])->name('admin.admin.index');
    Route::get('admin/admin/create', [AdminController::class, 'create'])->name('admin.admin.create');
    Route::post('admin/admin/store', [AdminController::class, 'store'])->name('admin.admin.store');
    Route::get('admin/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.admin.edit');
    Route::put('admin/admin/update/{id}', [AdminController::class, 'update'])->name('admin.admin.update');
    Route::get('admin/admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.admin.delete');
});

Route::group(['middleware' => 'teacher'], function () {
    Route::get('teacher/dashboard', [DashboardController::class, 'dashboard']);
});

Route::group(['middleware' => 'student'], function () {
    Route::get('student/dashboard', [DashboardController::class, 'dashboard']);
});

Route::group(['middleware' => 'parent'], function () {
    Route::get('parent/dashboard', [DashboardController::class, 'dashboard']);
});