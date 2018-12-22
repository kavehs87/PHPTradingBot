<!doctype html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TickerDaemon</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        ul.menu {
            display: block;
            list-style: none;
            margin: 0px;
            padding: 0px;
            text-align: center;
        }

        ul.menu li a {
            -moz-box-shadow:inset 0px 1px 0px 0px #7a8eb9;
            -webkit-box-shadow:inset 0px 1px 0px 0px #7a8eb9;
            box-shadow:inset 0px 1px 0px 0px #7a8eb9;
            background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #637aad), color-stop(1, #5972a7));
            background:-moz-linear-gradient(top, #637aad 5%, #5972a7 100%);
            background:-webkit-linear-gradient(top, #637aad 5%, #5972a7 100%);
            background:-o-linear-gradient(top, #637aad 5%, #5972a7 100%);
            background:-ms-linear-gradient(top, #637aad 5%, #5972a7 100%);
            background:linear-gradient(to bottom, #637aad 5%, #5972a7 100%);
            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#637aad', endColorstr='#5972a7',GradientType=0);
            background-color:#637aad;
            border:1px solid #314179;
            display:inline-block;
            cursor:pointer;
            color:#ffffff;
            font-family:Arial;
            font-size:13px;
            font-weight:bold;
            padding:6px 12px;
            text-decoration:none;
        }

        ul.menu li a:hover, ul.menu li a:active {
            background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #5972a7), color-stop(1, #637aad));
            background:-moz-linear-gradient(top, #5972a7 5%, #637aad 100%);
            background:-webkit-linear-gradient(top, #5972a7 5%, #637aad 100%);
            background:-o-linear-gradient(top, #5972a7 5%, #637aad 100%);
            background:-ms-linear-gradient(top, #5972a7 5%, #637aad 100%);
            background:linear-gradient(to bottom, #5972a7 5%, #637aad 100%);
            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#5972a7', endColorstr='#637aad',GradientType=0);
            background-color:#5972a7;
        }

        ul.menu li a.active {
            background-color: #42cf42 !important;
            background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #42cf42), color-stop(1, #0b7b00));
            background:-moz-linear-gradient(top, #42cf42 5%, #0b7b00 100%);
            background:-webkit-linear-gradient(top, #42cf42 5%, #0b7b00 100%);
            background:-o-linear-gradient(top, #42cf42 5%, #0b7b00 100%);
            background:-ms-linear-gradient(top, #42cf42 5%, #0b7b00 100%);
            background:linear-gradient(to bottom, #42cf42 5%, #0b7b00 100%);
            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#42cf42', endColorstr='#0b7b00',GradientType=0);
            position:relative;
            top:1px;
        }

        ul.menu li {
            display: inline-block;
        }

        div.balance {
            display: block;
            padding: 1px;
        }

        div.balance span {
            display: inline-block;
            background-color: #1d2124;
            color: #f7f7f7;
            padding: 4px;
            min-width: 80px;
            width: 80px;
        }

        div.balance strong {
            background-color: #1d3f54;
            color: #dddddd;
            padding: 4px;
        }
    </style>

    <script src="/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="/js/utils.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
</head>
<body>
<ul class="menu">
    <div class="mt-3"></div>
    <li><a class="{{ Request::is('signals*') ? 'active' : '' }}" href="{{route('signals')}}">Signals</a></li>
    <li><a class="{{ Request::is('positions*') ? 'active' : '' }}" href="{{route('positions')}}">Positions</a></li>
    <li><a class="{{ Request::is('history*') ? 'active' : '' }}" href="{{route('history')}}">History</a></li>
    @if(!empty(\App\Modules::getMenus()))
        @foreach(\App\Modules::getMenus() as $menu)
            <li><a class="{{ Request::is('modules/page/'.$menu['route'].'*') ? 'active' : '' }}"
                   href="{{route($menu['route'])}}">{{$menu['text']}}</a></li>
        @endforeach
    @endif
    <li><a class="{{ Request::is('system*') ? 'active' : '' }}" href="{{route('system')}}">System</a></li>
    <li><a class="{{ Request::is('modules') ? 'active' : '' }}" href="{{route('modules')}}">Modules</a></li>
</ul>
<hr>
<div class="container-fluid">
    <div class="row">
        @yield('body')
    </div>
</div>
</body>
</html>