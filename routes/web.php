<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/manange_staff', [UserController::class, 'transaction_list']);
Route::get('/user', [UserController::class, 'user_transact']);
Route::get('/user/print_queue', [UserController::class, 'print_queue']);