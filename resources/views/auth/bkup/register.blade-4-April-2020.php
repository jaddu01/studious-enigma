@extends('layouts.app')
@section('content')
<section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li>Register</li>      
    </ul>
    </div>  
</section>

<section class="section-area">
<div class="container"> 
<div class="delivery-time-box">
<h2>Register</h2>  

<div class="date-box-area">
    {!! Form::open(['route' => 'register','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                              <div class="col-sm-12">
                                     <input type="hidden" value="6" name="access_user_id">
                                      <input type="hidden" value="user" name="user_type">
                                </div>
                                  <div class="col-sm-12">
                                   <div class="form-group">
                                    <label class="form-label col-md-2"  for="name">Name</label>   
                                    <div class="form-group col-md-10 {{ $errors->has('name') ? ' has-error' : '' }}">
                                        {!!  Form::text('name', null, array('class' => 'form-control custom_input','placeholder'=>' Name')) !!}
                                        @if( $errors->has('name'))
                                            {{ Form::filedError('name') }}
                                        @endif
                                    </div>
                                    </div>
                                </div>
                              <div class="col-sm-12">
                                 <div class="form-group">
                                    <label class="form-label col-md-2"  for="email">Email</label>
                                     <div class="form-group col-md-10 {{ $errors->has('email') ? ' has-error' : '' }}">
                                        {!!  Form::text('email', null, array('class' => 'form-control custom_input','placeholder'=>'Email')) !!}
                                        @if( $errors->has('email'))
                                            {{ Form::filedError('email') }}
                                        @endif

                                    </div>
                                     </div>
                                </div>

                         <div class="col-sm-12">
                                      <div class="form-group">
                                       <label class="form-label col-md-2"  for="password">Password</label>   
                                        <div class="form-group col-md-10 {{ $errors->has('password') ? ' has-error' : '' }}">
                                            {!!  Form::password('password',  array('class' => 'form-control custom_input','placeholder'=>'Password')) !!}
                                            @if( $errors->has('password'))
                                                {{ Form::filedError('password') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                               <div class="col-sm-12">
                                    <div class="form-group">
                                      <label class="form-label col-md-2"  for="password_confirmation">Password Confirmation</label>   
                                    <div class="form-group col-md-10 {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            {!!  Form::password('password_confirmation',  array('class' => 'form-control custom_input','placeholder'=>'Password Confirm')) !!}
                                            @if( $errors->has('password_confirmation'))
                                                {{ Form::filedError('password_confirmation') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                              <div class="col-sm-12">
                                     <div class="form-group">
                                      <label class="form-label col-md-2"  for="gender">gender</label>   
                                      <div class="form-group col-md-10 {{ $errors->has('gender') ? ' has-error' : '' }}">
                                        {!!  Form::select('gender', Helper::$gender,null, array('class' => 'form-control custom_input','placeholder'=>'Gender')) !!}
                                        @if( $errors->has('gender'))
                                            {{ Form::filedError('gender') }}
                                        @endif
                                         </div>
                                    </div>
                                </div>
                              <div class="col-sm-12">
                                 <div class="form-group">
                                      <label class="form-label col-md-2"  for="phone_code">Phone Number</label>   
                                      <div class="form-group col-md-2 {{ $errors->has('phone_code') ? ' has-error' : '' }}">
                                        {!!  Form::select('phone_code', $countryPhoneCode,$user->phone_code, array('class' => 'form-control custom_input','placeholder'=>'phone code')) !!}
                                        @if( $errors->has('phone_code'))
                                            {{ Form::filedError('phone_code') }}
                                        @endif
                                      </div>
                                      <div class="col-md-1"></div>
                                       <div class="form-group col-md-7 {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                                        {!!  Form::text('phone_number', $user->phone_number, array('class' => 'form-control custom_input','placeholder'=>'Phone Number')) !!}
                                        @if( $errors->has('phone_number'))
                                            {{ Form::filedError('phone_number') }}
                                        @endif
                                         </div>
                                    </div>
                                </div>
                               <div class="col-sm-12">
                                 <div class="form-group">
                                    <label class="form-label col-md-2"  for="dob">Date of Birth</label> 
                                    <div class="form-group col-md-10 {{ $errors->has('dob') ? ' has-error' : '' }}">
                                        {!!  Form::text('dob', null, array('class' => 'form-control datepicker custom_input','autocomplete'=>'off','placeholder'=>'DOB')) !!}
                                        @if( $errors->has('dob'))
                                            {{ Form::filedError('dob') }}
                                        @endif

                                    </div>
                                </div>
                                 </div>
                              <div class="col-sm-12">
                                  <div class="form-group">
                                      <label class="form-label col-md-2"  for="address">Address</label> 
                                        <div class="form-group col-md-10 {{ $errors->has('address') ? ' has-error' : '' }}">
                                        {!!  Form::text('address', null, array('class' => 'form-control custom_input','placeholder'=>'Address')) !!}
                                        @if( $errors->has('address'))
                                            {{ Form::filedError('address') }}
                                        @endif

                                      </div>
                                </div>
                                </div>
                                <div class="col-sm-12 text-center">
                                    {!!  Form::submit('Sign Up',array('class'=>'btn btn-success')) !!}
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
    <script src="{{asset('assets/validator/validator.min.js')}}"></script>
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
@endpush

