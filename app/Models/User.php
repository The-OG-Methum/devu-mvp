<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'github_id',
        'github_username',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Optional relationship if you want
    public function oauthCodes()
    {
        return $this->hasMany(OauthCode::class);
    }

   public function languages()
{
    return $this->belongsToMany(Languages::class);
}

public function interests()
{
    return $this->belongsToMany(Interests::class);
}

    public function preferences()
    {
    return $this->hasOne(Preferences::class);
    }



}
