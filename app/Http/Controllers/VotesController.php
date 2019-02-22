<?php

namespace App\Http\Controllers;

use App\Vote;
use App\User;
use App\Power;
use App\Join;
use Illuminate\Http\Request;

class VotesController extends Controller
{
    public function vote($powerId, $userId)
    {
        // return $userId;
        $superpower = Power::find($powerId);
        $user = User::find($userId);

        $vote = Vote::create([
            'auth_id' => auth('api')->user()->id,
            'power_id' => $superpower->id,
            'user_id' => $user->id,
        ]);

        return Vote::find($vote->id);
    }

    public function fetchUserVotes($powerId, $userId)
    {
        // return $powerId;
        $totalVotes = Vote::where('power_id', $powerId)->get();

        $votes = Vote::where(function($q) use ($powerId, $userId){
            $q->where('power_id', $powerId);
            $q->where('user_id', $userId);
        })
        ->get();
        // array_push($userVotes, $votes);

        // return $userVotes;

        return response()->json([
            'votes' => $votes,
            'totalVotes' => $totalVotes
        ], 200);
    }
}
