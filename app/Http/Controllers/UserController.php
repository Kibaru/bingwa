<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use App\Profile;
use App\Power;
use App\User;
use App\Join;
use App\Vote;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // get all users
    public function getAllUsers()
    {
        $users = User::all();

        return $users;
    }

    // find a user by query
    public function findUser(Request $request)
    {
        return $q = $request->query;
        $users = DB::table('users')
            ->where('username', 'like', '%'. $q .'%')
            ->get();

        return $users;
    }

    // find a user by id
    public function getUser($id)
    {
        $user = User::where('id', $id)->first();
        $profile = Profile::where('user_id', $id)->first();
        $following = $user->following()->get();
        $followers = $user->followers()->get();

        return response()->json([
            'user' => $user,
            'profile' => $profile,
            'followings' => $following,
            'followers' => $followers,
        ], 200);
    }

    public function updatePrimary(Request $request){

        $user = auth('api')->user();

        $request->validate([
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'username' => 'required|string|max:50|unique:users,username,'.$user->id,
            'email' => 'required|email|max:50|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Updated successfully.',
        ], 200);
    }

    public function updateSecondary(Request $request)
    {
        $this->validate($request, [
            'bios' => 'required|string|max:1000',
            'phone' => 'string|min:10|nullable',
            'city' => 'string|max:50|nullable',
            'country' => 'required|string',
        ]);

        auth('api')->user()->profile()->update([
            'bios' => $request->bios,
            'phone' => $request->phone,
            'city' => $request->city,
            'country' => $request->country,
        ]);

        return response()->json([
            'message' => 'Updated successfully.',
        ], 200);
    }

    // update profile image
    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image64:jpeg,jpg,png,gif'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors'=>$validator->errors(),
                'status' => 422
            ], 422);
        }

        $user = auth('api')->user();

        // Handle avatar file upload
        $currentAvatar = $user->avatar;
        if($request->avatar != $currentAvatar){
            $name = time().'.' . explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];

            \Image::make($request->avatar)->save(public_path('storage/images/profile/' . $name));

            $request->merge(['avatar' => $name]);

            $userAvatar = public_path('storage/images/profile/').$currentAvatar;
            if(file_exists($userAvatar)){
                @unlink($userAvatar);
            }
        }

        $user->update($request->all());
        return ['message' => 'Avatar updated.'];
    }

    // update cover image
    public function updateCover(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cover' => 'required|image64:jpeg,jpg,png,gif'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors'=>$validator->errors(),
                'status' => 422
            ], 422);
        }

        $user = auth('api')->user();

        // Handle cover file upload
        $currentCover = $user->cover;
        if($request->cover != $currentCover){
            $name = time().'.' . explode('/', explode(':', substr($request->cover, 0, strpos($request->cover, ';')))[1])[1];

            \Image::make($request->cover)->save(public_path('storage/images/covers/' . $name));

            $request->merge(['cover' => $name]);

            $userCover = public_path('storage/images/covers/').$currentCover;
            if(file_exists($userCover)){
                @unlink($userCover);
            }
        }

        $user->update($request->all());
        return ['message' => 'Cover updated.'];
    }


    public function created($id)
    {
        $powers = Power::latest()->where('user_id', $id)->get();
        $votes = Vote::all();

        return response()->json([
            'powers' => $powers,
            'votes' => $votes,
        ], 200);
    }

    // fetch user's claimed powers
    public function claimed($id)
    {
        // return $id;
        $joins = Join::where('user_id', $id)->get();

        $powers = [];
        foreach ($joins as $join):
            $power = Power::latest()->where('id', $join->power_id)->first();
            array_push($powers, $power);
        endforeach;

        $votes = Vote::all();

        return response()->json([
            'powers' => $powers,
            'votes' => $votes,
        ], 200);
    }
}