<?php

namespace App\Http\Controllers;

use App\Tweet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'show', 'search'
        ]);
    }

    public function index()
    {
        $user = auth()->user();

        return view('profile')->with([
            'user' => $user,
            'tweets' => $this->getTweets($user),
            'home' => true
        ]);
    }

    public function show(string $user)
    {
        $user = User::getUserByName($user);

        if (!$user) {
            return abort(404);
        }

        return view('profile')->with([
            'user' => $user,
            'tweets' => $this->getTweets($user, false),
            'home' => false
        ]);
    }

    private function getTweets($user, bool $home = true): Collection
    {
        $tweets = array();

        foreach ($user->reTweets as $tweet) {
            $tweet['isReTweet'] = true;
            array_push($tweets, $tweet);
        }

        foreach ($user->tweets as $tweet) {
            array_push($tweets, $tweet);
        }

        if ($home) {
            foreach ($user->followings()->get() as $followedUser) {
                foreach ($followedUser->$tweets as $tweet) {
                    array_push($tweets, $tweet);
                }
            }
        }

        return collect($tweets)->sort(function (Tweet $a, Tweet $b) use ($user) {
            if ($a->remessage && $b->remessage) {
                return $a->pivot->created_at < $b->pivot->created_at;
            } else if ($a->remessage && !$b->remessage) {
                return $a->pivot->created_at < $b->created_at;
            } else if (!$a->remessage && $b->remessage) {
                return $a->created_at < $b->pivot->created_at;
            } else {
                return $a->created_at < $b->created_at;
            }
        });
    }

    public function following()
    {
        return view('data')->with([
            'title' => 'Following',
            'data' => auth()->user()->followings()->get()
        ]);
    }

    public function followers()
    {
        return view('data')->with([
            'title' => 'Followers',
            'data' => auth()->user()->followers()->get()
        ]);
    }

    public function edit()
    {
        return view('settings.settings')->with('user', auth()->user());
    }

    public function editPassword()
    {
        return view('settings.password')->with('user', auth()->user());
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        $user = auth()->user();
        $name = $request->name;

        if (strcmp($name, $user->name) == 0) {
            return back()->with('error', 'New name cannot be the same as your current one.');
        } else if (User::getUserByName($name)) {
            return back()->with('error', 'Name already taken.');
        } else {
            $user->name = $name;
            $user->save();
            return back()->with('success', 'Name updated.');
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6',
        ]);

        $user = auth()->user();
        $current = $request->input('current-password');
        $new = $request->input('new-password');

        if (!(Hash::check($current, $user->password))) {
            return back()->with('error', 'Your current password does not matches with the password you provided.');
        }

        if (strcmp($current, $new) == 0) {
            return back()->with('error', 'New Password cannot be same as your current password.');
        }

        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        return back()->with('success', 'Password updated.');
    }

    public function followUser(Request $request, User $user)
    {
        $authUser = auth()->user();
        if ($authUser->isFollowing($user)) {
            $authUser->unfollow($user);
            return back()->with('success', 'Unfollowed user.');
        } else {
            $authUser->follow($user);
            return back()->with('success', 'Followed user.');
        }
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string'
        ]);

        $user = User::getUserByName($request->search);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        return redirect('/' . $user->cleanedName());
    }

}
