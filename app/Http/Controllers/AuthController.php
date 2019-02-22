<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Hash;
use App\User;
use App\Country;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // check username
        $user = User::where('email', $request->username)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Sorry! This email was not recognised.',
                'status' => 422
            ], 422);
        }

        // check password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Wrong password combination.',
                'status' => 422
            ], 422);
        }

        $client = DB::table('oauth_clients')
        ->where('password_client', true)
        ->first();

        Auth::attempt(array('email' => $request->username, 'password' => $request->password));

        if (!$client) {
            return response()->json([
                    'message' => 'User does not exist.',
                    'status' => 500
            ], 500);
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->username,
            'password' => $request->password,
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        // Check if the request was successful
        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Server error. Please try again later.',
                'status' => 422
            ], 422);
        }

        // Get the data from the response
        $data = json_decode($response->getContent());

        // Format the final response in a desirable format
        return response()->json([
            'token' => $data->access_token,
            'user' => $user,
            'status' => 200
        ], 200);
    }

    // get authenticate user
    public function authUser()
    {
        $auth = auth('api')->user();
        $countries = Country::all();

        return response()->json([
            'auth' => $auth,
            'countries' => $countries,
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|unique:users|email|max:50',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->profile()->create();

        $client = DB::table('oauth_clients')
        ->where('password_client', true)
        ->first();

        if (!$client) {
            return response()->json([
                    'message' => 'User does not exist.',
                    'status' => 500
            ], 500);
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request['email'],
            'password' => $request['password'],
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        // Check if the request was successful
        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Server error. Please try again later.',
                'status' => 422
            ], 422);
        }

        // Get the data from the response
        $data = json_decode($response->getContent());

        // Format the final response in a desirable format
        return response()->json([
            'token' => $data->access_token,
            'user' => $user,
            'status' => 200
        ], 200);
    }

    public function logout()
    {
        auth('api')->user()->tokens->each(function($token, $key){
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }
}
