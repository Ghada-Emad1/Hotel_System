<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['floor_id', 'number', 'capacity', 'price', 'manager_id'];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    // public function reservations()
    // {
    //     return $this->hasMany(Reservation::class);
    // }
}
