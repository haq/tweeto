<?php

namespace App\Http\Controllers;

use App\Message;
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

        return back()->with('success', 'Message created');
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if (auth()->id() !== $message->user_id) {
            return back();
        }

        $message->delete();
        return back()->with('success', 'Message deleted');
    }
}