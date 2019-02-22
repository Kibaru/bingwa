<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');

//['mid' => 'token.auth'], f($router)
Route::group(['middleware' => 'auth:api'], function(){

    Route::post('/logout', 'AuthController@logout');

    Route::get('/auth', 'AuthController@authUser');

    // user profile
    Route::get('/users', 'UserController@getAllUsers');

    Route::post('/users', 'UserController@findUser');

    Route::get('/users/{id}', 'UserController@getUser');

    Route::put('/users/updatePri', 'UserController@updatePrimary');

    Route::put('/users/updateSec', 'UserController@updateSecondary');

    Route::put('/users/updateAv', 'UserController@updateAvatar');

    Route::put('/users/updateCo', 'UserController@updateCover');

    Route::get('/users/claimed/{id}', 'UserController@claimed');

    Route::get('/users/created/{id}', 'UserController@created');

    // follow/unfollow
    Route::get('/check_relationship_status/{id}', 'FollowController@check');

    Route::get('/follow/{id}', 'FollowController@follow');

    Route::get('/unfollow/{id}', 'FollowController@unfollow');

    // superpowers
    Route::post('/superpowers/create', 'PowersController@createSuperpower');

    Route::get('/superpowers/fetch', 'PowersController@fetchSuperpowers');

    Route::get('/superpowers/trending', 'PowersController@trending');

    Route::get('/superpowers/fetch/{id}', 'PowersController@fetchSuperpower');

    Route::put('/superpowers/updatePowerAvatar/{id}', 'PowersController@updatePowerAvatar');

    Route::put('/superpowers/updatePowerCover/{id}', 'PowersController@updatePowerCover');

    Route::put('/superpowers/updatePower/{id}', 'PowersController@updatePower');

    Route::delete('/superpowers/deletePower/{id}', 'PowersController@deletePower');

    // Join/leave superpower
    Route::get('/superpowers/join/{id}', 'JoinsController@join');

    Route::get('/superpowers/leave/{id}', 'JoinsController@leave');

    Route::put('/superpowers/{powerId}/{userId}', 'JoinsController@updateEvidence');

    // Cast vote
    Route::get('/superpowers/vote/{powerId}/{userId}', 'VotesController@vote');

    // messages
    Route::get('/superpowers/getMessages/{id}', 'ChatController@getMessages');
    Route::post('/superpowers/postMessages/{id}', 'ChatController@postMessages');
});
