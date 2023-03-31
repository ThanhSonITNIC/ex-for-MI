<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilesController;

/*
|--------------------------------------------------------------------------
| File Routes
|--------------------------------------------------------------------------
*/

Route::get('{source}/{name}', [FilesController::class, 'download']);
