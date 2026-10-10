<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
// Lindungi route internal menggunakan auth middleware. Pastikan autentikasi dikonfigurasi.
Route::middleware(['auth'])->group(function(){
 Route::get('/',[DashboardController::class,'index'])->name('dashboard');
 Route::resource('products',ProductController::class)->except(['show']);
 Route::get('/stock',[StockController::class,'index'])->name('stock.index');
 Route::post('/stock',[StockController::class,'store'])->name('stock.store');
});
