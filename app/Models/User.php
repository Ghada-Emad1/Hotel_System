<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cog\Contracts\Ban\Bannable as BannableContract;
class User extends Authenticatable implements BannableContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles,Bannable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'is_approved',
        'password',
        'role',
        'national_id',
        'avatar_image',
        'gender',
        'country',
        'manager_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
        ];
    }
    public function approval():HasOne
    {
        return $this->hasOne(ClientApproval::class, 'client_id');
    }
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    // A receptionist approves many clients
    public function approvedClients(): HasMany
    {
        return $this->hasMany(ClientApproval::class, 'approved_by');
    }
    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }
}
