<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::middleware(['api'])->group(function () {
   
    Route::resource('/users', UserController::class);
    Route::post('/register', [UserController::class, 'store'])->name('user.store');
    Route::post('/login', [UserController::class, 'login'])->name('user.login');
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/current_active_user', [UserController::class, 'current_active_user'])->name('user.current_active_user');
        Route::resource('/categories', CategoryController::class);
        Route::resource('/tasks', TaskController::class);
         Route::get('/task/fliters', [TaskController::class, 'fliters'])->name('user.fliters');
         Route::get('/task/search', [TaskController::class, 'search'])->name('user.search');
        
    });

    



});


