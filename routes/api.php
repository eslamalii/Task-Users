<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify', [AuthController::class, 'verifyCode']);

// Protected Routes - Require Authentication via Sanctum
Route::middleware('auth:sanctum')->group(function () {
    
    // Tag Routes (CRUD operations)
    Route::apiResource('tags', TagController::class);
    
    // Post Routes (CRUD operations, soft delete, restore)
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    Route::get('/posts/deleted', [PostController::class, 'deleted']);
    Route::post('/posts/{post}/restore', [PostController::class, 'restore']);

    // Stats Route (Get stats)
    Route::get('/stats', [StatsController::class, 'index']);
    
});
