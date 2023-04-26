@extends('layouts.app')
@push('css')
<style type="text/css">
      hr {clear: both;}
    .pac-container {  
                z-index: 10000;
    }
    label{     margin: 10px; }
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
                  <input type="hidden" value="{{$user->id}}" name="id">
            </div>
              <div class="col-sm-6">
               <div class="form-group">
                <label class="form-label col-md-3"  for="name">Name</label>   
                <div class="form-group col-md-9 {{ $errors->has('name') ? ' has-error' : '' }}">
                    {!!  Form::text('name', null, array('class' => 'form-control custom_input','placeholder'=>' Name')) !!}
                    @if( $errors->has('name'))
                        {{ Form::filedError('name') }}
                    @endif
                </div>
                </div>
            </div>

          <div class="col-sm-6">
             <div class="form-group">
                <label class="form-label col-md-3"  for="email">Email</label>
                 <div class="form-group col-md-9 {{ $errors->has('email') ? ' has-error' : '' }}">
                    {!!  Form::text('email', null, array('class' => 'form-control custom_input','placeholder'=>'Email')) !!}
                    @if( $errors->has('email'))
                        {{ Form::filedError('email') }}
                    @endif

                </div>
                 </div>
            </div>

     <div class="col-sm-6">
                  <div class="form-group">
                   <label class="form-label col-md-3"  for="password">Password</label>   
                    <div class="form-group col-md-9 {{ $errors->has('password') ? ' has-error' : '' }}">
                        {!!  Form::password('password',  array('class' => 'form-control custom_input','placeholder'=>'Password')) !!}
                        @if( $errors->has('password'))
                            {{ Form::filedError('password') }}
                        @endif
                    </div>
                </div>
            </div>

           <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label col-md-3"  for="password_confirmation">Password Confirmation</label>   
                <div class="form-group col-md-9 {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        {!!  Form::password('password_confirmation',  array('class' => 'form-control custom_input','placeholder'=>'Password Confirm')) !!}
                        @if( $errors->has('password_confirmation'))
                            {{ Form::filedError('password_confirmation') }}
                        @endif
                    </div>
                </div>
            </div>

          <div class="col-sm-6">
                 <div class="form-group">
                  <label class="form-label col-md-3"  for="gender">Gender</label>   
                  <div class="form-group col-md-9 {{ $errors->has('gender') ? ' has-error' : '' }}">
                    {!!  Form::select('gender', Helper::$gender,null, array('class' => 'form-control custom_input','placeholder'=>'Gender')) !!}
                    @if( $errors->has('gender'))
                        {{ Form::filedError('gender') }}
                    @endif
                     </div>
                </div>
            </div>
              <div class="col-sm-6">
             <div class="form-group">
                <label class="form-label col-md-3"  for="dob">Date of Birth</label> 
                <div class="form-group col-md-9 {{ $errors->has('dob') ? ' has-error' : '' }}">
                    {!!  Form::text('dob', null, array('class' => 'form-control datepicker custom_input','autocomplete'=>'off','placeholder'=>'DOB')) !!}
                    @if( $errors->has('dob'))
                        {{ Form::filedError('dob') }}
                    @endif

                </div>
            </div>
             </div>
          <div class="col-sm-6">
             <div class="form-group">
                  <label class="form-label col-md-3"  for="phone_code">Phone Number</label>   
                  <div class="form-group col-md-2 {{ $errors->has('phone_code') ? ' has-error' : '' }}">
                    {!!  Form::select('phone_code', $countryPhoneCode,$user->phone_code, array('class' => 'form-control custom_input','placeholder'=>'phone code','readonly'=>'readonly')) !!}
                    @if( $errors->has('phone_code'))
                        {{ Form::filedError('phone_code') }}
                    @endif
                  </div>
                  <div class="col-md-1"></div>
                   <div class="form-group col-md-6 {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                    {!!  Form::text('phone_number', $user->phone_number, array('class' => 'form-control custom_input','placeholder'=>'Phone Number','readonly'=>'readonly')) !!}
                    @if( $errors->has('phone_number'))
                        {{ Form::filedError('phone_number') }}
                    @endif
                     </div>
                </div>
            </div>
         
           


              <div class="col-sm-6">
               <div class="form-group">
                <label class="form-label col-md-3"  for="referral-code">Referral Code</label>   
                <div class="form-group col-md-9 {{ $errors->has('referral_code') ? ' has-error' : '' }}">
                    {!!  Form::text('referral_code', null, array('class' => 'form-control custom_input','placeholder'=>' Enter Referral Code')) !!}
                    @if( $errors->has('referral_code'))
                        {{ Form::filedError('referral_code') }}
                    @endif
                </div>
                </div>
            </div>

                      <div class="col-sm-6 add_address">
              <div class="form-group">
                  <label class="form-label col-md-3"  for="address">Address</label> 
                    <div class="form-group col-md-9">
                    {!!  Form::text('addressshow', null, array('class' => 'form-control custom_input','placeholder'=>'Address' ,'id'=>'addressshow','style'=>'display:none;')) !!}
                    </div>
                    <div class="form-group col-md-3 ">
                    <div class="col-sm-12 actionbutton">
                                  <a  class="btn btn-success " id="addAddress" data-toggle="modal" data-target="#selectOrderAddress">Add Address</a>
                            </div> 
                    </div>
                    <div class="form-group col-md-2"> </div>
                    <div class="form-group col-md-3 {{ $errors->has('address') ? ' has-error' : '' }}">
                    {!!  Form::hidden('address', null, array('class' => 'form-control custom_input','placeholder'=>'Address' ,'id'=>'Address')) !!}
                    @if( $errors->has('address'))
                        {{ Form::filedError('address') }}
                    @endif
                    </div>
                    
            </div>
            </div>
            <div class="col-sm-12 text-center">
                {!!  Form::submit('Sign Up',array('class'=>'btn btn-success','style'=>'    font-size: 22px;')) !!}
            </div>
       
        {!! Form::close() !!} 
        </div>
 </div>
