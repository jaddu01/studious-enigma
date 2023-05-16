<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') {{ config('setting.name') }}  </title>

    <!-- Bootstrap -->
    <link href="{{asset('public/assets/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('public/assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{asset('public/assets/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="{{asset('public/assets/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
    <!-- jVectorMap -->
    <link href="{{asset('public/css/maps/jquery-jvectormap-2.0.3.css')}}" rel="stylesheet"/>

    <!-- Custom Theme Style -->
  
    <link href="{{asset('public/css/alertify.core.css')}}" rel="stylesheet">

    @stack('css')
    <!-- Styles -->
   {{-- <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">--}}
    <script src="{{asset('public/assets/jquery/dist/jquery.min.js')}}"></script>


</head>
<body class="nav-md">
    <div class="container body" id="wrapper1">
        <div class="main_container">
           
            @yield('content')
          
        </div>
    </div>

    <script src="{{ asset('public/js/alertify.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('public/assets/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="http://{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <script src="{{ asset('public/js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>

   {{-- <script src="{{ asset('js/admin-components.js') }}"></script>--}}
    <!-- Custom Theme Scripts -->
    <script src="{{asset('public/js/custom.js')}}"></script>
    @stack('scripts')

</body>
</html>
