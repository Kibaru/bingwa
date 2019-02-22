<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function check($id){
        $user = User::where('id', $id)->first();

        if(auth('api')->user()->isFollowing($user))
        {
            return ["status" => 1];
        }
        return ["status" => 0];
    }

    public function follow($id){
        // sending notifications, email, broadcast
        $user = User::where('id', $id)->first();
        if(auth('api')->user()->isFollowing($user))
        {
            return 0;
        }
        return auth('api')->user()->follow($user);
    }

    public function unfollow($id){
        $user = User::where('id', $id)->first();
        if(auth('api')->user()->isFollowing($user))
        {
            return auth('api')->user()->unfollow($user);
        }
        return 0;
    }
}