</div>  
</section>

<!--select delivery address model -->
    <div id="selectOrderAddress" class="modal fade" role="dialog" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select delivery address </h4>
                </div>
                <div class="modal-body">
                      {!! Form::open(['method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','id'=>'modify-address-from']) !!}
                    {{csrf_field()}}
                     <input type="hidden" name="shipping_location">
                    <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <!--  <label for="locationTextField">Location</label>
                    <input id="locationTextField" type="text" size="50"> -->
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Address Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('name', null, array('id'=>'name', 'placeholder' => 'Address name','class' => 'form-control col-md-7 col-xs-12','value'=>'' )) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="item form-group{{ $errors->has('address_name') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Address Location<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div id="locationField">
                                {!!  Form::text('address', null, array('id'=>'address_name', 'placeholder' => 'Address name','class' => 'form-control col-md-7 col-xs-12')) !!}
                                
                                @if ($errors->has('address_name'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('address_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div id="us3" style="width: 300px; height: 300px;"></div>
                            <div class="clearfix">&nbsp;</div>
                        </div>
                    </div>
                    <div class="item form-group{{ $errors->has('lat') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Latitute <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('lat',  null, array('id'=>'lat','placeholder' => 'latitute','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('lat'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('lat') }}</strong>
                                 </span>
                            @endif
                        </div>
                    </div>
                    <div class="item form-group{{ $errors->has('lng') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Longitute <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('lng', null, array('id'=>'long','placeholder' => 'lng','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('lng'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('lng') }}</strong>
                                            </span>
                            @endif
                        </div>
                    </div>
                  <!--   <div class="item form-group{{ $errors->has('building') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">   Building<span class="required">*</span></label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!!  Form::text('building', null, array('placeholder' => 'building','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('building'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('building') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="item form-group{{ $errors->has('flat') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Flat <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('flat', null, array('placeholder' => 'flat','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('flat'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('flat') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                      <div class="item form-group{{ $errors->has('floor_number') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Floor_number <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('floor_number', null, array('placeholder' => 'Floor Number','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('floor_number'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('floor_number') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                        <div class="item form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Street
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('street', null, array('placeholder' => 'Street','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('street'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('street') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                     <div class="item form-group{{ $errors->has('zone') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Zone
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('zone', null, array('placeholder' => 'zone','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('zone'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('zone') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div> -->
                    <div class="item form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Description
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::textarea('description', null, array('placeholder' => 'address description','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button style="margin-bottom: 5px;"  onclick="addAddress()" type="button" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
      
    </div>
</section>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
    <!-- /page content -->

<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places&v=weekly"></script>
<script type="text/javascript" src="{{ asset('public/js/locationpicker.jquery.min.js')}}"></script>
<script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>


      <!-- Latest compiled and minified JavaScript -->
    <!-- FastClick -->
    <script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>
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
    <script>
    $(document).ready(function(){

        $("#selectOrderAddress #name").val('');
        if($('#addressshow').val()!==''){
             $('#addAddress').hide();
             $('#addressshow').css('display','block');
        }
    });
       
   
        function addAddress() {
            $.ajax({
                url: "{!! route('delivery_location.store') !!}",
                type: 'POST',
                data:  $('#modify-address-from').serialize()+'&user_id={{$user->id}}&_token={{ csrf_token() }}',
                success: function( data ) {
                    $("#selectOrderAddress").modal('hide');
                    $('#modify-address-from').trigger("reset");
                
             //   alert(data.error);
                  if(data.error) {

                      new PNotify({
                        title: 'Error',
                        text: data.message+"in address",
                        type: "error",
                        styling: 'bootstrap3'
                    });
                    }else{
                         $('#Address').val(data.data.id);
                         $('#addAddress').hide();
                         $('#addressshow').val(data.data.address);
                         $('#addressshow').css('display','block');
                   
                   $('.add_address div div').removeClass('has-error');
                   $('.add_address .help-block').hide();
                   

                      new PNotify({
                        title: 'success',
                        text: data.message,
                        type: "success",
                        styling: 'bootstrap3'
                    });
                    }
                    
                //    window.location.reload();

                },
                error: function( data, status, error ) {

                    new PNotify({
                        title: 'Error',
                        text: data.responseJSON.message,
                        type: "error",
                        styling: 'bootstrap3'
                    });
                }
            });

        }
        $('#us3').locationpicker({
            location: {
                latitude: 26.8634,
                longitude: 75.7776
                /*latitude: $('#lat').val(),
                longitude: $('#lng').val()*/
            },
            radius: 300,
            inputBinding: {
                latitudeInput: $('#lat'),
                longitudeInput: $('#long'),
                //radiusInput: $('#us3-radius'),
                locationNameInput: $('#address_name')
            },
            enableAutocomplete: true,
            onchanged: function (currentLocation, radius, isMarkerDropped) {
                // Uncomment line below to show alert on each Location Changed event
                //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                
            }
        });
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

