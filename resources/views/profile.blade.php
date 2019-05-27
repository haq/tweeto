@extends('layouts.app')

@section('content')
    <div class="container d-none d-md-block">
        <div class="float-left">

            <div class="card " style="width: 18rem;">
                <div class="card-body">
                    <div class="float-right">
                        @if(!auth()->guest() && auth()->id() !== $user->id)
                            @if(auth()->user()->isFollowing($user))
                                {!! Form::open(['action' => ['ProfileController@followUser',  $user->id], 'method' => 'POST']) !!}
                                {{ Form::button('<i class="fas fa-user-minus"></i>', ['class' => 'btn btn-outline-dark', 'type' => 'submit']) }}
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['action' => ['ProfileController@followUser',  $user->id], 'method' => 'POST']) !!}
                                {{ Form::button('<i class="fas fa-user-plus"></i>', ['class' => 'btn btn-outline-dark', 'type' => 'submit']) }}
                                {!! Form::close() !!}
                            @endif
                        @endif
                    </div>
                    <img src="{{ $user->image() }}" class="rounded" alt="user icon" width="64" height="64">
                    <div class="d-flex" style="padding-top: 5px;">
                        <h5 class="card-title">{{ $user->name }}</h5>
                        <h5 class="text-muted small" style="padding-top: 5px; padding-left: 5px;">
                            {{ '@' }}{{ $user->cleanedName() }}</h5>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-value">{{ $user->tweets->count() }}</div>
                            <div class="text-uppercase text-muted small">Tweets</div>

                        </div>
                        <div>
                            <div class="text-value">{{ $user->followings()->get()->count() }}</div>
                            <div class="text-uppercase text-muted small">Following</div>
                        </div>
                        <div>
                            <div class="text-value">{{ $user->followers()->get()->count() }}</div>
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

                        @foreach ($tweets as $tweet)
                            <div style="" class="card">
                                <div class="card-header">
                                    <img src="{{ $tweet->user->image() }}"
                                         class="rounded" alt="user icon" width="32" height="32">
                                    <a style="padding-left:10px;text-decoration: none;"
                                       href="/{{ $tweet->user->cleanedName() }}">
                                        {{ $tweet->user->name }}
                                    </a>
                                    <div class="float-right">
                                        <span class="badge badge-secondary">{{ $tweet->favoriters()->get()->count() }}</span>
                                        -
                                        @if($tweet->isFavoritedBy(auth()->user()))
                                            <a class="btn btn-outline-dark"
                                               href="{{ route('tweet.favorite', $tweet->id) }}">
                                                <i class="fas fa-star"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-outline-dark"
                                               href="{{ route('tweet.favorite', $tweet->id) }}">
                                                <i class="far fa-star"></i>
                                            </a>
                                        @endif

                                        @if(auth()->id() == $tweet->user->id)
                                            {!! Form::open(['action' => ['TweetsController@destroy', $tweet->id], 'method' => 'POST', 'class' => 'float-right pl-2']) !!}
                                            {{ Form::hidden('_method', 'DELETE') }}
                                            {{ Form::button('<i class="fas fa-trash"></i>', ['class' => 'btn btn-outline-danger', 'type' => 'submit']) }}
                                            {!! Form::close() !!}
                                        @elseif(!$tweet->remessage)
                                            {!! Form::open(['action' => ['TweetsController@reMessage', $tweet->id], 'method' => 'POST', 'class' => 'float-right pl-2']) !!}
                                            {{ Form::button('<i class="fas fa-retweet"></i>', ['class' => 'btn btn-outline-dark', 'type' => 'submit']) }}
                                            {!! Form::close() !!}
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p style="color:#666;">
                                        @if($tweet->remessage)
                                            <i class="fas fa-retweet"></i>
                                            {{ $tweet->pivot->created_at->diffForHumans() }}
                                        @else
                                            {{ $tweet->created_at->diffForHumans() }}
                                        @endif
                                    </p>
                                    <p class="card-text">{{ $tweet->message }}</p>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr style="width: 40%">
                            @endif
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
