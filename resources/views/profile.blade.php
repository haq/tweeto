@extends('layouts.app')

@section('content')
    <div class="container">
        @if(auth()->id() !== $user->id)
            <a href="{{ route('user.follow', $user->id) }}">Follow User</a>
            <a href="{{ route('user.unfollow', $user->id) }}">Unollow User</a>
        @endif
    </div>
@endsection
