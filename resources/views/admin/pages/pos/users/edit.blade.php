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
                            <form action="{{ route('pos.users.update', $user->id) }}" method="post" class="form-horizontal form-label-left validation" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                             <span class="section">Edit User</span>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        <input type="email" class="form-control custom_input" name="email" value="{{ $user->email }}" placeholder="Email" autocomplete="off">
                                        @if( $errors->has('email'))
                                            {{ Form::filedError('email') }}
                                        @endif

                                    </div>
                                </div>


                                <div class="col-sm-6">

                                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                        <input type="text" class="form-control custom_input" name="name" value="{{ $user->name }}" placeholder=" Name" autocomplete="off">
                                        @if( $errors->has('name'))
                                            {{ Form::filedError('name') }}
                                        @endif

                                    </div>
                                </div>



                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                            <input type="password" name="password" id="password" class="form-control custom_input" autocomplete="off" placeholder="Password">
                                            @if( $errors->has('password'))
                                                {{ Form::filedError('password') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">

                                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control custom_input" autocomplete="off" placeholder="Password Confirm">
                                            @if( $errors->has('password_confirmation'))
                                                {{ Form::filedError('password_confirmation') }}
                                            @endif
                                        </div>

                                </div>
                            </div>

                                <div class="col-sm-2">
                                    <div class="form-group {{ $errors->has('phone_code') ? ' has-error' : '' }}">
                                        {{-- {!!  Form::select('phone_code', $countryPhoneCode, $user->phone_code, array('class' => 'form-control custom_input','placeholder'=>'phone code')) !!} --}}
                                        <input type="text"  value="91" placeholder="+91" readonly style="cursor: default" name="phone_code" class="form-control custom_input">

                                        @if( $errors->has('phone_code'))
                                            {{ Form::filedError('phone_code') }}
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group {{ $errors->has('phone_number') ? ' has-error' : '' }}"> <span class="numbervalid"></span>
                                        {!!  Form::text('phone_number', $user->phone_number, array('class' => 'form-control priceNum custom_input','autocomplete'=>'off','placeholder'=>'Phone Number')) !!}
                                       
                                        @if( $errors->has('phone_number'))
                                            {{ Form::filedError('phone_number') }}
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                                        {!!  Form::text('address', $user->address, array('class' => 'form-control custom_input','placeholder'=>'Address','autocomplete'=>'off')) !!}
                                        @if( $errors->has('address'))
                                            {{ Form::filedError('address') }}
                                        @endif

                                    </div>
                                </div>

                                <div class="col-sm-12 text-center">
                                    {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!}
                                </div>
                            </div>

                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {{-- {!! $validator !!} --}}
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
    </script>
@endpush