<?php

namespace App;

use App\Power;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'password', 'avatar', 'cover',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function following()
    {
        return $this->belongsToMany('App\User', 'follows', 'auth_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany('App\User', 'follows', 'user_id', 'auth_id');
    }

    public function follow(User $user)
    {
        $this->following()->attach($user->id);
        return 1;
    }

    public function unfollow(User $user)
    {
        $this->following()->detach($user->id);
        return 1;
    }

    public function isFollowing(User $user){
        return (bool) $this->following()->get()->where('id', $user->id)->count();
    }

    public function powers()
    {
        return $this->hasMany('App\Power');
    }

    //join
    public function joins()
    {
        return $this->hasMany('App\Join');
    }

    public function join(Power $power)
    {
        $this->joins()->attach($power->id);
        return 1;
    }

    public function leave(Power $power)
    {
        $this->joins()->detach($power->id);
        return 1;
    }

    public function isContestant(Power $power){
        return (bool) $this->joins()->get()->where('id', $power->id)->count();
    }

    // votes
    public function votes()
    {
        return $this->hasMany('App\Vote');
    }
    public function voted()
    {
        return $this->belongsToMany('App\User', 'votes', 'auth_id', 'user_id', 'power_id');
    }

    public function voters()
    {
        return $this->belongsToMany('App\User', 'votes', 'user_id', 'auth_id', 'power_id');
    }

    public function hasVoted(User $user){
        return (bool) $this->voted()->get()->where('id', $user->id)->count();
    }

    // chats
    public function chats()
    {
        return $this->hasMany('App\Chat');
    }
}
