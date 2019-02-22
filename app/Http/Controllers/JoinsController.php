<?php

namespace App\Http\Controllers;

use App\Power;
use App\Join;
use Illuminate\Http\Request;

class JoinsController extends Controller
{
    public function join($id)
    {
        $superpower = Power::find($id);

        $join = Join::create([
            'user_id' => auth('api')->user()->id,
            'power_id' => $superpower->id,
        ]);

        return Join::find($join->id);
    }

    public function leave($id)
    {
        $superpower = Power::find($id);

        $join = Join::where('user_id', auth('api')->user()->id)
            ->where('power_id', $superpower->id)
            ->first();

            $join->delete();

            return $join->id;
    }

    public function updateEvidence(Request $request, $powerId, $userId)
    {
        $this->validate($request, [
            'experience' => 'required|string|max:1000',
            'image' => 'image64:png,jpeg,jpg,tif',
            'url' => 'url|nullable',
        ]);

        $join = Join::where(function($q) use ($powerId, $userId){
            $q->where('power_id', $powerId);
            $q->where('user_id', $userId);
        })
        ->first();

        // Handle image upload
        $currentImage = $join->image;

        if($request->image != $currentImage){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];

            \Image::make($request->image)->save(public_path('storage/images/evidence/' . $name));

            $request->merge(['image' => $name]);

            $joinImage = public_path('storage/images/evidence/').$currentImage;

            if(file_exists($joinImage)){
                @unlink($joinImage);
            }
        }

        $join->update($request->all());

        return $join;
    }
}
