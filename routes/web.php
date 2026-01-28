<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ResourceController;
use App\Models\Category;
use App\Models\Resource;



Route::view('/', 'welcome')->name('welcome');
Route::get('/catalogue', function (Request $request) {
    $categories = Category::all();
    $query = Resource::with('category');

    if ($request->filled('search')) {
        $term = $request->search;
        $query->where(function($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%')
              ->orWhere('specifications', 'like', '%' . $term . '%')
              ->orWhereHas('category', function($catQ) use ($term) {
                  $catQ->where('name', 'like', '%' . $term . '%');
              });
        });
    }
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    return view('catalogue', [
        'resources' => $query->get(),
        'categories' => $categories 
    ]);

})->name('catalogue');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::view('/usage-rules', 'rules.usage-rules')->name('usage-rules');
Route::view('/privacy-policy', 'rules.privacy-policy')->name('privacy-policy');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('admin.dashboard');
    Route::get('/admin/reservations', [ReservationController::class, 'adminReservations'])->name('admin.reservations');
    Route::post('/reserve', [ReservationController::class, 'store'])->name('reservation.store');
    Route::patch('/resource/{id}/toggle', [ResourceController::class, 'toggleMaintenance'])->name('resource.toggle');
    Route::delete('/resource/{id}', [ResourceController::class, 'destroy'])->name('resource.destroy');
    Route::get('/resource/{id}', [ResourceController::class, 'show'])->name('resource.show');
    Route::delete('/reservation/{id}', [App\Http\Controllers\ReservationController::class, 'destroy'])->name('reservation.destroy');
    Route::get('/my-reservations', [App\Http\Controllers\ReservationController::class, 'myReservations'])->name('reservations.my_list');
    Route::get('/resources/manage', [App\Http\Controllers\ResourceController::class, 'manage'])->name('resources.manage');
    Route::get('/resources/create', [App\Http\Controllers\ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [App\Http\Controllers\ResourceController::class, 'store'])->name('resources.store');
    Route::get('/resources/{id}/edit', [App\Http\Controllers\ResourceController::class, 'edit'])->name('resources.edit');
    Route::put('/resources/{id}', [App\Http\Controllers\ResourceController::class, 'update'])->name('resources.update');
    Route::delete('/resources/{id}', [App\Http\Controllers\ResourceController::class, 'destroy'])->name('resources.destroy');
    Route::patch('/reservation/{id}/approve', [App\Http\Controllers\ReservationController::class, 'approve'])->name('reservation.approve');
    Route::patch('/reservation/{id}/reject', [App\Http\Controllers\ReservationController::class, 'reject'])->name('reservation.reject');
    Route::patch('/resources/{id}/toggle', [App\Http\Controllers\ResourceController::class, 'toggleMaintenance'])->name('resources.toggle');
    Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/approve', [App\Http\Controllers\UserController::class, 'approve'])->name('users.approve');
    Route::patch('/users/{id}/reject', [App\Http\Controllers\UserController::class, 'reject'])->name('users.reject');
    Route::patch('/users/{id}/promote', [App\Http\Controllers\UserController::class, 'promote'])->name('users.promote');
});