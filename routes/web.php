<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Glosspro static company profile routes. Every route is resolved through
| PageController - no view is called directly from here.
|
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/products', [PageController::class, 'products'])->name('products.index');
Route::get('/products/{slug}', [PageController::class, 'productDetail'])->name('products.show');
Route::get('/dealers', [PageController::class, 'dealers'])->name('dealers');
Route::get('/cek-garansi', [PageController::class, 'cekGaransi'])->name('cek-garansi');
