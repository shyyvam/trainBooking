<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeatController;

Route::get('/', [SeatController::class, 'index'])->name('seats.index');
Route::post('/book', [SeatController::class, 'book'])->name('seats.book');
