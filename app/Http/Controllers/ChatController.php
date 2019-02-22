<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Power;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getMessages($id)
    {
        $messages = Chat::where('power_id', $id)->get();

        return $messages;
    }

    public function postMessages(Request $request, $id)
    {
        // return $request->message;
        $superpower = Power::find($id);

        $chat = Chat::create([
            'user_id' => auth('api')->user()->id,
            'power_id' => $superpower->id,
            'message' => $request->message,
        ]);

        return Chat::find($chat->id);
    }
}
