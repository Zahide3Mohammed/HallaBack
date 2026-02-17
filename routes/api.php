<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/personalitytest', [AuthController::class, 'personalitytest']);
Route::post('/check-email', [AuthController::class, 'checkEmail']);