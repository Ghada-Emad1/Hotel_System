<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Notifications\ClientApprovedNotification;

class ClientApproval extends Model
{
    protected $fillable = ['client_id', 'approved_by', 'approved_at','is_approved'];
    public $timestamps = false;

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function receptionist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected static function booted()
    {
        static::created(function ($approval) {
            if ($approval->client) {
                $approval->client->notify(new ClientApprovedNotification());
            }
        });
    }
}
