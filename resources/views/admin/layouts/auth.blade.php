<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('setting.name') }}| </title>

    <!-- Bootstrap -->
    <link href="{{asset('public/assets/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('public/assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('public/css/custom.css')}}" rel="stylesheet">


</head>
<body style="background:#F7F7F7;">
<div class="">
    <a class="hiddenanchor" id="toregister"></a>
    <a class="hiddenanchor" id="tologin"></a>
    <div id="wrapper">
        @yield('content')
    </div>
</div>

    <script src="{{ asset('public/js/alertify.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('public/assets/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    {{--<script src="{{ asset('js/app.js') }}"></script>--}}

    @stack('scripts')

</body>
</html>