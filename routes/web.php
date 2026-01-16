<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ResourceController;
use App\Models\Category;
use App\Models\Resource;



Route::get('/', function (Request $request) {
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
    return view('welcome', [
        'resources' => $query->get(),
        'categories' => $categories 
    ]);

})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/reserve', [ReservationController::class, 'store'])->name('reservation.store');
    Route::post('/reserve', [ReservationController::class, 'store'])->name('reservation.store');
    Route::patch('/resource/{id}/toggle', [ResourceController::class, 'toggleMaintenance'])->name('resource.toggle');
    Route::delete('/resource/{id}', [ResourceController::class, 'destroy'])->name('resource.destroy');
    Route::get('/resource/{id}', [ResourceController::class, 'show'])->name('resource.show');
    Route::delete('/reservation/{id}', [App\Http\Controllers\ReservationController::class, 'destroy'])->name('reservation.destroy');
    Route::get('/my-reservations', [App\Http\Controllers\ReservationController::class, 'myReservations'])->name('reservations.my_list');
});