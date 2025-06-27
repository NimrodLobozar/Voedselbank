<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
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
    Route::get('/voedselpakketten', [FoodPackageController::class, 'index'])->name('food_packages.index');
    Route::get('/voedselpakketten/create', [FoodPackageController::class, 'create'])->name('food_packages.create');
    Route::post('/voedselpakketten', [FoodPackageController::class, 'store'])->name('food_packages.store');

});

Route::post('/toggle-maintenance', [MaintenanceController::class, 'toggle'])->name('toggle.maintenance');

require __DIR__ . '/auth.php';
