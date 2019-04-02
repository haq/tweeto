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

        if (auth()->id() !== $message->user_id) {
            return back();
        }

        $message->delete();
        return back()->with('success', 'Message deleted.');
    }

    public function favorite(Message $message)
    {
        $id = auth()->id();
        if ($message->userFavorites($id)) {
            $message->favorites()->detach($id);
            return back()->with('success', 'Unfavorited message.');
        } else {
            $message->favorites()->attach($id);
            return back()->with('success', 'Favorited message.');
        }
    }

}