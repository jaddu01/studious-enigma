@extends('admin.layouts.app')

@section('title', 'Add General Setting')

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

                           <!--  {!! Form::open(['url' => 'admin/setting/reboot','method'=>'post']) !!}
                            <button  class="btn btn-success">Re Boot</button>
                            {!! Form::close() !!} -->

                            {!! Form::model($setting,['url' => 'admin/setting/general','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">General Setting</span>

                            <div class="item form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">APP NAME <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('app_name', null, array('placeholder' => 'app name','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('app_name'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('app_name') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('app_env') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">App env <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('app_env',Helper::$env, null, array('placeholder' => 'app env','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('app_env'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('app_env') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('app_debug') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">App debug <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('app_debug',Helper::$debug, null, array('class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('app_debug'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('app_debug') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('app_log_level') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">App log level <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('app_log_level', null, array('placeholder' => 'app log level','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('app_log_level'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('app_log_level') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('app_url') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">App Url <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('app_url', null, array('placeholder' => 'app url','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('app_url'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('app_url') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('mail_driver') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mail driver <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mail_driver', null, array('placeholder' => 'mail driver','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mail_driver'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mail_driver') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('mail_host') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mail host <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mail_host', null, array('placeholder' => 'mail host','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mail_host'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mail_host') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('mail_port') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mail port <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mail_port', null, array('placeholder' => 'mail port','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mail_port'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mail_port') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('mail_username') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mail Username <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mail_username', null, array('placeholder' => 'mail username','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mail_username'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mail_username') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('mail_password') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mail Password <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mail_password', null, array('placeholder' => 'mail password','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mail_password'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mail_password') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('mail_encryption') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mail encryption <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mail_encryption', null, array('placeholder' => 'mail encryption','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mail_encryption'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mail_encryption') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('mail_from_address') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">mail_from_address <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mail_from_address', null, array('placeholder' => 'mail from address','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mail_from_address'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mail_from_address') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('mail_from_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mail from name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mail_from_name', null, array('placeholder' => 'mail from name','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('mail_from_name'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('mail_from_name') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('app_url_android') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">App url android <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('app_url_android', null, array('placeholder' => 'app url android','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('app_url_android'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('app_url_android') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('app_url_ios') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">App url ios <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('app_url_ios', null, array('placeholder' => 'app url ios','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('app_url_ios'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('app_url_ios') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('under_maintenance') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">under maintenance <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('under_maintenance',Helper::$maintenance, null, array('class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('under_maintenance'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('under_maintenance') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('app_logo') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">App logo <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    @if(!empty($setting->app_logo))
                                        <img src="{{$setting->app_logo}}" style="background:silver none repeat scroll 0 0" >
                                    @endif

                                    {!!  Form::file('app_logo', null, array('placeholder' => 'applogo','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('app_logo'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('app_logo') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('timezone') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Timezone <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('timezone', null, array('placeholder' => 'timezone','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('timezone'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('timezone') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('pagination_limit') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Pagination limit <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('pagination_limit', null, array('placeholder' => 'pagination limit','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('pagination_limit'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('pagination_limit') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Email <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::email('email', null, array('placeholder' => 'email','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Phone <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('phone', null, array('placeholder' => 'phone','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mobile 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('mobile', null, array('placeholder' => 'mobile','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    
                                </div>
                            </div>
                               <div class="item form-group{{ $errors->has('currency') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">currency 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                {!!  Form::text('currency', null, array('placeholder' => 'Currency','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('currency'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('currency') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Address <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::textarea('address', null, array('placeholder' => 'address','class' => 'form-control col-md-7 col-xs-12','id'=>'editor' )) !!}
                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('address') }}</strong>
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
