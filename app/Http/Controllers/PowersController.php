<?php

namespace App\Http\Controllers;

use App\Power;
use App\User;
use App\Vote;
use App\Join;
use Validator;
use Illuminate\Http\Request;

class PowersController extends Controller
{
    // create a superpower
    public function createSuperpower(Request $request)
    {
        $request->validate([
            'superpower' => 'required|max:191|unique:powers',
        ], [
            'unique'=>'This superpower already exist. Please use a different name.'
        ]);
        // return $request->superpower;

        auth('api')->user()->powers()->create([
            'superpower' => $request->superpower,
        ]);

        return response()->json(['success'=>'Superpower created succssfully.']);

    }

    // fetch all superpowers
    public function fetchSuperpowers()
    {
        $powers = Power::latest()->get();
        $votes = Vote::all();

        return response()->json([
            'powers' => $powers,
            'votes' => $votes,
        ], 200);
    }

    // fetch trending superpowers
    public function trending()
    {
        $powers = Power::latest()->get();
        $trends = [];
        foreach ($powers as $power):
            $joins = Join::where('power_id', $power->id)->orderBy('desc')->count();
            array_push($trends, $joins);
            // take(10)
        endforeach;

        return $trends;
    }

    // fetch a superpower based on id
    public function fetchSuperpower($id)
    {
        $power = Power::where('id', $id)->first();
        $owner = User::where('id', $power->user_id)->first();
        $contestants = $power->joins()->get();
        $votes = Vote::where('power_id', $id)->get();

        return response()->json([
            'power' => $power,
            'owner' => $owner,
            'contestants' => $contestants,
            'votes' => $votes,
        ], 200);
    }

    // update power's avatar
    public function updatePowerAvatar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image64:png,jpeg,jpg,gif'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors'=>$validator->errors(),
                'status' => 422
            ], 422);
        }

        $superpower = Power::where('id', $id)->first();

        // Handle file upload
        $currentAvatar = $superpower->avatar;

        if($request->avatar != $currentAvatar){
            $name = time().'.' . explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];

            \Image::make($request->avatar)->save(public_path('storage/images/superpower/' . $name));

            $request->merge(['avatar' => $name]);

            $powerAvatar = public_path('storage/images/superpower/').$currentAvatar;

            if(file_exists($powerAvatar)){
                @unlink($powerAvatar);
            }
        }

        $superpower->update($request->all());
        return ['message' => 'Avatar updated.'];
    }

    // update power's cover
    public function updatePowerCover(Request $request, $id)
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

        $superpower = Power::where('id', $id)->first();

        // Handle file upload
        $currentCover = $superpower->cover;

        if($request->cover != $currentCover){
            $name = time().'.' . explode('/', explode(':', substr($request->cover, 0, strpos($request->cover, ';')))[1])[1];

            \Image::make($request->cover)->save(public_path('storage/images/covers/' . $name));

            $request->merge(['cover' => $name]);

            $powerCover = public_path('storage/images/superpower/').$currentCover;

            if(file_exists($powerCover)){
                @unlink($powerCover);
            }
        }

        $superpower->update($request->all());
        return ['message' => 'Cover updated.'];
    }

    // update power's info
    public function updatePower(Request $request, $id)
    {
        $superpower = Power::findOrFail($id);

        $this->validate($request, [
            'superpower' => 'required|string|max:191',
            'category' => 'required',
        ]);

        $superpower->update($request->all());
        return ['message' => 'Power info updated.'];
    }

    // delete a superpower
    public function deletePower($id)
    {
        $superpower = Power::findOrFail($id);

        // delete
        $superpower->delete();

        return ['message' => 'Superpower deleted.'];
    }
}
