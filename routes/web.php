<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuoteController::class, 'create'])->name('quotes.create');
Route::post('/quotes/vendor-options', [QuoteController::class, 'vendorOptions'])->name('quotes.vendor-options');
Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');

