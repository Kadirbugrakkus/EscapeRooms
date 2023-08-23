<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'room_number' => $this->when($this->room, fn() => $this->room->room_no),
            'start_date' => \Carbon\Carbon::parse($this->reservation_start)->format('Y-m-d H:i:s'),
            'end_date' => \Carbon\Carbon::parse($this->reservation_end)->format('Y-m-d H:i:s'),
            'total_amount' => $this->total_amount,
            'birth_day_discount' => $this->birth_day_discount,
        ];
    }
}
