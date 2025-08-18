<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'Login']);


Route::prefix('dashboard')->middleware(['auth:sanctum'])->group(function () {
   
    Route::prefix('task')->controller(TaskController::class)->group(function () {
        Route::get('/', 'index')->middleware('ability:task::index');
        Route::get('/{id}', 'show')->middleware('ability:task::show');
        Route::post('/', 'store')->middleware('ability:task::store');
        Route::put('/{id}', 'update')->middleware('ability:task::update');
        Route::delete('/{id}', 'delete')->middleware('ability:task::delete');
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