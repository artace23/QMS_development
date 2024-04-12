<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);

//window and user part
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/user', [UserController::class, 'user_transact']);
Route::post('/next_queue', [UserController::class, 'next']);
Route::post('/call_queue', [UserController::class, 'call']);
Route::get('/user/print_queue', [UserController::class, 'print_queue']);
Route::get('/display', [UserController::class, 'display_index']);
Route::get('/fetch-current-serving', [UserController::class, 'currentServing']);
Route::get('/fetch-ongoing-queues', [UserController::class, 'fetchOngoingQueues']);
Route::get('/fetch-first-queue', [UserController::class, 'fetchFirstQueue']);
Route::get('/fetch-staff-queues', [UserController::class, 'fetchStaffPendingQueue']);

//admin view part
Route::get('/admin', [AdminController::class, 'index']);
Route::get('/staff', [AdminController::class, 'staffTable']);
Route::get('/transaction', [AdminController::class, 'transactionTable']);
Route::get('/window', [AdminController::class, 'windowTable']);
Route::get('/history', [AdminController::class, 'historyTable']);

//admin add part
Route::get('/manage_staff', [AdminController::class, 'registerStaff']);
Route::post('/register', [AdminController::class, 'register']);
Route::post('/addwindow', [AdminController::class, 'addWindow']);
Route::post('/manage_transaction', [AdminController::class, 'addTransaction']);

//admin edit part
Route::post('/edit-data', [AdminController::class, 'editData']);
Route::post('/edit-transaction', [AdminController::class, 'editTransaction']);
Route::post('/edit-window', [AdminController::class, 'editWindow']);

//admin delete part
Route::post('/delete-data', [AdminController::class, 'deleteData']);
Route::post('/delete-transaction', [AdminController::class, 'deleteTransaction']);
Route::post('/delete-window', [AdminController::class, 'deleteWindow']);