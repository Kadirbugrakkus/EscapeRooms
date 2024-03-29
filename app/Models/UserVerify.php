<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVerify extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'code'];
    protected $table = 'user_verify_codes';
}
