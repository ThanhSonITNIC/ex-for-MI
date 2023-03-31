<?php

use App\Http\Controllers\CacheController;
use App\Http\Controllers\MeilisearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:firebase', 'scope.organization'])->group(function () {
    //

    Route::prefix('meilisearch')->group(function () {
        Route::post('import', [MeilisearchController::class, 'import']);
        Route::post('import-by-ids', [MeilisearchController::class, 'importByIds']);
        Route::post('import-missing', [MeilisearchController::class, 'importMissing']);
    });

    Route::prefix('cache')->group(function () {
        Route::post('refresh', [CacheController::class, 'refresh']);
        Route::post('refresh-by-ids', [CacheController::class, 'refreshByIds']);
    });
});
