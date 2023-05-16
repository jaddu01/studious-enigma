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
  <!--  <link href="{{asset('public/css/custom.css')}}" rel="stylesheet"> -->
    <link href="{{asset('public/css/alertify.core.css')}}" rel="stylesheet">


<!--  html css  -->
<link href="{{asset('public/css/slick.css')}}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" media="all" href="{{asset('public/css/webslidemenu.css')}}" />
<link href="{{asset('public/css/styles.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('public/css/responsive.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('public/css/lightslider.css')}}" rel="stylesheet" type="text/css"/>  
<link href="{{asset('public/css/selectize.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('public/css/frontend.css')}}" rel="stylesheet" type="text/css"/>



    @stack('css')
    <style type="text/css">
        .dropdown-menu {height:200px;overflow-y: scroll;}
        .dataTables_processing{height: 200px;background-color: transparent;border:none;}
       /*#preloader{ display:none;} */
    </style>
    <!-- Styles -->
   {{-- <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">--}}
    <script src="{{asset('public/assets/jquery/dist/jquery.min.js')}}"></script>


</head>
<body>
<!-- Header Start -->
<header>
              <?php $user = Auth::user(); ?>

            @section('header')
                @include('partials.header')
                @include('partials.login')
            @show
             @include('flash-message')
            @yield('content')
            @section('footer')
                @include('partials.footer')
            @show
    <script src="{{ asset('public/js/alertify.js') }}"></script>
    <!-- Bootstrap 
    <script src="{{asset('public/assets/bootstrap/dist/js/bootstrap.min.js')}}"> </script>-->

<script src="{{ asset('public/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/selectize.min.js') }}"></script>
<script src="{{ asset('public/js/slick.js') }}"></script>
<script src="{{ asset('public/js/webslidemenu.js') }}"></script>
<script src="{{ asset('public/js/navAccordion.min.js') }}"></script>
<script src="{{ asset('public/js/easyResponsiveTabs.js') }}"></script>
<script src="{{ asset('public/js/customhtml.js') }}"></script>
   
    <!-- <script src="http://{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script> -->
 <!--    <script src="{{ asset('public/js/app.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
   {{-- <script src="{{ asset('js/admin-components.js') }}"></script>--}}
    <!-- Custom Theme Scripts -->
    <script src="{{asset('public/js/custom.js')}}"></script>
    <script src="{{asset('public/js/lightslider.js')}}"></script>
    <script type="text/javascript">
        var root = '{{url("/")}}';
    </script>
    <script type="text/javascript">

      var $loading = $('#preloader').hide();
$(document)
  .ajaxStart(function () {
    $loading.show();
  })
  .ajaxStop(function () {
    $loading.hide();
  });
      $('#preloader').css('display','block');
          //document.getElementById('logout-form').submit();
        $(document).ready(function () {

          $('#preloader').css('display','none');
            $('.dropdown-toggle').dropdown();
             // Binds to the global ajax scope


             var myVar = "{{ Auth::user() }}";
            // alert(data);
           if(myVar===''){
           }else{
             $.ajax({
                url: root+'/get-zone/',
                type: 'GET',
                dataType: 'json',
                error: function() {
                    //   alert('zone_not_selected'); 
                },
                success: function(res) {
            
                    $('#zone_id').val(res);
                //    alert('zone_selected'); 
                }
            });
           }
        });

          $(document).ready(function(){
              $('#searchbox').selectize({
                  valueField: 'url',
                  labelField: 'name',
                  searchField: ['name'],
                  maxOptions: 10,
                  options: [],
                 // create: false,
                  render: {
                      option: function(item, escape) {
                          var image = '';
                          console.log(item);
                          if(item.image){
                              image = item.image.name;
                          }
                          return '<div><img class="search_thumb" src="'+ image +'"> <span class="search_name">' +escape(item.name)+'</span></div>';
                      }
                  },
                  // optgroups: [
                  //     {value: 'product', label: 'Products'},
                  //     {value: 'category', label: 'Categories'}
                  // ],
                  // optgroupField: 'class',
                  // optgroupOrder: ['product','category'],
                  load: function(query, callback) {
                      if (!query.length) return callback();
                      $.ajax({
                          url: root+'/api/search',
                          type: 'GET',
                          dataType: 'json',
                          data: {
                              q: query
                          },
                          error: function() {
                              callback();
                          },
                          success: function(res) {
                              callback(res.data);
                          }
                      });
                  },
                  onChange: function(){
                      window.location = this.items[0];
                  }
              });
          });

        
        // function readNotification(id){
        //     $.ajax({
        //          url: "{!! route('admin.notification.status') !!}",
        //         type: 'PATCH',
        //         // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
        //         data: {
        //         _method: 'PATCH',
        //         id : id,
        //         _token: '{{ csrf_token() }}'
        //         },
        //         success: function( data ) {
        //             console.log(data);
        //             window.table.draw();
        //             new PNotify({
        //                 title: 'Success',
        //                 text: data.message,
        //                 type: 'success',
        //                 styling: 'bootstrap3'
        //             });

        //         },
        //         error: function( data ) {
        //             new PNotify({
        //                 title: 'Error',
        //                 text: 'something is wrong',
        //                 type: "error",
        //                 styling: 'bootstrap3'
        //             });
        //         }
        //     });
        // }

        $('#zone_id').change(function(){
            $.ajax({
                url: root+'/update-zone/'+$(this).val(),
                type: 'GET',
                dataType: 'json',
                error: function() {
                },
                success: function(res) {
                   window.location.reload();
                }
            });

        });

    </script>
    <script type="text/javascript">
    //     $(document).ready(function(){
    //         $(".addtowishlist").on('click', function(evt) {
    //             var link_data = $(this).data('data');
    //             $.ajax({
    //                 type: "POST",
    //                 url: root+'/user/wishlist/store',
    //                 data: ({
    //                        product_id: link_data,
    //                     "_token": "{{ csrf_token() }}"
    //                 }),
    //                 success: function(data) {
    //                     if(data.code == 1)
    //                     {
    //                         $('a[data-data="' + link_data + '"] > span.heart-icon').addClass('wishlist')
    //                     }
    //                     else{
    //                         $('a[data-data="' + link_data + '"] > span.heart-icon').removeClass('wishlist')
    //                     }
    //                 }
    //             });
    //         });
    //         $(".removetowishlist").on('click', function(evt) {
    //             var link_data = $(this).data('data');
    //             $.ajax({
    //                 type: "POST",
    //                 url: root+'/user/wishlist/store',
    //                 data: ({
    //                     product_id: link_data,
    //                     "_token": "{{ csrf_token() }}"
    //                 }),
    //                 success: function(data) {
    //                     window.location.reload();
    //                 }
    //             });
    //         });
    //     });
    </script>

    @stack('scripts')
<style>
    .wishlist{
        color:#f93c3a;
    }
</style>
</body>
</html>
