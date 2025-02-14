<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Exception\ExceptionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\UserManagement\Role\RoleController;
use App\Http\Controllers\UserManagement\User\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/* Authentication Routes */
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout']);

/* Exception Handling Routes */
Route::get('/not/logged-in', [ExceptionController::class, 'notLoggedIn'])->name('login');
Route::get('/email/not-verified', [ExceptionController::class, 'emailNotVerified'])->name('verification.notice');

/* Email Verification Routes */
Route::controller(EmailVerificationController::class)->group(function () {
    Route::post('email/verification-notification', 'sendVerificationEmail');
    Route::get('verify-email/{id}/{hash}', 'verify')->name('verification.verify');
});

/* Password Reset Routes */
Route::controller(PasswordResetController::class)->group(function () {
    Route::post('/forgot-password', 'sendPasswordResetLink')->name('password.email');
    Route::post('/password/reset', 'resetPassword')->name('password.reset');
});

/* User Management | Role Routes */
Route::controller(RoleController::class)->group(function () {
    Route::prefix('role')->group(function () {
        Route::get('/list', 'index')->middleware('can:View Role');
        Route::get('/add', 'create')->middleware('can:Add Role');
        Route::post('/store', 'store')->middleware('can:Add Role');
        Route::get('/edit/{id}', 'edit')->middleware('can:Edit Role');
        Route::post('/update/{id}', 'update')->middleware('can:Edit Role');
        Route::get('/delete/{id}', 'destroy')->middleware('can:Delete Role');
    });
});

/* User Management | User Routes */
Route::controller(UserController::class)->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/list', 'index')->middleware('can:View User');
        Route::get('/add', 'create')->middleware('can:Add User');
        Route::post('/store', 'store')->middleware('can:Add User');
        Route::get('/edit/{id}', 'edit')->middleware('can:Edit User');
        Route::post('/update/{id}', 'update')->middleware('can:Edit User');
        Route::get('/delete/{id}', 'destroy')->middleware('can:Delete User');
        Route::get('/sub-users', 'subUser')->middleware('can:View User');
    });
});
