@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>
        <div class="row">
            @foreach($data as $user)
                <div class="col-3">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <img src="{{ $user->image() }}" class="rounded" alt="user icon" width="64" height="64">
                            <div class="d-flex" style="padding-top: 5px;">
                                <h5 class="card-title" style="cursor: pointer;"
                                    onclick="window.location='/{{ $user->cleanedName() }}';">{{ $user->name }}</h5>
                                <h5 class="text-muted small" style="padding-top: 5px; padding-left: 5px;">
                                    {{ '@' }}{{ $user->cleanedName() }}</h5>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div>{{ $user->messages->count() }}</div>
                                    <div class="text-uppercase text-muted small">Messages</div>

                                </div>
                                <div>
                                    <div>{{ $user->followings()->get()->count() }}</div>
                                    <div class="text-uppercase text-muted small">Following</div>
                                </div>
                                <div>
                                    <div>{{ $user->followers()->get()->count() }}</div>
                                    <div class="text-uppercase text-muted small">Followers</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection