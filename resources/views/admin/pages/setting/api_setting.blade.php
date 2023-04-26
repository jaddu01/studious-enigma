@extends('admin.layouts.app')

@section('title', 'Api Setting')

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

                            {!! Form::model($setting,['url' => 'admin/setting/api_setting','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">Api Setting</span>
							<h4>Facebook:</h4>
                            <div class="item form-group{{ $errors->has('facebook_app_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Facebook App Id <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('facebook_app_id', null, array('placeholder' => 'Facebook App Id','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('facebook_app_id'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('facebook_app_id') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('facebook_app_secret_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Facebook App Secret Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('facebook_app_secret_key', null, array('placeholder' => 'Facebook App Secret Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('facebook_app_secret_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('facebook_app_secret_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <h4>Twitter:</h4>
                            <div class="item form-group{{ $errors->has('twitter_app_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Twitter App Id <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('twitter_app_id', null, array('placeholder' => 'Twitter App Id','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('twitter_app_id'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('twitter_app_id') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('twitter_app_secret_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Twitter App Secret Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('twitter_app_secret_key', null, array('placeholder' => 'Twitter App Secret Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('twitter_app_secret_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('twitter_app_secret_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <h4>Google Maps Api Keys:</h4>
                            <div class="item form-group{{ $errors->has('ios_customer_app_google_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Ios Customer App Google Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('ios_customer_app_google_key', null, array('placeholder' => 'Ios Customer App Google Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('ios_customer_app_google_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('ios_customer_app_google_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('android_customer_app_google_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Customer App Google Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('android_customer_app_google_key', null, array('placeholder' => 'Android Customer App Google Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('android_customer_app_google_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('android_customer_app_google_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('android_shopper_app_google_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Shopper App Google Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('android_shopper_app_google_key', null, array('placeholder' => 'Android Shopper App Google Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('android_shopper_app_google_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('android_shopper_app_google_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('android_driver_app_google_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Driver App Google Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('android_driver_app_google_key', null, array('placeholder' => 'Android Driver App Google Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('android_driver_app_google_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('android_driver_app_google_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('admin_panel_google_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Admin Panel Google Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('admin_panel_google_key', null, array('placeholder' => 'Admin Panel Google Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('admin_panel_google_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('admin_panel_google_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <h4>Analytics Codes:</h4>
                            <div class="item form-group{{ $errors->has('google_analytics_code') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Google Analytics Code <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('google_analytics_code', null, array('placeholder' => 'Google Analytics Code','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('google_analytics_code'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('google_analytics_code') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('facebook_analytics_code') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Facebook Analytics Code <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('facebook_analytics_code', null, array('placeholder' => 'Facebook Analytics Code','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('facebook_analytics_code'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('facebook_analytics_code') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            
                            <h4>One Signal Notifications:</h4>
                            <div class="item form-group{{ $errors->has('customer_app_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Customer App Id <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('customer_app_id', null, array('placeholder' => 'Customer App Id','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('customer_app_id'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('customer_app_id') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('customer_app_rest_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Customer App Rest Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('linkedin', null, array('placeholder' => 'Customer App Rest Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('customer_app_rest_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('customer_app_rest_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('shopper_app_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Shopper App Id <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('shopper_app_id', null, array('placeholder' => 'Shopper App Id','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('shopper_app_id'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('shopper_app_id') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('shopper_app_rest_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Shopper App Rest Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('shopper_app_rest_key', null, array('placeholder' => 'Shopper App Rest Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('shopper_app_rest_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('shopper_app_rest_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('driver_app_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Driver App Id <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('driver_app_id', null, array('placeholder' => 'Driver App Id','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('driver_app_id'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('driver_app_id') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('driver_app_rest_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Driver App Rest Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('driver_app_rest_key', null, array('placeholder' => 'Driver App Rest Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('driver_app_rest_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('driver_app_rest_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('admin_panel_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Admin Panel Id <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('admin_panel_id', null, array('placeholder' => 'Admin Panel Id','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('admin_panel_id'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('admin_panel_id') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('admin_panel_rest_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Admin Panel Rest Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('admin_panel_rest_key', null, array('placeholder' => 'Admin Panel Rest Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('admin_panel_rest_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('admin_panel_rest_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('all_order_redirect_url') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">All Order Redirect Url <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('all_order_redirect_url', null, array('placeholder' => 'All Order Redirect Url','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('all_order_redirect_url'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('all_order_redirect_url') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('admin_notification_redirect_url') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Admin Notification Redirect Url <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('admin_notification_redirect_url', null, array('placeholder' => 'Admin Notification Redirect Url','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('linkedin'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('admin_notification_redirect_url') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('shopper_notification_redirect_url') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Shopper Notification Redirect Url <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('shopper_notification_redirect_url', null, array('placeholder' => 'Shopper Notification Redirect Url','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('shopper_notification_redirect_url'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('shopper_notification_redirect_url') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('driver_notification_redirect_url') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Driver Notification Redirect Url <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('driver_notification_redirect_url', null, array('placeholder' => 'Driver Notification Redirect Url','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('driver_notification_redirect_url'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('driver_notification_redirect_url') }}</strong>
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
