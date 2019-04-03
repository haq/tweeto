<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'search']);
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

        return view('home.home')->with([
            'user' => $user,
            'messages' => $messages,
        ]);
    }

    public function following()
    {
        return view('home.data')->with([
            'title' => 'Following',
            'data' => auth()->user()->following
        ]);
    }

    public function followers()
    {
        return view('home.data')->with([
            'title' => 'Following',
            'data' => auth()->user()->followers
        ]);
    }

    public function show(string $name)
    {
        $user = User::getUserByName($name);

        if (!$user) {
            return abort(404);
        }

        return view('profile')->with([
            'user' => $user,
            'messages' => $user->messages->sortByDesc('created_at'),
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

        // TODO: fix name check

        if (strcmp($name, $user->name) == 0) {
            return back()->with('error', 'New name cannot be the same as your current one.');
        } else if (User::where('name', $name)->exists()) {
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

    public function followUser(User $user)
    {
        if (auth()->user()->followsUser($user->id)) {
            $user->followers()->detach(auth()->id());
            return back()->with('success', 'Unfollowed user.');
        } else {
            $user->followers()->attach(auth()->id());
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
