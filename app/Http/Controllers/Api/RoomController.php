<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RoomController extends Controller
{
    public function rooms()
    {
        $rooms = Room::with('category')->get();
        $data = RoomResource::collection($rooms);

        if ($data->isEmpty()) {
            return Response::withoutData(false, 'Data not found!');
        } else {
            return Response::withData(true, 'Data Listed', $data);
        }
    }

    public function room($id)
    {
        $room = Room::with('category')->find($id);

        if (!$room) {
            return Response::withoutData(false, 'Data not found!');
        } else {
            $data = new RoomResource($room);
            return Response::withData(true, 'Data Listed', $data);
        }
    }

    public function getReservationInfo($id)
    {
        $room = Room::with('reservations')->find($id);

        if (!$room) {
            return Response::withoutData(false, 'Room not found');
        } else {
            $reservations = $room->reservations;

            if ($reservations == null) {
                return Response::withoutData(false, 'Room is not reserved');
            } else {
                $reservation = $reservations->first();

                $data = [
                    'start_date' => Carbon::parse($reservation->reservation_start)->toDateTimeString(),
                    'end_date' => Carbon::parse($reservation->reservation_end)->toDateTimeString(),
                ];

                return Response::withData(true, 'Reservation info retrieved successfully', $data);
            }
        }
    }


}
