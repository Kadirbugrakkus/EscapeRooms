<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'room_no' => $this->room_no,
            'status' => $this->status,
            'user_capacity' => $this->user_capacity,
            'category_id' => $this->category->id,
            'category_title' => $this->category->title,
            'category_description' => $this->category->desc,
            'category_amount' => $this->category->amount,
        ];
    }
}
