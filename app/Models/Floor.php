<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
        'manager_id',
    ];
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function rooms()
{
    return $this->hasMany(Room::class);
}

}
