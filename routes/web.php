<?php

use App\Http\Controllers\LocalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LocalController::class, 'index']);
Route::get('/locales', [LocalController::class, 'index']);
