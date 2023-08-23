<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\RoomController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Oturum gereksinimini devre dışı bırakmadan middleware ile korunan rotalar
Route::middleware('auth:sanctum')->group(function () {
    Route::post('escape-rooms/verify', [LoginController::class, 'verifyCode']);
    Route::post('escape-rooms/bookings', [ReservationController::class, 'add_reservation']);
    Route::get('escape-rooms/bookings/reservation', [ReservationController::class, 'get_reservation']);
    Route::delete('escape-rooms/bookings/reservation/cancel/{id}', [ReservationController::class, 'cancel_reservation']);
});

// Oturum gereksinimini devre dışı bırakarak middleware ile korunan rotalar
Route::middleware('auth:sanctum')->withoutMiddleware([Authenticate::class])->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::get('escape-rooms', [RoomController::class, 'rooms']);
    Route::get('escape-rooms/{id}', [RoomController::class, 'room']);
    Route::get('escape-rooms/{id}/time-slots', [RoomController::class, 'getReservationInfo']);
});

// Oturum gerektirmeyen rotalar
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::get('escape-rooms', [RoomController::class, 'rooms']);
Route::get('escape-rooms/{id}', [RoomController::class, 'room']);
Route::get('escape-rooms/{id}/time-slots', [RoomController::class, 'getReservationInfo']);

