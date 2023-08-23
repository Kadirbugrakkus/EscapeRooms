<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'desc',
        'amount',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_category_id', 'id');
    }
}

