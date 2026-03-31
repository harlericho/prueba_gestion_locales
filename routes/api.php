<?php

use App\Http\Controllers\Api\LocalController;
use Illuminate\Support\Facades\Route;

Route::get('/locales', [LocalController::class, 'index']);
Route::match(['put', 'patch'], '/locales/{id}', [LocalController::class, 'update']);
