<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('dashboard')->group(function () {
    Route::post('login', [AuthController::class, 'Login']);

    Route::prefix('task')->controller(TaskController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
    });
});

Route::prefix('dashboard')->middleware(['auth:sanctum'])->group(function () {

    Route::prefix('task')->controller(TaskController::class)->group(function () {
        Route::post('/', 'store')->middleware('ability:task::store');
        Route::put('/{id}', 'update')->middleware(['ability:task::update', 'task.owner']);
        Route::delete('/{id}', 'delete')->middleware(['ability:task::delete', 'task.owner']);
    });
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->middleware('ability:user::index');
        Route::get('/{id}', 'show')->middleware('ability:user::show');
        Route::post('/', 'store')->middleware('ability:user::store');
        Route::put('/{id}', 'update')->middleware('ability:user::update');
        Route::delete('/{id}', 'delete')->middleware('ability:user::delete');
    });

    Route::post('logout', [AuthController::class, 'Logout']);
});
