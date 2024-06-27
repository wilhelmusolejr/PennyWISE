<?php

use App\Models\Breakdown;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('index');
}) -> name('home');

Route::get("/login",function () { return view('index');}) -> name('login');
Route::post("/login",[AuthController::class, 'login']) -> name('login');

Route::post('/logout',[AuthController::class, 'logout']) -> name('logout');
Route::post("/register",[AuthController::class, 'register']) -> name("register");

Route::patch('/user_update', [UserController::class, 'update']) -> name("user_update");

Route::get('/dashboard', [TransactionController::class, 'index']) -> name("home_dashboard")->middleware('auth');
Route::post('/dashboard', [TransactionController::class, 'store']) -> name("add_transaction");
Route::delete('/dashboard/{id}', [TransactionController::class, 'destroy']) -> name("del_transaction");
Route::delete('/dashboard', [TransactionController::class, 'destroyMultiple']) -> name("del_transactions");