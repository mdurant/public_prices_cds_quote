<?php

use App\Http\Controllers\QuotationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('quotations', QuotationController::class);
Route::post('quotations/restore/{id}', [QuotationController::class, 'restore'])->name('quotations.restore');
Route::get('products/search', [QuotationController::class, 'searchProducts'])->name('products.search');

Route::get('/', function () {
    return redirect()->route('dashboard');
});