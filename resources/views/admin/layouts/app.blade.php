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
    <link href="{{asset('public/css/custom.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/alertify.core.css')}}" rel="stylesheet">

    @stack('css')
    <style type="text/css">
        .dropdown-menu {height:200px;overflow-y: scroll;}
        .dataTables_processing{height: 200px;background-color: transparent;border:none;}
    </style>
    <!-- Styles -->
   {{-- <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">--}}
    <script src="{{asset('public/assets/jquery/dist/jquery.min.js')}}"></script>


</head>
<body class="nav-md">
<input type="hidden" id="ActiveSidebarCurrentSection" value="{{$currentSection??null}}">
<input type="hidden" id="ActiveSidebarCurrentPage" value="{{$currentPage??null}}">
    <div class="container body" id="wrapper1">
        <div class="main_container">
            
            <?php $user_id = \Auth::guard('admin')->user()->id; ?>
            @section('sidebar')
                @include('admin.partials.side_bar')
            @show
            @section('header')
                @include('admin.partials.header')

            @show
            @yield('content')
            
            @section('footer')
                @include('admin.partials.footer')
            @show
        </div>
    </div>

    <script src="{{ asset('public/js/alertify.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('public/assets/bootstrap/dist/js/bootstrap.min.js')}}">
        
    </script>
    <!-- <script src="http://{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script> -->
 <!--    <script src="{{ asset('public/js/app.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>

   {{-- <script src="{{ asset('js/admin-components.js') }}"></script>--}}
    <!-- Custom Theme Scripts -->

    <script src="{{asset('public/js/custom.js')}}"></script>
    <script src="{{asset('public/js/sidebar.js')}}"></script>
    <script>
          function ajxHeader() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

        }
    </script>
    <?php if($user_id != 1){ ?>
    <script type="text/javascript">
        $(document).ready(function(){
            setInterval(
                function() {
                $.ajax({
                    data: {id:{{$user_id}} },
                    method:'post',
                    url: "{!! route('autologout') !!}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function( data ) {
                    console.log(data);
                    if(data.status == 'inactive'){
                        window.location.reload();
                        window.location.href = "{{URL::to('/admin')}}"
                    }
                    
                });
            }, 5000);
            
        });
    </script>
    <?php } ?>
    <script type="text/javascript">
          //document.getElementById('logout-form').submit();
        $(document).ready(function () {
            $('.dropdown-toggle').dropdown();
        });
        
        function readNotification(id){
            $.ajax({
                 url: "{!! route('admin.notification.status') !!}",
                type: 'PATCH',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                data: {
                _method: 'PATCH',
                id : id,
                _token: '{{ csrf_token() }}'
                },
                success: function( data ) {
                    console.log(data);
                    window.table.draw();
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: 'success',
                        styling: 'bootstrap3'
                    });

                },
                error: function( data ) {
                    new PNotify({
                        title: 'Error',
                        text: 'something is wrong',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                }
            });
        }
    </script>
      <script>
        
</script>
    @stack('scripts')

</body>
</html>
