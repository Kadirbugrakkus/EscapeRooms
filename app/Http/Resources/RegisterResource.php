<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'identity_number' => $this->identity_number,
            'birth_day' => $this->birth_day,
            'phone' => $this->phone,
            'user_token' => $this->user_token,
            'verification_code' => $this->verification_code,
        ];
    }
}
