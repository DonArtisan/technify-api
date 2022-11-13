<?php

use App\Http\Controllers\DashboardController;
use App\Http\Livewire\Brands;
use App\Http\Livewire\Categories;
use App\Http\Livewire\Colors;
use App\Http\Livewire\Products;
use App\Http\Livewire\Sellers;
use App\Http\Livewire\Suppliers;
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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('products', Products::class)->name('products');
    Route::get('sellers', Sellers::class)->name('sellers');
    Route::get('categories', Categories::class)->name('categories');
    Route::get('brands', Brands::class)->name('brands');
    Route::get('colors', Colors::class)->name('colors');
    Route::get('suppliers', Suppliers::class)->name('suppliers');
});

require __DIR__.'/auth.php';
