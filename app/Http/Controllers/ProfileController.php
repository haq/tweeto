<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            return back()->with('error', 'Name cannot be the same');
        } else if (User::where('name', $name)->exists()) {
            return back()->with('error', 'Name already taken');
        } else {
            $user->name = $name;
            $user->save();
            return back()->with('success', 'Name updated');
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
            $user->followers()->detach(auth()->user()->id);
            return back()->with('success', 'Unfollowed user');
        } else {
            $user->followers()->attach(auth()->user()->id);
            return back()->with('success', 'Followed user');
        }
    }

}
