<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\FirebaseAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page publik
Route::get('/', function () {
    $categories = Category::orderBy('name')->get();
    $selected = request('category');

    $itemsQuery = Item::with('category')->latest();
    if ($selected) {
        $itemsQuery->whereHas('category', function ($q) use ($selected) {
            $q->where('slug', $selected)->orWhere('id', $selected);
        });
    }

    $latestItems = $itemsQuery->take(12)->get();

    return view('landing', compact('latestItems', 'categories', 'selected'));
})->name('landing');

// Katalog penuh
Route::get('/katalog', function () {
    $categories = Category::orderBy('name')->get();
    $selected = request('category');
    $query = request('q');

    $itemsQuery = Item::with('category')->latest();
    if ($selected) {
        $itemsQuery->whereHas('category', function ($q) use ($selected) {
            $q->where('slug', $selected)->orWhere('id', $selected);
        });
    }
    if ($query) {
        $itemsQuery->where(function ($q) use ($query) {
            $q->where('name', 'like', '%'.$query.'%')
                ->orWhere('description', 'like', '%'.$query.'%');
        });
    }

    $items = $itemsQuery->paginate(12)->withQueryString();

    return view('catalog', compact('categories', 'selected', 'items', 'query'));
})->name('catalog');

// Route auth dari Breeze (login, register, dll)
require __DIR__ . '/auth.php';

// ROUTE LOGIN WAJIB LOGIN
Route::middleware(['auth'])->group(function () {

    // Dashboard mahasiswa
    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->name('dashboard');

    // Laporan barang hilang (mahasiswa)
    Route::get('/laporan', [ReportController::class, 'userIndex'])->name('reports.index');
    Route::get('/laporan/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/laporan/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/laporan/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::delete('/laporan/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Form klaim barang (mahasiswa)
    Route::get('/items/{item}/klaim', [ClaimController::class, 'create'])->name('claims.create');
    Route::post('/items/{item}/klaim', [ClaimController::class, 'store'])->name('claims.store');
    Route::delete('/claims/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy');
});

// ROUTE ADMIN (hanya role admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Lihat bukti laporan (ADMIN ONLY)
    Route::get(
        '/reports/{report}/bukti',
        [ReportController::class, 'bukti']
    )->name('reports.bukti');


    // Dashboard admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Kelola barang temuan
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Kelola laporan hilang
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::patch('/reports/{report}/status', [ReportController::class, 'updateStatus'])
        ->name('reports.update-status');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy-admin');

    // Kelola klaim
    Route::get('/claims', [ClaimController::class, 'indexAdmin'])->name('claims.index');
    Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show');
    Route::patch('/claims/{claim}/verify', [ClaimController::class, 'verify'])
        ->name('claims.verify');
    Route::delete('/claims/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy-admin');
});

// Fallback logout via GET untuk mencegah 419 saat klik langsung /logout
Route::middleware('auth')->get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('landing');
})->name('logout.get');

// Detail barang untuk publik/user
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.public.show');

Route::post('/auth/google/firebase', [FirebaseAuthController::class, 'login']);
