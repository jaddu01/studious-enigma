@extends('layouts.app')
@push('css')
<style type="text/css">
      hr {clear: both;}
    .pac-container {  
                z-index: 10000;
    }
</style>
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
@endpush
@section('content')
<section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li>Join Partner</li>      
    </ul>
    </div>  
</section>

<section class="section-area">
<div class="container"> 
<div class="delivery-time-box">
<h2>Sell with Us </h2>  

<div class="date-box-area">
    {!! Form::open(['route' => 'joinpartner','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}



              <div class="col-sm-12">
               <div class="form-group">
                <label class="form-label col-md-2"  for="name">Name</label>   
                <div class="form-group col-md-10 ">
                    {!!  Form::text('name', null, array('class' => 'form-control custom_input','placeholder'=>' Name')) !!}
                 
                </div>
                </div>
            </div>
        
              <div class="col-sm-12">
               <div class="form-group">
                <label class="form-label col-md-2"  for="phone_code">Shop Name</label>   
                <div class="form-group col-md-10 ">
                    {!!  Form::text('shopname', null, array('class' => 'form-control custom_input','placeholder'=>' Shop Name')) !!}
                 
                </div>
                </div>
            </div>
          <div class="col-sm-12">
             <div class="form-group">
                  <label class="form-label col-md-2"  for="phone_code">Phone Number</label>   
                  <div class="form-group col-md-2 {{ $errors->has('phone_code') ? ' has-error' : '' }}">
                    {!!  Form::select('phone_code', $countryPhoneCode,$phone_code, array('class' => 'form-control custom_input','placeholder'=>'phone code')) !!}
                   
                  </div>
                  <div class="col-md-1"></div>
                   <div class="form-group col-md-7 {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                    {!!  Form::text('phone_number', null, array('class' => 'form-control custom_input','placeholder'=>'Phone Number')) !!}
                    @if( $errors->has('phone_number'))
                        {{ Form::filedError('phone_number') }}
                    @endif
                     </div>
                </div>
            </div>

           <div class="col-sm-12">
                <div class="form-group">
                  <label class="form-label col-md-2"  for="location">Location</label>   
                <div class="form-group col-md-10 {{ $errors->has('address') ? ' has-error' : '' }}">
                        {!!  Form::text('address', null,  array('class' => 'form-control custom_input','placeholder'=>'Location')) !!}
                       
                    </div>
                </div>
            </div>

            <div class="col-sm-12 text-center">
                {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!}
            </div>
       
        {!! Form::close() !!} 
        </div>
 </div>
</div>  
</section>


@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
    <!-- /page content -->
@endpush