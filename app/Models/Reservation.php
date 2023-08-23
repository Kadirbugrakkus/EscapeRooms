<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable =
        [
            'user_id',
            'room_id',
            'reservation_start',
            'reservation_end',
            'other_user_identity',
            'birth_day_discount',
            'total_amount',
        ];

    protected $casts = [
        'reservation_start' => 'datetime',
        'reservation_end' => 'datetime',
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function room()
    {
        return $this->hasOne(Room::class, 'id','room_id');
    }
}
