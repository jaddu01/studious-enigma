@extends('admin.layouts.app')

@section('title', 'Social Media Setting')

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

                            {!! Form::model($setting,['url' => 'admin/setting/social_media','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            {{method_field('POST')}}
                            <span class="section">Social Media Setting</span>

							<h4>Page & Accounts Links:</h4>
                            <div class="item form-group{{ $errors->has('facebook_page') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">facebook page <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('facebook_page', null, array('placeholder' => 'facebook page','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('facebook_page'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('facebook_page') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('twitter_page') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">twitter page <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('twitter_page', null, array('placeholder' => 'twitter page','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('twitter_page'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('twitter_page') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('instagram_page') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">instagram page <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('instagram_page', null, array('placeholder' => 'instagram_page','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('instagram_page'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('instagram_page') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('linkedin_page') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">linkedin page <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('linkedin_page', null, array('placeholder' => 'linkedin page','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('linkedin_page'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('linkedin_page') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <h4>Share Links: </h4>
                            <div class="item form-group{{ $errors->has('whatsapp_share') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">whatsapp share <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('whatsapp_share', null, array('placeholder' => 'whatsapp_share','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('whatsapp_share'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('whatsapp_share') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('facebook_share') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">facebook share <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('facebook_share', null, array('placeholder' => 'facebook_share','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('facebook_share'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('facebook_share') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('instagram_share') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">instagram share <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('instagram_share', null, array('placeholder' => 'instagram_share','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('instagram_share'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('instagram_share') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('twitter_share') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">twitter share <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('twitter_share', null, array('placeholder' => 'twitter_share','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('twitter_share'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('twitter_share') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('linkedin_share') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">linkedin share <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('linkedin_share', null, array('placeholder' => 'linkedin_share','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('linkedin_share'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('linkedin_share') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('other_share') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">other share <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('other_share', null, array('placeholder' => 'other share','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('other_share'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('other_share') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            
                            <h4>Like & Follow Links:</h4>
                            <div class="item form-group{{ $errors->has('facebook_follow') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">facebook follow <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('facebook_follow', null, array('placeholder' => 'facebook follow','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('facebook_follow'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('facebook_follow') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('twitter_follow') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">twitter follow <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('twitter_follow', null, array('placeholder' => 'twitter follow','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('twitter_follow'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('twitter_follow') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('instagram_follow') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">instagram follow <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('instagram_follow', null, array('placeholder' => 'instagram follow','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('instagram_follow'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('instagram_follow') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('linkedin_follow') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">linkedin follow <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('linkedin_follow', null, array('placeholder' => 'linkedin follow','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('linkedin_follow'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('linkedin_follow') }}</strong>
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

    {!! $validator !!}
@endsection

