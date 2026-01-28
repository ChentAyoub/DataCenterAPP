<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\UserController;

Route::get('/', [ResourceController::class, 'index'])->name('catalogue');
Route::get('/catalogue', [ResourceController::class, 'index']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::view('/usage-rules', 'rules.usage-rules')->name('usage-rules');
Route::view('/privacy-policy', 'rules.privacy-policy')->name('privacy-policy');
Route::get('/resources/{id}', [ResourceController::class, 'show'])->name('resources.show');
Route::post('/reservations/{id}/status', [DashboardController::class, 'updateStatus'])
    ->name('reservations.updateStatus')
    ->middleware('auth');

Route::post('/notifications/mark-read', [ResourceController::class, 'markAsRead'])
    ->name('notifications.markRead')
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('admin.dashboard');
    Route::get('/admin/reservations', [ReservationController::class, 'adminReservations'])->name('admin.reservations');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-reservations', [ReservationController::class, 'myReservations'])->name('reservations.my_list');
    Route::post('/reserve', [ReservationController::class, 'store'])->name('reservation.store');
    Route::delete('/reservation/{id}', [ReservationController::class, 'destroy'])->name('reservation.destroy');
    Route::get('/resources/manage', [ResourceController::class, 'manage'])->name('resources.manage');
    Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
    Route::get('/resources/{id}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
    Route::put('/resources/{id}', [ResourceController::class, 'update'])->name('resources.update');
    Route::delete('/resources/{id}', [ResourceController::class, 'destroy'])->name('resource.destroy');
    Route::patch('/resources/{id}/toggle', [ResourceController::class, 'toggleMaintenance'])->name('resource.toggle');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::patch('/users/{id}/reject', [UserController::class, 'reject'])->name('users.reject');
    Route::patch('/users/{id}/promote', [UserController::class, 'promote'])->name('users.promote');
    Route::patch('/reservation/{id}/reject', [App\Http\Controllers\ReservationController::class, 'reject'])->name('reservation.reject');
    Route::patch('/users/{id}/reject', [App\Http\Controllers\UserController::class, 'reject'])->name('users.reject');
});