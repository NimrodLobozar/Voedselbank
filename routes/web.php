<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\FoodStorageController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\FoodPackageController;

Route::get('/', function () {
    $isMaintenanceMode = DB::table('settings')->where('key', 'maintenance_mode')->value('value') ?? false;
    return view('welcome', compact('isMaintenanceMode'));
})->name('/');


Route::get('/dashboard', function () {
    $isMaintenanceMode = DB::table('settings')->where('key', 'maintenance_mode')->value('value') ?? false;
    return view('dashboard', compact('isMaintenanceMode'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::resource('customers', CustomerController::class);
    Route::patch('customers/{customer}/restore', [CustomerController::class, 'restore'])->name('customers.restore');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::patch('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

  
    Route::get('/voedselpakketten', [FoodPackageController::class, 'index'])->name('food_packages.index');
    Route::get('/voedselpakketten/create', [FoodPackageController::class, 'create'])->name('food_packages.create');
    Route::post('/voedselpakketten', [FoodPackageController::class, 'store'])->name('food_packages.store');
    Route::get('/voedselpakketten/{food_package}', [FoodPackageController::class, 'show'])->name('food_packages.show');
    Route::get('/voedselpakketten/{food_package}/edit', [FoodPackageController::class, 'edit'])->name('food_packages.edit');
    Route::patch('/voedselpakketten/{food_package}', [FoodPackageController::class, 'update'])->name('food_packages.update');
    Route::delete('/voedselpakketten/{food_package}', [FoodPackageController::class, 'destroy'])->name('food_packages.destroy');

    // Food storage routes moved inside auth middleware
    Route::resource('foodstorage', FoodStorageController::class);
});

Route::post('/toggle-maintenance', [MaintenanceController::class, 'toggle'])->name('toggle.maintenance');

require __DIR__ . '/auth.php';
