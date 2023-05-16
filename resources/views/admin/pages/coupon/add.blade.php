@extends('admin.layouts.app')

@section('title', 'Add Coupon |')

@section('sidebar')
    @parent
@endsection
@section('header')
    @parent
@endsection
@section('footer')
    @parent
@endsection


@section('content')

    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                                {!! Form::open(['route' => 'coupon.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}

                                {{csrf_field()}}
                                <span class="section">Add coupon</span>

 
                           


                        @foreach(config('translatable.locales') as $locale)
                                <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::text('name:'.$locale, null, array('placeholder' => 'Name','class' => 'form-control col-md-7 col-xs-12',  'dir'=>($locale=="ar" ? 'rtl':'ltr') )) !!}
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            @endforeach
                       
                            <div class="item form-group {{ $errors->has('code') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Coupon Code<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                   {!!  Form::text('code', null, array('placeholder' => 'code','class' => 'form-control col-md-7 col-xs-12 ','id' =>'code'  )) !!}
                                    @if ($errors->has('code'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('code') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="item form-group {{ $errors->has('coupon_type') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">coupon Type <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('coupon_type', Helper::$offer_type,null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'coupon_type')) !!}
                                    {{ Form::filedError('coupon_type') }}
                                </div>
                            </div>

                            <div class="item form-group{{ $errors->has('coupon_value') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Coupon Value <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::number('coupon_value', null, array('placeholder' => 'coupon value','min' => '1', 'max' => '100','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('coupon_value'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('coupon_value') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('to_time') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From Time <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('from_time', null, array('placeholder' => 'from time','class' => 'form-control col-md-7 col-xs-12 datepicker','id' =>'from_time'  )) !!}
                                    @if ($errors->has('from_time'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('from_time') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="item form-group{{ $errors->has('from_time') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">To Time <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!!  Form::text('to_time', null, array('placeholder' => 'To time','class' => 'form-control col-md-7 col-xs-12 datepicker','id' =>'to_time' )) !!}
                                    @if ($errors->has('to_time'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('to_time') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                              <div class="item form-group{{ $errors->has('from_time') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Max Number of use per user<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!!  Form::number('number_of_use', null, array('placeholder'=>'Number of Use','class' => 'form-control col-md-7 col-xs-12 ','id' =>'number_of_use','min'=>'1','max'=>'100')) !!}
                                    @if ($errors->has('number_of_use'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('number_of_use') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('status') }}
                                </div>
                            </div>

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                       {{-- <button type="submit" class="btn btn-primary">Cancel</button>--}}
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

   {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')

    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
    <script>
       
    var today = new Date();
    $('.datepicker' ).datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        startDate: '-0m'
    });
    
    $("#coupon_type").change(function (){
        if($("#coupon_type option:selected").val() == 'amount'){
            $("input[name=coupon_value]").removeAttr('max');
        }else{
            $("input[name=coupon_value]").attr('max','100');
            if($("input[name=coupon_value]").val() < 1 || $("input[name=coupon_value]").val() > 100) {
            alert('Coupon range must be in rage of 1 to 100');
            $("input[name=coupon_value]").val('');
           } 
        }
    });
    $("input[name=coupon_value]").blur(function (){
        if($("#coupon_type option:selected").val() == 'percentages'){
            if($(this).val() < 1 || $(this).val() > 100) {
            alert('Coupon range must be in rage of 1 to 100');
            $(this).val('');
           } 
        }else{
           
            if($(this).val() < 1 ) {
            alert('Coupon value must be more than zero');
            $(this).val('');
           } 
        }
        
                  
    });
    $("#to_time").change(function (selected){
        var startDate = new Date($("#from_time").val());
        var endDate =  new Date($("#to_time").val());
        if (endDate < startDate){
            alert('To time can not be less than from time');
            $("#to_time").val('');
        }                 
    });
    $("#from_time").change(function (selected){
        var startDate = new Date($("#from_time").val());
        var endDate =  new Date($("#to_time").val());
        if (endDate < startDate){
            alert('To time can not be less than from time');
            $("#to_time").val('');
        }                 
    });
   

    </script>
@endpush
