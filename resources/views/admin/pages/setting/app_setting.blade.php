@extends('admin.layouts.app')

@section('title', 'App Setting')

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


                            {!! Form::close() !!}

                            {!! Form::model($setting,['url' => 'admin/setting/app_setting','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">App Setting</span>
						<h4>Custmor Care:</h4>
                            <div class="item form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Phone Number <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('phone_number', null, array('placeholder' => 'Phone Number','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('phone_number'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('phone_number') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('whatsapp_api_link') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Whatsapp Api Link <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('whatsapp_api_link', null, array('placeholder' => 'Whatsapp Api Link','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('whatsapp_api_link'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('whatsapp_api_link') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            
                            <h4>App Store Links:</h4>
                            <div class="item form-group{{ $errors->has('android_play_store') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Customer Play Store <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('android_play_store', null, array('placeholder' => 'Android Play Store','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('android_play_store'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('android_play_store') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('android_play_store') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Driver Play Store <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('android_play_store_driver', null, array('placeholder' => 'Android Driver Play Store','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('android_play_store_driver'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('android_play_store_driver') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('android_play_store') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Shopper Play Store <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('android_play_store_shopper', null, array('placeholder' => 'Android Shopper Play Store','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('android_play_store_shopper'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('android_play_store_shopper') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('ios_app_store') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Ios App Store <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('ios_app_store', null, array('placeholder' => 'Ios App Store','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('ios_app_store'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('ios_app_store') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <h4>Parameters:</h4>
                            <div class="item form-group{{ $errors->has('mim_amount_for_order') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Minimum Amount For Order <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mim_amount_for_order', null, array('placeholder' => 'Minimum Amount For Order','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mim_amount_for_order'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mim_amount_for_order') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('mim_amount_for_free_delivery') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Minimum Amount For Free Delivery <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mim_amount_for_free_delivery', null, array('placeholder' => 'Minimum Amount For Free Delivery','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mim_amount_for_free_delivery'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mim_amount_for_free_delivery') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('mim_amount_for_free_delivery_prime') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Minimum Amount For Free Delivery Prime<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mim_amount_for_free_delivery_prime', null, array('placeholder' => 'Minimum Amount For Free Delivery Prime' ,'class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mim_amount_for_free_delivery_prime'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mim_amount_for_free_delivery_prime') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('update_shopper_location') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Interval Time to update Shopper Location <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('update_shopper_location', null, array('placeholder' => 'Interval Time to update Shopper Location','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('update_shopper_location'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('update_shopper_location') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('update_driver_location') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Interval Time to update Driver Location <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('update_driver_location', null, array('placeholder' => 'Interval Time to update Driver Location','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('update_driver_location'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('update_driver_location') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('update_shopper_location') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Interval Time to update Shopper App <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('update_shopper_app', null, array('placeholder' => 'Interval Time to update Shopper Location','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('update_shopper_app'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('update_shopper_app') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('update_shopper_location') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Interval Time to update Driver Location <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('update_driver_app', null, array('placeholder' => 'Interval Time to update Shopper Location','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('update_driver_app'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('update_driver_app') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="reset" class="btn btn-primary">Reset</button>
                                    <button id="send" type="submit" class="btn btn-success">Submit</button>
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
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
@endsection
@push('scripts')
    <script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.replace( 'editor' );
    </script>
@endpush
