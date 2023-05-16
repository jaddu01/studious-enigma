@extends('layouts.app')
@push('css')
<style type="text/css">
    .delivery-time-box{ border: 1px solid; }
</style>
@endpush
@section('content')
<section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li>My Account</li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li>Login</li>      
    </ul>
    </div>  
</section>
<section class="section-area">
<div class="container"> 
<div class="col-xs-1 col-md-2  col-sm-2 col-lg-4"></div>
<div class="delivery-time-box col-xs-10 col-md-8  col-sm-8 col-lg-4">
    <div class="flash-message alert-block"> <button class="close" data-dismiss="alert"></button></div>
<div class="date-box-area">
            {!! Form::open(['route' => 'mobile.login','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
            <input type="hidden" class="resend_otp" name="phone_number" value="{{$data['phone_number']}}">
             <input type="hidden" name="otp" value="{{$data['otp']}}"> 

            <div class="col-sm-12">
            <div class="form-group">
            <label class="form-label col-xs-2 col-sm-2 col-md-2 col-lg-2"  for="otp">OTP</label>   
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
            <div class="form-group col-md-7 {{ $errors->has('otp') ? ' has-error' : '' }}">
            {!!  Form::text('otp_confirmation', null, array('class' => 'form-control custom_input','placeholder'=>'Enter OTP','required'=>'required')) !!}
            @if( $errors->has('otp'))
            {{ Form::filedError('otp') }}
            @endif
            </div>
            
            </div>
            </div>
            <div class="col-sm-12 text-center">
            {!!  Form::submit('verify',array('class'=>'common-btn')) !!}
            </div>
            {!! Form::close() !!}
            
            <div class="col-sm-12 text-center" id="resend-otp-wait">
                <span id="resend_otp_wait">Resend OTP in 00:30</span>
            </div>
            <div class="col-sm-12 text-center" id="resend-otp" style="display: none;">
                <span><a href="javascript:void(0);" id="resend_otp">Resend OTP</a></span>
            </div>
            </div>
            </div>
             <div class="col-xs-1 col-md-2  col-sm-2 col-lg-4"></div>
</div>  
</section>

@endsection
@push('scripts')
    <!-- FastClick -->
    <script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>
    <!-- NProgress -->
    <!-- <script src="{{asset('public/assets/nprogress/nprogress.js')}}"></script> -->
    <!-- validator -->
   <script src="{{asset('public/assets/validator/validator.min.js')}}"></script>
    <!-- Custom Theme Scripts -->
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
    <script>
        $('.datepicker' ).datepicker({
            autoclose: true,
            endDate: "-0m", 
            format: 'dd-mm-yyyy',
        });
    </script>
@endpush


@push('scripts')
    <script>
        $(".priceNum").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $(".numbervalid").html("Digits Only").show().fadeOut("slow");
            return false;
        }
     });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#resend_otp').on('click', function(){
                var phone_number = $(".resend_otp").val();
                
                $.ajax({
                    data: {
                        phone_number : phone_number,
                        _method:'POST'
                    },
                    type: "POST",
                    url: "{!! route('resendOTP') !!}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( data ) {
                        var res = JSON.parse(data);  
                         $('div.flash-message').addClass('alert alert-success');
                         $('div.flash-message').html(res.message);
                     // $('#product-'+productId).load(location.href+('#product-'+productId));

                        //window.location.reload();
                    },
                    error: function( data ) {
                    //  alert("Please Login TO ADD TO CART THIS PRODUCT");
                        $('div.flash-message').addClass('alert  alert-danger');
                         $('div.flash-message').html("Error!! please try again.");              
                   }
                });
            })
        });
    </script>
    <script type="text/javascript">
        let timerOn = true;

        function timer(remaining) {
          var m = Math.floor(remaining / 60);
          var s = remaining % 60;
          
          m = m < 10 ? '0' + m : m;
          s = s < 10 ? '0' + s : s;
          document.getElementById('resend_otp_wait').innerHTML = 'Resend OTP in ' +m + ':' + s;
          remaining -= 1;
          
          if(remaining >= 0 && timerOn) {
            setTimeout(function() {
                timer(remaining);
            }, 1000);
            return;
          }

          if(!timerOn) {
            // Do validate stuff here
            return;
          }
          
          // Do timeout stuff here
          $('#resend-otp').show();
          $('#resend-otp-wait').hide();
        }

        timer(30);
    </script>
@endpush

