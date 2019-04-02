@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Settings</h1>
        {!! Form::open(['action' => ['ProfileController@update'], 'method' => 'POST']) !!}
        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', $user->name , ['class' => 'form-control', 'placeholder' => 'Name', 'required' => 'required']) }}
        </div>
        <div class="form-group">
            {{ Form::label('email', 'Email') }}
            {{ Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => 'Email', 'readonly' => 'readonly']) }}
        </div>
        <div class="form-group">
            {{ Form::label('joined', 'Joined') }}
            {{ Form::text('joined', date('F, d, Y', strtotime(explode(' ', $user->created_at)[0])) , ['class' => 'form-control', 'placeholder' => 'Joined', 'readonly' => 'readonly']) }}
        </div>

        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
        <a href="/settings/password" class="btn btn-warning float-right">Change password</a>
        <a href="/" class="btn btn-outline-dark">Cancel</a>

        {{ Form::hidden('_method', 'PUT') }}
        {!! Form::close() !!}
    </div>
@endsection