<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['bios','phone', 'city', 'country'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
