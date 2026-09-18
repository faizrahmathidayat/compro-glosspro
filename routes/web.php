<?php

use App\Http\Controllers\CmsController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| GlossPro static company profile routes. Every route is resolved through
| PageController - no view is called directly from here.
|
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/layanan', [PageController::class, 'services'])->name('services.index');
Route::get('/layanan/{slug}', [PageController::class, 'serviceDetail'])->name('services.show');
Route::get('/portfolio', [PageController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/{slug}', [PageController::class, 'portfolioShow'])->name('portfolio.show');
Route::get('/artikel', [CmsController::class, 'articles'])->name('articles.index');
Route::get('/artikel/{slug}', [CmsController::class, 'articleShow'])->name('articles.show');
Route::get('/sorotan', [CmsController::class, 'catalog'])->name('sorotan.index');
Route::get('/sorotan/{slug}', [CmsController::class, 'catalogShow'])->name('sorotan.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/cek-garansi', [PageController::class, 'cekGaransi'])->name('cek-garansi');
