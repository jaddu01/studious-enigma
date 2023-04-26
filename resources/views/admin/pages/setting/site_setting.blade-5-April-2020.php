@extends('admin.layouts.app')

@section('title', 'Site Setting')

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

                            {!! Form::model($setting,['url' => 'admin/setting/site_setting','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">Site Setting</span>

                            <div class="item form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Min Price <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('min_price', null, array('placeholder' => 'min price','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('min_price'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('min_price') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Max Price <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('max_price', null, array('placeholder' => 'max price','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('max_price'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('max_price') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Free delivery charge <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('free_delivery_charge', null, array('placeholder' => 'Free delivery charge','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('free_delivery_charge'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('free_delivery_charge') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Phone <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('phone', null, array('placeholder' => 'Phone','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('whats_up') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">whatsup number <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('whats_up', null, array('placeholder' => 'whatsup number','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('whats_up'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('whats_up') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('facebook') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">facebook <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('facebook', null, array('placeholder' => 'facebook','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('facebook'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('facebook') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('twitter') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">twitter <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('twitter', null, array('placeholder' => 'twitter','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('twitter'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('twitter') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="item form-group{{ $errors->has('instagram') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">instagram <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('instagram', null, array('placeholder' => 'instagram','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('instagram'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('instagram') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('linkedin') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">linkedin <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('linkedin', null, array('placeholder' => 'linkedin','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('linkedin'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('linkedin') }}</strong>
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
