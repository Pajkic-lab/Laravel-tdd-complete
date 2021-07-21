<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\PasswordForgotController;
use App\Http\Controllers\auth\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::post('lang/change', [LangController::class, 'change'])->name('changeLang');

Route::group(['middleware' => ['guest']], function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::get('password/forgot/reset', [PasswordForgotController::class, 'index']);
Route::get('password/forgot', [PasswordForgotController::class, 'show']);
Route::post('password/forgot', [PasswordForgotController::class, 'forgot']);
Route::get('password/forgot/{jwt}', [PasswordForgotController::class, 'checkJwt']);
Route::post('password/forgot/reset/{jwt}', [PasswordForgotController::class, 'reset']);


Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('home', [HomeController::class, 'index']);
    Route::get('password/reset', [PasswordResetController::class, 'index']);
    Route::post('password/reset', [PasswordResetController::class, 'reset']);
    Route::post('logout', [LoginController::class, 'logout']);

    //users
    Route::get('users', [UsersController::class, 'index']);
    Route::get('users/create', [UsersController::class, 'create']);
    Route::get('users/{user}', [UsersController::class, 'show']);
    Route::get('users/{user}/edit', [UsersController::class, 'edit']);
    Route::post('users', [UsersController::class, 'store']);
    Route::patch('users/{user}', [UsersController::class, 'update']);
    Route::delete('users/{user}', [UsersController::class, 'destroy']);
});

Route::group(['middleware' => ['auth', 'adminMiddleware']], function () {

    //premissions
    Route::get('permissions', [PermissionsController::class, 'index']);

    //roles
    Route::get('roles', [RolesController::class, 'index']);
    Route::get('roles/create', [RolesController::class, 'create']);
    Route::get('roles/{role}', [RolesController::class, 'show']);
    Route::get('roles/{role}/edit', [RolesController::class, 'edit']);
    Route::post('roles', [RolesController::class, 'store']);
    Route::patch('roles/{role}', [RolesController::class, 'update']);
    Route::delete('roles/{role}', [RolesController::class, 'destroy']);
});

Route::get('401', function () {
    return view('errors.401');
});
Route::get('404', function () {
    return view('errors.404');
});
