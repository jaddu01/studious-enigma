@extends('admin.layouts.app')

@section('title', 'Add Offer |')

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

                                {!! Form::open(['route' => 'offer.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}

                                {{csrf_field()}}
                                <span class="section">Add offer</span>


                          {{--  <div class="item form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Vendor Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('user_id', $vandors,null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('user_id') }}
                                </div>
                            </div>--}}

                            <div class="item form-group {{ $errors->has('offer_type') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Offer Type <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('offer_type', Helper::$offer_type,null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'offer_type')) !!}
                                    {{ Form::filedError('offer_type') }}
                                </div>
                            </div>

                            <div class="item form-group{{ $errors->has('offer_value') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Offer Value <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::number('offer_value', null, array('placeholder' => 'offer value','min' => '1', 'max' => '100','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('offer_value'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('offer_value') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            {{--<div class="item form-group{{ $errors->has('sold_product') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Number Of Time <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::number('sold_product', null, array('placeholder' => 'sold product','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('sold_product'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('sold_product') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>--}}

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


                        @foreach(config('translatable.locales') as $locale)
                                <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name In {{$locale}}<span class="required">*</span>
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
                            <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image  <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    <input type="file" id="image" name="image" class="form-control col-md-7 col-xs-12">
                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                                    <strong>{{ $errors->first('image') }}</strong>
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
    
    $("#offer_type").change(function (){
        if($("#offer_type option:selected").val() == 'amount'){
            $("input[name=offer_value]").removeAttr('max');
        }else{
            $("input[name=offer_value]").attr('max','100');
            if($("input[name=offer_value]").val() < 1 || $("input[name=offer_value]").val() > 100) {
            alert('Offer range must be in rage of 1 to 100');
            $("input[name=offer_value]").val('');
           } 
        }
    });
    $("input[name=offer_value]").blur(function (){
        if($("#offer_type option:selected").val() == 'percentages'){
            if($(this).val() < 1 || $(this).val() > 100) {
            alert('Offer range must be in rage of 1 to 100');
            $(this).val('');
           } 
        }else{
           
            if($(this).val() < 1 ) {
            alert('Offer value must be more than zero');
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
