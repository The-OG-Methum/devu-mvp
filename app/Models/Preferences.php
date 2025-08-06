<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preferences extends Model
{
    protected $fillable = ['user_id', 'experience_level'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Languages::class, 'language_user_preference', 'preference_id', 'language_id');
    }

    public function interests()
    {
        return $this->belongsToMany(Interests::class, 'interest_user_preference', 'preference_id', 'interest_id');
    }
}
