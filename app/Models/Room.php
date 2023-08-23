<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_category_id',
        'room_no',
        'status',
        'user_capacity',
    ];

    public function category()
    {
        return $this->hasOne(RoomCategory::class, 'id','room_category_id');
    }

    public function reservations()
    {
        return $this->hasOne(Reservation::class, 'room_id','id');
    }
}

