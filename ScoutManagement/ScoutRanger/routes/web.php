<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatController;

// Redirect root URL to login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration & Phone Check Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/check-phone', [RegisterController::class, 'checkPhone'])->name('phone.check');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Main Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // System Chat Routes
    Route::get('/chat/messages', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

    // Admin Panel: Leader Request Approvals
    Route::post('/admin/leader/{id}/approve', [DashboardController::class, 'approveLeader'])->name('admin.leader.approve');
    Route::post('/admin/leader/{id}/reject', [DashboardController::class, 'rejectLeader'])->name('admin.leader.reject');

    // Admin Panel: User CRUD Operations
    Route::post('/admin/users', [DashboardController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [DashboardController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [DashboardController::class, 'destroyUser'])->name('admin.users.destroy');
});