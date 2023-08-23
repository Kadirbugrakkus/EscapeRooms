<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'room_number' => $this->when($this->room, fn() => $this->room->room_no),
            'room_status' => $this->room_status,
            'start_date' => $this->reservation_start,
            'end_date' => $this->reservation_end,
            'total_amount' => $this->total_amount,
            'birth_day_discount' => $this->birth_day_discount,
        ];
    }
}
