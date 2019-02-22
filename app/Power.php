<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Power extends Model
{

    public $with = ['user', 'joins', 'votes',];

    protected $fillable = [
        'superpower', 'category', 'avatar', 'cover',
    ];

    // battle creation relation
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function joins()
    {
        return $this->hasMany('App\Join');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    // chats
    public function chats()
    {
        return $this->hasMany('App\Chat');
    }
}
