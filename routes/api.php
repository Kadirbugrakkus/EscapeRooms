<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\RoomController;
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
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);


Route::get('escape-rooms', [RoomController::class, 'rooms']);
Route::get('escape-rooms/{id}', [RoomController::class, 'room']);
Route::get('escape-rooms/{id}/time-slots', [RoomController::class, 'getReservationInfo']);


Route::middleware('auth:sanctum')->prefix('escape-rooms')->group(function () {
    Route::post('verify', [LoginController::class, 'verifyCode']);
    Route::post('bookings', [ReservationController::class, 'add_reservation']);
    Route::get('bookings/reservation', [ReservationController::class, 'get_reservation']);
    Route::delete('bookings/reservation/cancel/{id}', [ReservationController::class, 'cancel_reservation']);
});
