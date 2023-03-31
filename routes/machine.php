<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "machine" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:tomoni', 'scope.organization'])->group(function () {
    //
});
