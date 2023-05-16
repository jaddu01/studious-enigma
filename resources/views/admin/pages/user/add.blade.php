@extends('admin.layouts.app')

@section('title', 'Add User')

@section('sidebar')
    @parent
@endsection
@section('header')
    @parent
@endsection
@section('footer')
    @parent
@endsection
@push('css')
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet" />
@endpush

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                            {!! Form::open(['route' => 'user.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}
                             <span class="section">Add User</span>
                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('user_type') ? ' has-error' : '' }}">
                                        {!!  Form::select('user_type', Helper::$user_type,null, array('class' => 'form-control custom_input','placeholder'=>'User Type')) !!}
                                        @if( $errors->has('user_type'))
                                            {{ Form::filedError('user_type') }}
                                        @endif

                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        {!!  Form::text('email', null, array('class' => 'form-control custom_input','autocomplete'=>'off','placeholder'=>'Email')) !!}
                                        @if( $errors->has('email'))
                                            {{ Form::filedError('email') }}
                                        @endif

                                    </div>
                                </div>


                                <div class="col-sm-6">

                                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                        {!!  Form::text('name', null, array('class' => 'form-control custom_input','autocomplete'=>'off','placeholder'=>' Name')) !!}
                                        @if( $errors->has('name'))
                                            {{ Form::filedError('name') }}
                                        @endif

                                    </div>
                                </div>



                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                            {!!  Form::password('password',  array('class' => 'form-control custom_input','autocomplete'=>'off','placeholder'=>'Password')) !!}
                                            @if( $errors->has('password'))
                                                {{ Form::filedError('password') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">

                                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            {!!  Form::password('password_confirmation',  array('class' => 'form-control custom_input','autocomplete'=>'off','placeholder'=>'Password Confirm')) !!}
                                            @if( $errors->has('password_confirmation'))
                                                {{ Form::filedError('password_confirmation') }}
                                            @endif
                                        </div>

                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
                                        {!!  Form::select('gender', Helper::$gender,null, array('class' => 'form-control custom_input','placeholder'=>'Gender')) !!}
                                        @if( $errors->has('gender'))
                                            {{ Form::filedError('gender') }}
                                        @endif

                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group {{ $errors->has('access_user_id') ? ' has-error' : '' }}">
                                        {!!  Form::select('access_user_id', $accessLevels,null, array('class' => 'form-control custom_input','placeholder'=>'access user')) !!}
                                        @if( $errors->has('access_user_id'))
                                            {{ Form::filedError('access_user_id') }}
                                        @endif

                                    </div>
                                </div>


                            </div>

                                <div class="col-sm-2">
                                    <div class="form-group {{ $errors->has('phone_code') ? ' has-error' : '' }}">
                                        {!!  Form::select('phone_code', $countryPhoneCode,null, array('class' => 'form-control custom_input','placeholder'=>'phone code')) !!}
                                        @if( $errors->has('phone_code'))
                                            {{ Form::filedError('phone_code') }}
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group {{ $errors->has('phone_number') ? ' has-error' : '' }}"> <span class="numbervalid"></span>
                                        {!!  Form::text('phone_number', null, array('class' => 'form-control priceNum custom_input','autocomplete'=>'off','placeholder'=>'Phone Number')) !!}
                                       
                                        @if( $errors->has('phone_number'))
                                            {{ Form::filedError('phone_number') }}
                                        @endif

                                    </div>
                                </div>
                                    <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
                                        {!!  Form::text('dob', null, array('class' => 'form-control datepicker custom_input','autocomplete'=>'off','placeholder'=>'DOB')) !!}
                                        @if( $errors->has('dob'))
                                            {{ Form::filedError('dob') }}
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                                        {!!  Form::text('address', null, array('class' => 'form-control custom_input','placeholder'=>'Address','autocomplete'=>'off')) !!}
                                        @if( $errors->has('address'))
                                            {{ Form::filedError('address') }}
                                        @endif

                                    </div>
                                </div>

                                <div class="col-sm-12 text-center">
                                    {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!}
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2({
                placeholder: "Zone",
                allowClear: true
            });
        });
        $(".priceNum").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $(".numbervalid").html("Digits Only").show().fadeOut("slow");
            return false;
        }
     });
       /* $("[name=user_type]").change(function () {
            var val = $(this).val();
            var zones = JSON.parse('<?php echo json_encode($zone); ?>');


            if(val=='vendor'){
                zones = JSON.parse('<?php echo json_encode(collect($zone)->whereNotIn('id',$already_taken_vender_zones)->all()); ?>');

            }
            $("#zone_id").empty();
            for (var i in zones){
               $("#zone_id").append('<option value="'+zones[i]['id']+'">'+zones[i]['name']+'</option>')
            }
        });*/
    </script>
@endpush