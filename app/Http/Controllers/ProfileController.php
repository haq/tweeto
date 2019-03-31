<?php

namespace App\Http\Controllers;

use App\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    public function index()
    {
        $user = auth()->user();
        $messages = array();

        foreach ($user->messages as $message) {
            array_push($messages, $message);
        }

        foreach ($user->following as $followedUser) {
            foreach ($followedUser->messages as $message) {
                array_push($messages, $message);
            }
        }

        $messages = collect($messages)->sortByDesc('created_at');

        return view('home')->with([
            'user' => $user,
            'messages' => $messages,
        ]);
    }

    public function show(string $name)
    {
        $user = User::where('name', $name)->firstOrFail();

        return view('profile')->with([
            'user' => $user,
            'messages' => $user->messages,
        ]);
    }

    public function followUser(User $user)
    {
        if (auth()->user()->followsUser($user->id)) {
            $user->followers()->detach(auth()->user()->id);
            return back()->with('success', 'Unfollowed user');
        } elseif (!auth()->user()->followsUser($user->id)) {
            $user->followers()->attach(auth()->user()->id);
            return back()->with('success', 'Followed user');
        }
        return back();
    }

}
