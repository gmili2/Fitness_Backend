<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserAdminAuthController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::prefix('admin')->group(function () {
    Route::get('/login', [UserAdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [UserAdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [UserAdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/register', [UserAdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [UserAdminAuthController::class, 'register'])->name('admin.register.submit');
    Route::middleware('auth.user_admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });
});