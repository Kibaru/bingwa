<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public $with = ['user',];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auth_id', 'user_id', 'power_id',
    ];

    public function power()
    {
        return $this->belongsTo('App\Power');
    }

    public function user()
    {
        return $this->belongsTo('App\user');
    }
}
