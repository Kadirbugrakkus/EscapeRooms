<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Http\Resources\GetReservationResource;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function add_reservation(ReservationRequest $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), (new ReservationRequest())->rules());

        if ($validator->fails()) {
            return Response::withoutData(false, $validator->errors()->first());
        } else {

            if ($user->user_verified_at != null) {

                $roomId = $request->input('room_id');
                $start = $request->input('start_date');
                $end = $request->input('end_date');

                $room = Room::find($roomId);

                if ($room && $room->status == 0) {

                    $capacity = $room->user_capacity;
                    $userIds = $request->input('user_identity',[]);
                    $json = json_encode($userIds);
                    $userCount = count($userIds);

                    if ($userCount > $capacity) {

                        return Response::withoutData(false, 'Exceeded room capacity');
                    } else {

                        $birthdayDiscount = 0;

                        $currentDate = now();
                        $birthday = Carbon::createFromFormat('Y-m-d', $user->birth_day);
                        $birthday->year = $currentDate->year;

                        if ($birthday->format('md') == $currentDate->format('md')) {
                            $totalAmount = $room->category->amount * ++$userCount;
                            $birthdayDiscount = $totalAmount * 0.1;
                            $finalTotalAmount = $totalAmount - $birthdayDiscount;

                            $reservation = Reservation::create([
                                'room_id' => $room->id,
                                'user_id' => $user->id,
                                'reservation_start' => $start,
                                'reservation_end' => $end,
                                'other_user_identity' => $json,
                                'total_amount' => $finalTotalAmount,
                                'birth_day_discount' => $birthdayDiscount,
                            ]);

                            $room->update([
                                'status' => 1
                            ]);
                            $data = new ReservationResource($reservation);
                            $data->room_status = $room->status;

                            return Response::withData(true, 'Reservation created successfully', $data);
                        }

                        $totalAmount = $room->category->amount * ++$userCount;
                        $finalTotalAmount = $totalAmount - $birthdayDiscount;

                        $reservation = Reservation::create([
                            'room_id' => $room->id,
                            'user_id' => $user->id,
                            'reservation_start' => $start,
                            'reservation_end' => $end,
                            'other_user_identity' => $json,
                            'total_amount' => $finalTotalAmount,
                            'birth_day_discount' => $birthdayDiscount,
                        ]);

                        $room->update([
                            'status' => 1
                        ]);
                        $data = new ReservationResource($reservation);
                        $data->room_status = $room->status;

                        return Response::withData(true, 'Reservation created successfully', $data);
                    }
                } else {
                    return Response::withoutData(false, 'Room not available');
                }
            } else {
                return Response::withoutData(false, 'User not verified!');
            }
        }
    }


    public function get_reservation()
    {
        $user = auth()->user();

        if ($user->user_verified_at != null){
            $reservations = Reservation::where('user_id', $user->id)->get();

            $data = GetReservationResource::collection($reservations);
            if ($data->isEmpty()){
                return Response::withoutData(false, 'Data not found!');
            }else{
                return Response::withData(true, 'Data Listed', $data);
            }
        }else{
            return Response::withoutData(false, 'User not verified!');
        }
    }

    public function cancel_reservation($id)
    {
        $user = auth()->user();
        if ($user->user_verified_at != null) {
            $reservation = Reservation::where('user_id', $user->id)->find($id);
            if (!$reservation) {
                return Response::withoutData(false, 'Reservation not found!');
            }else{
                $room = $reservation->room;
                if ($room->status = 0) {
                    return Response::withoutData(false, 'Room status is already updated');
                }else{
                    $reservation->delete();
                    $room->update([
                        'status' => 0
                    ]);
                    return Response::withoutData(true, 'Reservation cancelled successfully');
                }
            }
        } else {
            return Response::withoutData(false, 'User not verified!');
        }
    }

}
