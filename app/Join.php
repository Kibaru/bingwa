<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Join extends Model
{
    public $with = ['user',];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'power_id', 'experience', 'image', 'url',
    ];

    public function power()
    {
        return $this->belongsTo('App\Power');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
