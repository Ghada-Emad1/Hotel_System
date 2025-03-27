<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use App\Models\User;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'client_id',
        'accompany_number',
        'paid_price',
        'status'
    ];

    // Relationship with Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relationship with Client (User)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
