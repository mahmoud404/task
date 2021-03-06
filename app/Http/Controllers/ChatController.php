<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Http\Resources\MessageResource;
use App\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{


    public function messages()
    {
        $messages = Message::all();
        return MessageResource::collection($messages);
    }

    public function sendMessage(Request $request)
    {
        $user = auth()->user();
        $message = $user->messages()->create([
            'content'=>$request->message,
        ]);
        event(new ChatEvent(MessageResource::make($message)));
        return;
    }
}
