<?php

use App\Http\Controllers\User\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware(['auth:sanctum'])->group(function () {
   
    Route::prefix('task')->controller(TaskController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
    });
});
