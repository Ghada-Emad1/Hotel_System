<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ClientApproval extends Model
{
    protected $fillable = ['client_id', 'approved_by', 'approved_at'];
    public $timestamps = false;

    // Client associated with this approval
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Receptionist who approved the client
    public function receptionist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

}
