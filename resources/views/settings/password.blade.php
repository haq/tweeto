@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit password</h1>
        {!! Form::open(['action' => ['ProfileController@updatePassword'], 'method' => 'POST']) !!}
        <div class="form-group">
            {{ Form::label('current-password', 'Current password') }}
            {{ Form::password('current-password', ['class' => 'form-control', 'placeholder' => 'Current password', 'required' => 'required']) }}
            <br>
            {{ Form::label('new-password', 'New password') }}
            {{ Form::password('new-password', ['class' => 'form-control', 'placeholder' => 'New password', 'required' => 'required']) }}
        </div>
        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
        <a href="/settings" class="btn btn-outline-dark">Cancel</a>
        {!! Form::close() !!}
    </div>
@endsection