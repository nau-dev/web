@extends('layouts.master')

@section('title', 'NAU')

@section('content')

    <div class="container">
        <h1>{{  __('msg.welcome', ['name' => $authUser['name']]) }}</h1>

        @auth
            @foreach( $data as $name => $list )
                <div class="row f-25">
                    <div class="col-sm-4 p-5">
                        <p> {{ __('words.' . $name) }} </p>
                    </div>
                    <div class="col-sm-8 p-5">
                        <p> {{ $list }} </p>
                    </div>
                </div>
            @endforeach
        @endauth
    </div>
@stop