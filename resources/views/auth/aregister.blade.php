@extends('layouts.app')
@section('content')
<section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li>My Account</li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li>Register</li>      
    </ul>
    </div>  
</section>

<section class="section-area">
<div class="container"> 
<div class="delivery-time-box">
<h2>Register</h2>  
<!-- @foreach (['danger', 'warning', 'success', 'info'] as $key)
    @if(Session::has($key))
        <div class="alert alert-{{ $key }} alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ Session::get($key) }}
        </div>
    @endif
@endforeach -->

<div class="date-box-area">
       {!! Form::open(['route' => 'createregister','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
        <div class="col-sm-12">
           <!--  <div class="form-group">
            <div class="col-md-2"></div>
              <label class="form-label col-md-2"  for="phone_code">Phone Number</label>   
              <div class="form-group col-md-1 {{ $errors->has('phone_code') ? ' has-error' : '' }}">
                {!!  Form::select('phone_code', $countryPhoneCode,'91', array('class' => 'form-control custom_input','placeholder'=>'phone code')) !!}
                @if( $errors->has('phone_code'))
                    {{ Form::filedError('phone_code') }}
                @endif
              </div>
              <div class="col-md-1"></div>
               <div class="form-group col-md-6 {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                {!!  Form::text('phone_number', null, array('class' => 'form-control custom_input priceNum','placeholder'=>'Phone Number')) !!}
                @if( $errors->has('phone_number'))
                    {{ Form::filedError('phone_number') }}
                @endif
                 </div>
            </div> -->

            <div class="col-sm-12">
               <div class="form-group">
                <label class="form-label col-md-3" for="name" align="right" style="margin: 10px;">Phone Number</label>   
                <div class="form-group col-md-1 "> 
                   {!!  Form::text('phone_code', '91', array('class' => 'form-control custom_input','placeholder'=>'Phone Code','readonly'=>'readonly')) !!}
                  <!-- {!!  Form::select('phone_code', $countryPhoneCode,'91', array('class' => 'form-control custom_input','placeholder'=>'phone code')) !!}-->
               </div>
                 <div class="form-group col-md-5 ">
                    <input  placeholder="Phone Number" name="phone_number" type="text" class="form-control custom_input priceNum">
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-2">{!!  Form::submit('Next',array('class'=>'common-btn')) !!}</div>
                </div>
            </div>
        </div>
<div class="col-sm-12 text-center">

</div>

{!! Form::close() !!} 
</div>
    
    
    
     </div>
</div>  
</section>
<script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
    <!-- /page content -->
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
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57 )) {
            //display error message
            $(".has_error").html("Digits Only").show().fadeOut("slow");
            return false;
        }
        var number=$(this).val();
        var compare=/[0-9]{10}/;
        if(number.match(compare)){
          return false;
        }
     });
    </script>
@endpush

