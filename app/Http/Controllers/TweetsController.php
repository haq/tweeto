<?php

namespace App\Http\Controllers;

use App\Tweet;
use App\User;
use Illuminate\Http\Request;

class TweetsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tweet' => 'required'
        ]);

        $tweet = new Tweet();
        $tweet->message = $request->tweet;
        $tweet->user_id = auth()->id();
        $tweet->save();

        return back()->with('success', 'Tweet created.');
    }

    public function destroy($id)
    {
        $message = Tweet::findOrFail($id);

        if (auth()->id() != $message->user_id) {
            return back();
        }

        $message->delete();
        return back()->with('success', 'Tweet deleted.');
    }

    public function favorite(Tweet $message)
    {
        $user = auth()->user();
        if ($user->hasFavorited($message)) {
            $user->unfavorite($message);
            return back()->with('success', 'Unfavorited tweet.');
        } else {
            $user->favorite($message);
            return back()->with('success', 'Favorited tweet.');
        }
    }

    public function reMessage(Request $request, Tweet $message)
    {
        auth()->user()->reMessages()->attach($message);
        return back()->with('success', 'Re messaged.');
    }

}