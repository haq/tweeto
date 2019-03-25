@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {!! Form::open(['action' => 'MessagesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        <div class="form-group">
                            {{ Form::label('message', 'Message') }}
                            {{ Form::textarea('message', '', ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Message']) }}
                        </div>
                        {{ Form::submit('Create', ['class'=>'btn btn-primary']) }}
                        {!! Form::close() !!}

                        @if(count($messages) > 0)
                            <hr style="width: 40%">
                            @foreach ($messages as $message)
                                <div style="" class="card">
                                    <div class="card-header">
                                        <img src="{{ $message->user->getImage() }}"
                                             class="img-responsive" alt="user icon" width="32" height="32">
                                        <a style="padding-left:10px;">{{ $message->user->name }}</a>
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
