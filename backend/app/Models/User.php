<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

    protected $hidden = [
        'login_code',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
        ];
    }

    // twilio looks for phone_number attribute but we have phone as attribute name
    public function routeNotificationForTwilio()
    {
        return $this->phone;
    }

    public function driver(): HasOne 
    {
        return $this->hasOne(Driver::class);
    }

    public function trip(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
