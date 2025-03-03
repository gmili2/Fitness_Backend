<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserAdminAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
        // Gestion des utilisateurs
        Route::get('/users', [AdminDashboardController::class, 'listUsers'])->name('admin.users');
        Route::get('/users/create', [AdminDashboardController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{id}', [AdminDashboardController::class, 'showUser'])->name('admin.users.show');
        Route::get('/users/{id}/edit', [AdminDashboardController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminDashboardController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('admin.users.delete');
        
        // Gestion des associations client-utilisateur
        Route::get('/users/{id}/assign-clients', [AdminDashboardController::class, 'showAssignClientForm'])->name('admin.users.assign-clients');
        Route::post('/users/{id}/assign-clients', [AdminDashboardController::class, 'assignClients'])->name('admin.users.assign-clients.store');
    });
});