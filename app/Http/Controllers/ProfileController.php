<?php

namespace App\Http\Controllers;

use App\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            'messages' => $messages,
            'followers' => $user->followers->count(),
            'following' => $user->following->count()
        ]);
    }

    public function show(string $name)
    {
        return view('profile')->with(
            'user', User::where('name', $name)->firstOrFail()
        );
    }

    public function followUser(User $user)
    {
        $user->followers()->attach(auth()->user()->id);
        return back()->with('success', 'Followed user');
    }

    public function unFollowUser(User $user)
    {
        $user->followers()->detach(auth()->user()->id);
        return back()->with('success', 'Unfollowed user');
    }
}
