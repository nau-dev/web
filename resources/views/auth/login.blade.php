@extends('layouts.auth')
@section('content')

<div class="card card-login card-hidden">
    <div class="content">
        <div class="social-line text-center">

            <div>
                {!! Form::open(array('route' => 'login', 'method' => 'POST')) !!}
                    @include(
                        "form.input",
                        [
                            "type" => "email",
                            "name" => "email",
                            "params" => ["placeholder" => "email", "class" => "form-control input-no-border"],
                        ],
                        ["label" => "Email"]
                    )
                    @include(
                        "form.input",
                        [
                            "type" => "password",
                            "name" => "password",
                            "params" => ["placeholder" => "password", "class" => "form-control input-no-border"],

                        ],
                        ["label" => "Password"]
                    )
                    <input class="btn btn-nau" type="submit" value="Login">
                {!! Form::close() !!}
                <div><a style="color: #bbb;" href="{{ route('password.request') }}">Reset password</a></div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .login-page .card { padding-top: 0; padding-bottom: 6px; }
        .tabs-titles { position: relative; z-index: 2; margin: 0; padding: 0; list-style: none; }
        .tabs-titles:after { content: ""; display: block; clear: both; }
        .tabs-titles li { float: left; }
        .tabs-titles li a { position: relative; display: block; padding: 6px 15px; color: #364150; border: 1px solid #eee; border-bottom: none; border-radius: 6px 6px 0 0;}
        .tabs-titles li.active a { color: #fff; background: #f08301 linear-gradient(to right, #f08301, #f0a810); }
        .tabs-titles li.active a:after { content: ''; position: absolute; left: 0; top: 100%; right: 0; height: 1px; background: #f08301 linear-gradient(to right, #f08301, #f0a810); }
        .tabs-titles li:not(:first-child) { margin-left: -1px; }
        .tabs .tab { display: none; padding: 12px 15px; border: 1px solid #eee; border-radius: 0 0 6px 6px; }
        .tabs .tab.active { display: block; }
        #formOperator { margin-bottom: 6px; }
    </style>
@endpush

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/login.js') }}"></script>
@endpush

@stop
