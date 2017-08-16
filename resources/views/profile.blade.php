{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

        <!DOCTYPE html>
<html>
<head>
    <title>NAU</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #38bdff;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }

        .header {
            position: fixed;
            top: 0;
            height: 40px;
            border-bottom: 1px solid #38bdff;
            width: 96%;
            left: 0;
            padding: 2%;
        }

        .header-right {
            float: right;
            margin-right: 20px;
            color: black;
            font-size: 18px;
            font-weight: bold;
        }

        .profile {
            color: black;
            font-size: 25px;
            text-align: left;
            font-weight: bold;
            margin-bottom: 100px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="header">
            <div class="header-right"> Hello {{$name}}! &nbsp; <a href="{{route('logout')}}">Logout</a></div>
        </div>
        <div class="profile">
            Your email: {{$email}}<br>
            Your invite link: <a
                    href="{{route('registerForm', $invite_code)}}">{{route('registerForm', $invite_code)}}</a><br>
            <br>Links:<br>
            <a href="{{route('advert.offers.create')}}">{{route('advert.offers.create')}}</a><br>
            <a href="{{route('advert.offers.index')}}">{{route('advert.offers.index')}}</a><br>
            <a href="{{route('offers.index')}}">{{route('offers.index')}}</a><br>
        </div>
        <div class="title">NAU</div>
    </div>
</div>
</body>
</html>