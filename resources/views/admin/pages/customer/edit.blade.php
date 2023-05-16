@extends('admin.layouts.app')

@section('title', 'Edit Customer')

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
@endpush
@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">


                        <div class="x_content">
                            {!! Form::model($user,['route' => array('customer.update',$user->id),'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}

                            {{csrf_field()}}
                             <span class="section">Edit Customer</span>
                            {{method_field('put')}}
                            <div class="row">

                               <!--  <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('user_type') ? ' has-error' : '' }}">
                                        {!!  Form::select('user_type', Helper::$user_type,null, array('class' => 'form-control custom_input','placeholder'=>'User Type')) !!}
                                        @if( $errors->has('user_type'))
                                            {{ Form::filedError('user_type') }}
                                        @endif

                                    </div>
                                </div>
 -->
                                <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        {!!  Form::text('email', null, array('class' => 'form-control custom_input','placeholder'=>'Email')) !!}
                                        @if( $errors->has('email'))
                                            {{ Form::filedError('email') }}
                                        @endif

                                    </div>
                                </div>


                                <div class="col-sm-6">

                                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                        {!!  Form::text('name', null, array('class' => 'form-control custom_input','placeholder'=>'Name')) !!}
                                        @if( $errors->has('name'))
                                            {{ Form::filedError('name') }}
                                        @endif

                                    </div>
                                </div>





                                <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
                                        {!!  Form::select('gender', Helper::$gender,null, array('class' => 'form-control custom_input','placeholder'=>'Gender')) !!}
                                        @if( $errors->has('gender'))
                                            {{ Form::filedError('gender') }}
                                        @endif

                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group {{ $errors->has('phone_code') ? ' has-error' : '' }}">
                                        {!!  Form::select('phone_code', $countryPhoneCode,$phone_code, array('class' => 'form-control custom_input','placeholder'=>'phone code')) !!}
                                        @if( $errors->has('phone_code'))
                                            {{ Form::filedError('phone_code') }}
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group {{ $errors->has('phone_number') ? ' has-error' : '' }}"> <span class="numbervalid"></span>
                                        {!!  Form::text('phone_number', null, array('class' => 'form-control priceNum custom_input','placeholder'=>'Phone Number')) !!}

                                        @if( $errors->has('phone_number'))
                                            {{ Form::filedError('phone_number') }}
                                        @endif

                                    </div>
                                </div>
                                <!-- <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('access_user_id') ? ' has-error' : '' }}">
                                        {!!  Form::select('access_user_id', $accessLevels,null, array('class' => 'form-control custom_input','placeholder'=>'access user')) !!}
                                        @if( $errors->has('access_user_id'))
                                            {{ Form::filedError('access_user_id') }}
                                        @endif

                                    </div>
                                </div> -->
                                <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
                                        {!!  Form::text('dob', null, array('class' => 'form-control datepicker custom_input','placeholder'=>'DOB')) !!}
                                        @if( $errors->has('dob'))
                                            {{ Form::filedError('dob') }}
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                                        {!!  Form::text('address', null, array('class' => 'form-control custom_input','placeholder'=>'Address')) !!}
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
<!-- <script src="{{asset('public/js/custom.js')}}"></script> -->
<link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
    <script>
       $('.datepicker' ).datepicker({
        autoclose: true,
        endDate: "-0m", 
        format: 'dd-mm-yyyy',
    });
    </script>

<!-- validator -->
<script>
    // initialize the validator function
    validator.message.date = 'not a real date';

    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

    $('.multi.required').on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
    });

    $('form').submit(function(e) {
        e.preventDefault();
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
            submit = false;
        }

        if (submit)
            this.submit();

        return false;
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