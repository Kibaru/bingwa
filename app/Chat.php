<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $with = ['user',];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','power_id', 'message'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function power()
    {
        return $this->belongsTo('App\Power');
    }
}
