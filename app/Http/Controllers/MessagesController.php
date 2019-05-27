<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'message' => 'required'
        ]);

        $message = new Message();
        $message->message = $request->input('message');
        $message->user_id = auth()->id();
        $message->save();

        return back()->with('success', 'Message created.');
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if (auth()->id() != $message->user_id) {
            return back();
        }

        $message->delete();
        return back()->with('success', 'Message deleted.');
    }

    public function favorite(Message $message)
    {
        $user = auth()->user();
        if ($user->hasFavorited($message)) {
            $user->unfavorite($message);
            return back()->with('success', 'Unfavorited message.');
        } else {
            $user->favorite($message);
            return back()->with('success', 'Favorited message.');
        }
    }

    public function reMessage(Request $request, Message $message)
    {
        auth()->user()->reMessages()->attach($message);
        return back()->with('success', 'Re messaged.');
    }

}