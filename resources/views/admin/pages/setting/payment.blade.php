@extends('admin.layouts.app')

@section('title', 'Payment Setting')

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

                            {!! Form::model($setting,['url' => 'admin/setting/payment','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">Payment Setting</span>

							<h4>Payment Page:</h4>
                            <div class="item form-group{{ $errors->has('active_payment_page') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Active Payment Page <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('active_payment_page',['yes'=>'Yes','no'=>'No'], null, array('class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('active_payment_page'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('active_payment_page') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <h4>Payment Options:</h4>
                            <div class="item form-group{{ $errors->has('cash_on_delivery') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Cash On Delivery <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('cash_on_delivery',['yes'=>'Yes','no'=>'No'], null, array('class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('cash_on_delivery'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('cash_on_delivery') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('wallet') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">wallet <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('wallet',['yes'=>'Yes','no'=>'No'], null, array('class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('wallet'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('wallet') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('credit_card') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Credit Card <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('credit_card',['yes'=>'Yes','no'=>'No'], null, array('class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('credit_card'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('credit_card') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('paypal') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">paypal <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('paypal',['yes'=>'Yes','no'=>'No'], null, array('class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('paypal'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('paypal') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <h4>Payment Gateway Settings:</h4>
                            <div class="item form-group{{ $errors->has('stripe_secret_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Stripe Secret Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('stripe_secret_key', null, array('placeholder' => 'Stripe Secret Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('stripe_secret_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('stripe_secret_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('stripe_public_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Stripe Public Key <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('stripe_public_key', null, array('placeholder' => 'Stripe Public Key','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('stripe_public_key'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('stripe_public_key') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('paypal_account_email') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Paypal Account Email <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('paypal_account_email', null, array('placeholder' => 'Paypal Account Email','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('paypal_account_email'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('paypal_account_email') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('paypal_currency') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Paypal Currency <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('paypal_currency', null, array('placeholder' => 'Paypal Currency','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('paypal_currency'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('paypal_currency') }}</strong>
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
