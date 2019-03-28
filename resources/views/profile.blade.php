@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="float-left">

            <div class="card " style="width: 18rem;">
                <div class="card-body">
                    <div class="float-right">
                        @if(!auth()->guest() && auth()->id() !== $user->id)
                            @if(auth()->user()->followsUser($user->id))
                                <a class="btn btn-danger" href="{{ route('user.unfollow', $user->id) }}">
                                    Unfollow
                                </a>
                            @else
                                <a class="btn btn-primary" href="{{ route('user.follow', $user->id) }}">
                                    Follow
                                </a>
                            @endif
                        @endif
                    </div>
                    <img src="{{ $user->image() }}" class="rounded" alt="user icon" width="64" height="64">
                    <div class="d-flex" style="padding-top: 5px;">
                        <h5 class="card-title">{{ $user->name }}</h5>
                        <h5 class="text-muted small" style="padding-top: 5px; padding-left: 5px;">
                            @ {{ $user->cleanedName() }}</h5>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-value">{{ count($messages) }}</div>
                            <div class="text-uppercase text-muted small">Messages</div>

                        </div>
                        <div>
                            <div class="text-value">{{ $user->following->count() }}</div>
                            <div class="text-uppercase text-muted small">Following</div>
                        </div>
                        <div>
                            <div class="text-value">{{ $user->followers->count() }}</div>
                            <div class="text-uppercase text-muted small">Followers</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">

                        @if(count($messages) > 0)
                            @foreach ($messages as $message)
                                <div style="" class="card">
                                    <div class="card-header">
                                        <img src="{{ $message->user->image() }}"
                                             class="rounded" alt="user icon" width="32" height="32">
                                        <a style="padding-left:10px;text-decoration: none;"
                                           href="/{{ $message->user->cleanedName() }}">{{ $message->user->name }}</a>
                                        <div class="float-right">
                                            @if(Auth::user()->id == $message->user->id)
                                                {!! Form::open(['action' => ['MessagesController@destroy', $message->id], 'method' => 'POST', 'class' => 'float-left']) !!}
                                                {{ Form::hidden('_method', 'DELETE') }}
                                                {{ Form::submit('Delete', ['class' => 'btn btn-outline-danger']) }}
                                                {!! Form::close() !!}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p style="color:#666;">
                                            {{ $message->created_at->diffForHumans() }}
                                        </p>
                                        <p class="card-text">{{ $message->message }}</p>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr style="width: 40%">
                                @endif
                            @endforeach

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
