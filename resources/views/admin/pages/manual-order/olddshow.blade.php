@extends('admin.layouts.app')

@section('title', ' Add Manual Order |')
@push('css')
 <link href="{{asset('public/css/chosen.min.css')}}" rel="stylesheet">
    <style type="text/css">
    .ms-parent { width: 100% !important; }
    .ms-choice {border: none; background-color: transparent;}
    .pac-container { z-index: 10000;}
    .over{display: block;background: #eee;display: block;height: 100%;width: 100%;top: 0;left: 0;position: absolute;opacity:0.7;z-index:1;}
    .loader-img{width:auto;height:auto;display: block;margin:0 auto;top: 30%;left: 40%;position: absolute;}
    </style>
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
@endpush
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

                            {!! Form::open(['route' => 'manual-order.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','id'=>'manual-order-form','autocomplete'=>'off']) !!}

                            {{csrf_field()}}
                            <span class="section">Add Manual Order</span>
                            <input type="hidden" name="user_id" value="0">
                            <input type="hidden" name="delivery_address_id_val" id="delivery_address_id_val" value="0">
                            <input type="hidden" name="diver_id_val" id="diver_id_val" value="">
                            <input type="hidden" name="shoper_id_val" id="shoper_id_val" value="0">
                            <input type="hidden" name="vendor_id_val" id="vendor_id_val" value="0">
                            <input type="hidden" name="zone_id_val" id="zone_id_val" value="0">
                            <input type="hidden" name="delivery_charges_val" id="delivery_charges_val" value="0">
                            <input type="hidden" name="order_success_id" id="order_success_id" value="">
                                
                                
                            <div class="item form-group{{ $errors->has('customer_phone') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Customer Phone <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('customer_phone', null, array('placeholder' => 'Customer Phone','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('customer_phone'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('customer_phone') }}</strong>
                                            </span>
                                    @endif
                                </div>
                                <button type="button" onclick="getUserByPhone($('[name=customer_phone]').val())" class="btn btn-info">Get Details</button>
                            </div>
                            <div class="item form-group{{ $errors->has('customer_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Customer Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('customer_name', null, array('placeholder' => 'Customer Name','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('customer_name'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('customer_name') }}</strong>
                                            </span>
                                    @endif
                                </div>
                                <button type="button" onclick="showUserModel()" class="btn btn-info">Add New Customer</button>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="item form-group{{ $errors->has('delivery_address') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Delivery Address <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                
                                <input type="hidden" name="delivery_address_id_val" id="delivery_address_id_val" value="">
                               
                                    {!!  Form::select('delivery_address_id',[], null, array('placeholder' => 'Delivery Address','class' => 'form-control col-md-7 col-xs-12','id'=>'delivery_address_id' )) !!}
                                    @if ($errors->has('delivery_address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('delivery_address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button type="button" id="btn_add_model" onclick="openOrderAddressModel($('[name=user_id]').val())" class="btn btn-info">Select Address</button>
                            </div>

                            <div class="item form-group {{ $errors->has('zone_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Load Zone <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php //$zones = []; ?>
                                    {!!  Form::select('zone_id',$zones,null, array('class' => 'form-control select2-multiple','placeholder'=>'Load Zone','id'=>'zone_id')) !!}
                                    {{ Form::filedError('zone_id') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('vendor_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Load Vendor <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php $vendors = []; ?>
                                    {!!  Form::select('vendor_id',$vendors,null, array('class' => 'form-control select2-multiple','placeholder'=>'Load Vendor','id'=>'vendor_id')) !!}
                                    {{ Form::filedError('vendor_id') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('shopper_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Load Shopper <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php $shoppers = []; ?>
                                    {!!  Form::select('shopper_id',$shoppers,null, array('class' => 'form-control select2-multiple','placeholder'=>'Load Shopper','id'=>'shopper_id')) !!}
                                    {{ Form::filedError('shopper_id') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('driver_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Load Driver <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php $drivers = []; ?>
                                    {!!  Form::select('driver_id',$drivers,null, array('class' => 'form-control select2-multiple','placeholder'=>'Load Driver','id'=>'driver_id')) !!}
                                    {{ Form::filedError('driver_id') }}
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="item form-group{{ $errors->has('delivery_date') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Delivery Date <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('delivery_date', null, array('placeholder' => 'Delivery Date','class' => 'form-control datepicker col-md-7 col-xs-12' ,'id'=>'delivery_date')) !!}
                                    @if ($errors->has('delivery_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('delivery_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('delivery_time_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Time Slot <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('delivery_time_id',[], null, array('placeholder' => 'delivery time','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('delivery_time_id'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('delivery_time_id') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <button type="button" id="add_product_model" onclick="openAddProductModel()"  class="btn btn-primary">Add Product</button>
                            <table class="table table-striped table-bordered" id="users-table" style="display: none;">
                                <thead  class="success">
                                <tr>
                                    <th>Product ID</th>
                                    <th>Total Price </th>
                                    <th>Price </th>
                                    <th>Is Offer</th>
                                    <th>Offer Value</th>
                                    <th>Qty</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>


                            <div class="item form-group{{ $errors->has('total_offer') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Total Offer <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('total_offer', 0, array('placeholder' => 'Total Offer','class' => 'form-control col-md-7 col-xs-12','readonly'=>'true' )) !!}
                                    @if ($errors->has('total_offer'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('total_offer') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('number_of_orders') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Number Of Orders <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('number_of_orders', 0, array('placeholder' => 'offer value','class' => 'form-control col-md-7 col-xs-12','readonly'=>'true' )) !!}
                                    @if ($errors->has('number_of_orders'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('number_of_orders') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('sub_total') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sub-Total <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('sub_total', 0, array('placeholder' => 'Sub-Total','class' => 'form-control col-md-7 col-xs-12','readonly'=>'true' )) !!}
                                    @if ($errors->has('sub_total'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('sub_total') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('delivery_charge') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Delivery Charge <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('delivery_charge', 0, array('placeholder' => 'Delivery Charge','class' => 'form-control col-md-7 col-xs-12','readonly'=>'true' )) !!}
                                    @if ($errors->has('delivery_charge'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('delivery_charge') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('promo_discount') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Promo Discount <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('promo_discount', 0, array('placeholder' => 'offer value','class' => 'form-control col-md-7 col-xs-12','min'=>'0' )) !!}
                                    @if ($errors->has('promo_discount'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('promo_discount') }}</strong>
                                            </span>
                                    @endif
                                </div>
                               <!--  <button type="button" class="btn btn-info">Add Promo Discount </button> -->
                            </div>
                            <div class="item form-group{{ $errors->has('admin_discount') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Admin discount <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('admin_discount', 0, array('placeholder' => 'Admin discount','class' => 'form-control col-md-7 col-xs-12','min'=>'0' )) !!}
                                    @if ($errors->has('admin_discount'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('admin_discount') }}</strong>
                                            </span>
                                    @endif
                                </div>
                               <!--  <button type="button" class="btn btn-info">Add Discount</button> -->
                            </div>
                            <div class="item form-group{{ $errors->has('total') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Total <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('total', 0, array('placeholder' => 'Admin discount','class' => 'form-control col-md-7 col-xs-12','readonly'=>'true','min'=>'0' )) !!}
                                    @if ($errors->has('total'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('total') }}</strong>
                                            </span>
                                    @endif
                                </div>

                            </div>
                            <div class="over" id="loader" style="display: none;"><img class='loader-img' src='{{asset('public/images/loader-new.gif')}}'></div>
                            <div class="ln_solid"></div>
                            <div class="col-sm-12 text-center padd40">
                                <button type="reset" class="btn btn-default" onclick="history.go(0);">Cancel</button>
                                <button type="button" id="submit-manual-order" class="btn btn-success">Submit</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--Order sucessfull popup model -->
<div id="orderSuccessfull" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Order Details</h4>
                </div>
                <div class="modal-body">
                 <p id="order_success_msg"></p>
                 <p id="order_success_code"><strong>Order Id</strong> </p>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="javascript:window.location.reload();" class="btn btn-default" data-dismiss="modal">Ok</button>
                    <a href="" id="export_button" class="btn btn-primary">Export Invoice AS JPG</a>
                </div>
            </div>
        </div>
</div>
    <!--Add new user model -->

    <div id="addNewUser" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New User</h4>
                </div>
                <div class="modal-body">
                    <div class="row item form-group {{ $errors->has('country_phone_code') ? ' has-error' : '' }}">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email">country phone code <span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            {!!  Form::select('country_phone_code',$countryPhoneCode,null, array('class' => 'form-control select2-multiple','placeholder'=>'Phone Code','id'=>'country_phone_code')) !!}
                            {{ Form::filedError('country_phone_code') }}
                        </div>
                    </div>
                    <div class="row item form-group{{ $errors->has('add_customer_phone') ? ' has-error' : '' }}">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Customer Phone <span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            {!!  Form::text('add_customer_phone', null, array('placeholder' => 'Customer Phone','class' => 'form-control priceNum col-md-7 col-xs-12','pattern'=>'[789][0-9]{9}' )) !!}
                            @if ($errors->has('add_customer_phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('add_customer_phone') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                    <div class="row item form-group{{ $errors->has('add_customer_name') ? ' has-error' : '' }}">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Customer Name <span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            {!!  Form::text('add_customer_name', null, array('placeholder' => 'Customer Name','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('add_customer_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('add_customer_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button style="margin-bottom: 5px;" type="button" onclick="addNewUser()" class="btn btn-default" >Add</button>
                </div>
            </div>
        </div>
    </div>

    <!--select delivery address model -->
    <div id="selectOrderAddress" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select delivery address </h4>
                </div>
                <div class="modal-body">
                        {!! Form::open(['url' => ['admin/manual-order/modify-address'],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','id'=>'modify-address-from']) !!}
                        {{csrf_field()}}

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load Customer<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <p class="pname"><span id="label-name"></span></p>
                            </div>
                        </div>

                        <div class="item form-group {{ $errors->has('shipping_location') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Select Address<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                {!!  Form::select('shipping_location',[],null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'shipping_location')) !!}
                                {{ Form::filedError('shipping_location') }}
                            </div>
                        </div>

                        <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Address Name<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                {!!  Form::text('name', null, array('id'=>'name', 'placeholder' => 'Address name','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                @endif
                            </div>
                        </div>
                        <div class="item form-group{{ $errors->has('address_name') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Address Location<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                {!!  Form::text('address', null, array('id'=>'address_name', 'placeholder' => 'Address name','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                @if ($errors->has('address_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><span class="required"></span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div id="us3" style="width: 300px; height: 300px;"></div>
                                <div class="clearfix">&nbsp;</div>
                            </div>
                        </div>
                        <div class="item form-group{{ $errors->has('lat') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">latitute <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                {!!  Form::text('lat',  null, array('id'=>'lat','placeholder' => 'latitute','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                @if ($errors->has('lat'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('lat') }}</strong>
                                            </span>
                                @endif
                            </div>
                        </div>
                        <div class="item form-group{{ $errors->has('lng') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Longitute <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                {!!  Form::text('lng', null, array('id'=>'long','placeholder' => 'lng','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                @if ($errors->has('lng'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('lng') }}</strong>
                                            </span>
                                @endif
                            </div>
                        </div>
                        <div class="item form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                                Description <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                {!!  Form::textarea('description', null, array('placeholder' => 'address','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button style="margin-bottom: 5px;"  onclick="addAddress()" type="button" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

            </div>
        </div>
    </div>

    <div id="addProduct" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Product</h4>
                </div>
                <div class="modal-body">
                    <div class="row item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load Zone<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <p class="pname"><span id="label-zone"></span></p>
                        </div>
                    </div>
                    <div class="row item form-group{{ $errors->has('vendor_product_id') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                             {!!  Form::select('vendor_product_id',[], null, array('class' => 'form-control select2-multiple  col-md-7 col-xs-12','placeholder'=>'product','id'=>'vendor_product_id','required')) !!}
                            <!-- {!!  Form::select('vendor_product_id',[], null, array('placeholder' => 'Admin discount','class' => 'form-control select2-multiple col-md-7 col-xs-12' )) !!} -->
                            @if ($errors->has('vendor_product_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('vendor_product_id') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                    <div class="row item form-group{{ $errors->has('qty') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Qty <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::number('qty', 1, array('placeholder' => 'qty','class' => 'form-control col-md-7 col-xs-12', 'min'=>'1', 'autocomplete'=>'off','id'=>'qty')) !!}
                            @if ($errors->has('total'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('qty') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" style="margin-bottom: 5px;" onclick="addNewProduct()" class="btn btn-default" >Add</button>
                </div>
            </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts')

    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&sensor=false&libraries=places"></script>
    <script type="text/javascript" src="{{ asset('public/js/locationpicker.jquery.min.js')}}"></script>
    <script src="{{asset('public/js/chosen.jquery.min.js')}}"></script>
    <script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script><!-- 
     <script src="{{asset('public/js/chosen.jquery.min.js')}}"></script> -->
    <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

   
    <script>
        $('#delivery_date').keypress(function(event){
            event.preventDefault();  
        });
        $(document).keydown( function(e){  
          if( e.which == 8 && ( document.activeElement.id == 'delivery_date') || e.which == 8 && ( document.activeElement.id == 'qty')){   
            e.preventDefault();  
            return false;   
          } 
        }); 
         $("[name=qty]").keypress(function(event){
            event.preventDefault();  
        }); 
         /*restrict copy paste event*/
         $( "[name=qty]").on( "copy cut paste drop", function() {
                return false;
        });
         /*allow only digits*/
        $('[name=promo_discount]').bind('keyup paste', function(){
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        $('[name=admin_discount]').bind('keyup paste', function(){
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        
        /*$(".chosen").chosen();*/
        $(function () {
            $("#btn_add_model").attr('disabled','disabled');
            $("#add_product_model").attr('disabled','disabled');
            $("#submit-manual-order").attr('disabled','disabled');
            $("[name=delivery_date]").attr('readonly','readonly');
            $("[name=delivery_charge]").val(0);
            $("[name=total]").val(0);
            $(".priceNum").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    //display error message
                    $(".numbervalid").html("Digits Only").show().fadeOut("slow");
                    return false;
                }
             });
             total_order=0;
             number_of_orders=0;
             total_offer=0;
             delivery_charge=0;
             total=0;
             delivery_locations={};
             add_product_data={};
            $('#driver_id').select2({
                placeholder: "load driver",
                allowClear: true
            });
            $('#shopper_id').select2({
                placeholder: "load shopper",
                allowClear: true
            });
            $('#zone_id').select2({
                placeholder: "load zone",
                allowClear: true
            });
            $('#vendor_product_id').select2({
                placeholder: "load product",
                allowClear: true
            });
            $('#vendor_id').select2({
                placeholder: "load vendor",
                allowClear: true
            });
            var delAdd = $("#delivery_address_id").val();
            if(delAdd != ''){
                $('.datepicker' ).datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    startDate: '-0m',
                });
            }
            

            $("[name=shipping_location]").on("change" , function () {

                var pos = $(this).val();
                if(pos==0){
                    return false;
                }
                $('#lat').val(delivery_locations[pos].lat);
                $('#long').val(delivery_locations[pos].lng);
                $('[name=name]').val(delivery_locations[pos].name);
                $('[name=address]').val(delivery_locations[pos].address);
                $('[name=description]').val(delivery_locations[pos].description);

                $('#us3').locationpicker({
                    location: {
                        latitude: delivery_locations[pos].lat,
                        longitude:  delivery_locations[pos].lng
                    },
                    radius: 300,
                    inputBinding: {
                        latitudeInput: $('#lat'),
                        longitudeInput: $('#long'),
                        //radiusInput: $('#us3-radius'),
                        locationNameInput: $('#address_name'),
                        locationNameInput: $('[name=address]')
                    },
                    enableAutocomplete: true,
                    onchanged: function (currentLocation, radius, isMarkerDropped) {
                        // Uncomment line below to show alert on each Location Changed event
                        //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                    }
                });
            });

            $("[name=delivery_date]").on("change" , function (){
                //alert($("[name=zone_id]").val())
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; 
                if(dd<10){dd='0'+dd;} 
                if(mm<10){mm='0'+mm;}
                var yyyy = today.getFullYear();
                var curDate = yyyy+'-'+mm+'-'+dd;
                var today = $(this).val();
                var now = new Date(Date.now());
                // alert($.datepicker.formatDate('yy-mm-dd', new Date()));
                var curTime = now.getHours() + ":" + now.getMinutes();
                //alert(curTime);
               if($(this).val()== '' || $(this).val()== null){
                    $("[name=delivery_time_id]") .find('option')
                        .remove();
                    return false;
                   
                }
                 if(today < curDate){
                    $("[name=delivery_time_id]") .find('option')
                        .remove();
                    return false;
                 }
                if($("[name=zone_id]").val()=="" || $("[name=zone_id]").val()== null){
                     new PNotify({
                            title: 'Error',
                            text: 'Please select delivery address first',
                            type: "error",
                            styling: 'bootstrap3'
                        });
                    $("#delivery_date").val("");
                    return false
                }
                
                $.ajax({
                    url: "{!! route('get-delivery-day') !!}",
                    type: 'GET',
                    data: {
                        date : $(this).val(),
                        id : $("[name=zone_id]").val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function( data ) {
                        //console.log(data.data.delivery_time[0].lock_time);
                        var delivery_time = data.data.delivery_time;
                        var html ;
                        for (var i in delivery_time){
                            if(today == curDate){
                                if(delivery_time[i].lock_time > curTime){
                          
                                    html+="<option value='"+delivery_time[i].id+"'>"+delivery_time[i].name+"</option>";
                                }
                            }else{
                                html+="<option value='"+delivery_time[i].id+"'>"+delivery_time[i].name+"</option>";
                            }
                            
                        }
                        $("[name=delivery_time_id]").html(html);

                    },
                    error: function( data, status, error ) {

                        new PNotify({
                            title: 'Error',
                            text: data.responseJSON.message,
                            type: "error",
                            styling: 'bootstrap3'
                        });
                    }
                });
            });

              $("#delivery_address_id").change(function (){
                //alert($(this).val());
                 $("#delivery_date").val("");
                $("[name=delivery_time_id]") .find('option')
                        .remove();
                $("#users-table").find("tr:gt(0)").remove();
                $("[name=number_of_orders]").val(0);
                $("[name=sub_total]").val(0);
                $("[name=total]").val(0);
                
                //$("#users-table tr").remove();
                if($(this).val()==""){
                        $("#zone_id").val(null).trigger("change"); 
                        $("#driver_id").val(null).trigger("change"); 
                        $("#shopper_id").val(null).trigger("change"); 
                        $("#vendor_id").val(null).trigger("change"); 
                        $("[name=delivery_charge]").val('');
                        $("[name=delivery_date]").attr('readonly','readonly');
                        $("#delivery_date").datepicker("destroy");
                        $("#add_product_model").attr('disabled','disabled');
                        return false;
                }

                $.ajax({
                    url: "{!! route('load-zone') !!}",
                    type: 'GET',
                    data: {
                        id : $(this).val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function( data ) {
                        console.log(data);
                        $("#driver_id").val('');
                        $("#vendor_id").val('');
                        $("#shopper_id").val('');
                        $("[name=delivery_charge]").val('');
                        $("[name=delivery_charge]").val(0);
                        if(data.data.zone_id == 0 || data.data.zone_id == ''){

                        $("#zone_id").val(data.data.zone_id);
                        $('#zone_id')
                        .find('option')
                        .remove()
                        .end()
                        .append('<option value="">There is no matching zone</option>')
                        .val('whatever');
                            $("#driver_id").val('');
                            $("#vendor_id").val('');
                            $("#shopper_id").val('');
                            $("[name=delivery_charge]").val('');
                            $("#add_product_model").attr('disabled','disabled');
                            
                    }else{
                            //console.log('hi');
                            var zoneOption = <?php echo json_encode($zones) ?>;
                            //console.log(zoneOption[data.data.zone_id]);
                            $('#zone_id').find('option').remove();
                                $('#zone_id')
                                    .append($("<option></option>")
                                    .attr("value",data.data.zone_id)
                                    .text(zoneOption[data.data.zone_id]));
                            
                            $('#driver_id').find('option').remove();
                                $('#driver_id').append($("<option></option>")
                                    .attr("value",data.data.driver.id)
                                    .text(data.data.driver.name));
                          
                            $('#vendor_id').find('option').remove();
                                $('#vendor_id')
                                    .append($("<option></option>")
                                    .attr("value",data.data.vendor.id)
                                    .text(data.data.vendor.name));
                            
                             $('#shopper_id').find('option').remove();
                                $('#shopper_id')
                                    .append($("<option></option>")
                                    .attr("value",data.data.shoper.id)
                                    .text(data.data.shoper.name));
                            
                        $("#zone_id").val(data.data.zone_id);
                      /*  $("#driver_id").val(data.data.driver.id);
                        $("#vendor_id").val(data.data.vendor.id);
                        $("#shopper_id").val(data.data.shoper.id);*/
                        $("[name=delivery_charge]").val(data.data.delivery_charges);
                        
                         
                           
                    }
                    
                    $("[name=delivery_date]").removeAttr('readonly'); 
                        $('.datepicker' ).datepicker({
                            autoclose: true,
                            format: 'yyyy-mm-dd',
                            startDate: '-0m',
                        });
                        $("#add_product_model").removeAttr('disabled');
                        if($("#zone_id").val() == '' || $("#zone_id").val() == null || $("#zone_id").val() == 0){
                                $("#add_product_model").attr('disabled','disabled');
                        }
                        $('#zone_id').select2({
                            placeholder: "load zone",
                            //allowClear: true
                        });
                        $('#vendor_id').select2({
                            placeholder: "load vendor",
                            //allowClear: true
                        });
                        $('#driver_id').select2({
                            placeholder: "load driver",
                            //allowClear: true
                        });
                        $('#shopper_id').select2({
                            placeholder: "load shopper",
                            //allowClear: true
                        });

                    },
                    error: function( data, status, error ) {

                        new PNotify({
                            title: 'Error',
                            text: data.responseJSON.message,
                            type: "error",
                            styling: 'bootstrap3'
                        });
                    }
                });

              });
              
             
            /*$("[name=promo_discount]").on("change" , function (){
                
                var st = 0;
                 st = $("[name=sub_total]").val();
                 //alert($(this).val());
                 var s= $(this).val();
                 s = s.replace(/^0+/, '');
                 //alert(s);
                 if(s > st){
                        new PNotify({
                            title: 'Error',
                            text: "Discount can not be more than sub total",
                            type: "error",
                            styling: 'bootstrap3'
                        });
                         $("[name=promo_discount]").val(0);
                        return false;
                       
                 }
            });*/
            $("[name=zone_id]").on("change" , function (){
                //alert($(this).val());
                if($(this).val()=="" || $(this).val()== null){
                    return false
                }
                $.ajax({
                    url: "{!! route('zone-details') !!}",
                    type: 'GET',
                    data: {
                        id : $(this).val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function( data ) {
                        //console.log(data);
                         $('#driver_id').find('option').remove();
                                $('#driver_id').append($("<option></option>")
                                    .attr("value",data.data.driver.id)
                                    .text(data.data.driver.name));
                          
                        $('#vendor_id').find('option').remove();
                                $('#vendor_id')
                                    .append($("<option></option>")
                                    .attr("value",data.data.vendor.id)
                                    .text(data.data.vendor.name));
                            
                        $('#shopper_id').find('option').remove();
                                $('#shopper_id')
                                    .append($("<option></option>")
                                    .attr("value",data.data.shoper.id)
                                    .text(data.data.shoper.name));
                        $("#driver_id").val(data.data.driver.id);
                        $("#vendor_id").val(data.data.vendor.id);
                        $("#shopper_id").val(data.data.shoper.id);
                        $('#vendor_id').select2({
                            placeholder: "load vendor",
                            allowClear: true
                        });
                        $('#driver_id').select2({
                            placeholder: "load driver",
                            allowClear: true
                        });
                        $('#shopper_id').select2({
                            placeholder: "load shopper",
                            allowClear: true
                        });

                    },
                    error: function( data, status, error ) {

                        new PNotify({
                            title: 'Error',
                            text: data.responseJSON.message,
                            type: "error",
                            styling: 'bootstrap3'
                        });
                    }
                });
            });
            $('#us3').locationpicker({
                location: {
                    latitude: 20.593683,
                    longitude: 78.962883
                },
                radius: 300,
                inputBinding: {
                    latitudeInput: $('#lat'),
                    longitudeInput: $('#long'),
                    //radiusInput: $('#us3-radius'),
                    locationNameInput: $('#address_name')
                },
                enableAutocomplete: true,
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    // Uncomment line below to show alert on each Location Changed event
                    //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                }
            });


            $("#selectOrderAddress").on('hidden.bs.modal', function () {
               // alert('hi');
                $.ajax({
                    url: "{!! route('get-user-by-param') !!}",
                    type: 'GET',
                    data: {
                        id : $("[name=user_id]").val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function( data ) {
                        console.log(data.data);
                        var delivery_locations = data.data.delivery_location;
                        var html ;

                        var add_val = $("#delivery_address_id_val").val();
                        $("#shopper_id").val($("#shoper_id_val").val());
                        $("#driver_id").val($("#diver_id_val").val());
                        $("#vendor_id").val($("#vendor_id_val").val());
                        $("#zone_id").val($("#zone_id_val").val());
                        $("[name=delivery_charge]").val($("#delivery_charges_val").val());
                        $('.datepicker' ).datepicker({
                            autoclose: true,
                            format: 'yyyy-mm-dd',
                            startDate: '-0m',
                        });

                        $('#zone_id').select2({
                            placeholder: "load zone",
                            allowClear: true
                        });
                        $('#vendor_id').select2({
                            placeholder: "load vendor",
                            allowClear: true
                        });
                        $('#driver_id').select2({
                            placeholder: "load driver",
                            allowClear: true
                        });
                        $('#shopper_id').select2({
                            placeholder: "load shopper",
                            allowClear: true
                        });
                        //console.log(add_val);
                        for (var i in delivery_locations){
                            if(delivery_locations[i].id == add_val){
                                html+="<option selected value='"+delivery_locations[i].id+"'>"+delivery_locations[i].name+"</option>";
                            }else{

                                html+="<option value='"+delivery_locations[i].id+"'>"+delivery_locations[i].name+"</option>";
                            }
                            
                        }
                        $("[name=delivery_address_id]").html(html);
                        if($("#zone_id").val() == "" || $("#zone_id").val() == null){
                            $("#add_product_model").attr('disabled','disabled');
                        }
                       

                    },
                    error: function( data, status, error ) {

                        new PNotify({
                            title: 'Error',
                            text: data.responseJSON.message,
                            type: "error",
                            styling: 'bootstrap3'
                        });
                    }
                });
            });

        });

      

        $("#submit-manual-order").on('click',function () {
            var promoVal = $("[name=promo_discount]").val();
            var adminVal = $("[name=admin_discount]").val();
            var allDiscount = parseInt(promoVal)+parseInt(adminVal);
            var subTotalVal = $("[name=sub_total]").val();
        
            if($("#delivery_address_id").val() == "" || $("#delivery_address_id").val() == null){
                 new PNotify({
                        title: 'Error',
                        text: 'Please select delivery address first',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                 return false;
            }else{
                if(allDiscount > subTotalVal){
                    new PNotify({
                        title: 'Error',
                        text: 'Discount can not be greater than sub total',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                 return false;

                }else{

             $.ajax({
                url: "{!! route('manual-order.store') !!}",
                type: 'POST',
                data: $("#manual-order-form").serialize()+"&_token={{ csrf_token() }}",
                beforeSend: function()
                {
                    $('#loader').css('display','block');
                },
                success: function( data ) {
                    console.log(data);
                    var type ='success';
                    if(data.error==true){
                         $('#loader').css('display','none');
                         type = 'error';
                         title="Error"
                    }else{
                        title="Success"
                        $("#order_success_msg").text(data.data.msg);
                        $("#order_success_code").text(data.data.order_code);
                        $("#order_success_id").val(data.data.order_id);
                        var url = "{{url('admin/order/pdfdownload')}}/"+data.data.order_id;
                        $("#export_button").attr("href",url);
                        $("#orderSuccessfull").modal('show');
                    }
                    new PNotify({
                        title: title,
                        text: data.message,
                        type: type,
                        styling: 'bootstrap3'
                    });

                },
                error: function( data, status, error ) {
                     $('#loader').css('display','none');
                    new PNotify({
                        title: 'Error',
                        text: data.responseJSON.message,
                        type: "error",
                        styling: 'bootstrap3'
                    });
                }
            });
         }
            }
            
        });

         function minFromMidnight(tm) {
                var ampm = tm.substr(-2);
                var clk;
                if (tm.length <= 6) {
                    clk = tm.substr(0, 4);
                } else {
                    clk = tm.substr(0, 5);
                }
                var m = parseInt(clk.match(/\d+$/)[0], 10);
                var h = parseInt(clk.match(/^\d+/)[0], 10);
                h += (ampm.match(/pm/i)) ? 12 : 0;
                return h * 60 + m;
        }

        function openAddProductModel() {

            $("#label-zone").text($("[name=zone_id] option:selected").text());

            var vendor_id = $("#vendor_id").val();


             $.ajax({
                 url: "{!! route('get-vendor-product') !!}",
                 type: 'GET',
                 data: {
                     vendor_id : vendor_id,
                     _token: '{{ csrf_token() }}'
                 },
                 success: function( data ) {
                    var product = window.add_product_data = data.data;
                     var html ;
                     for (var i in product){
                         html+="<option value='"+product[i].product.id+"'>"+product[i].product.name+"</option>";
                     }
                     $("[name=vendor_product_id]").html(html);
                 },
                 error: function( data, status, error ) {

                     new PNotify({
                         title: 'Error',
                         text: data.responseJSON.message,
                         type: "error",
                         styling: 'bootstrap3'
                     });
                 }
             });

            $("#addProduct").modal('show');
        }
         function Remove(button) {
            var rowCount = $('#users-table tr').length -1;
           
            var row = $(button).closest("TR");
            var row_tprice = row.find('.t-price').text();
            var row_offerval = row.find('.offer-val').text();
            var row_pname = row.find('.p-name').text();

            //var name = $("TD", row).eq(0).html();
            var total_order_before = $("[name=total_order]").val();
            var total_offer_before = $("[name=total_offer]").val();
            var number_orders_before = $("[name=number_of_orders]").val();

            var sub_total_before = $("[name=sub_total]").val();
            if (confirm("Do you want to delete: " + row_pname)) {
                var table = $("#users-table")[0];
                if(rowCount == 1){
                $("#users-table").hide();
                }
                //Delete the Table row using it's Index.
                table.deleteRow(row[0].rowIndex);
                $("[name=total_order]").val();
                $("[name=total_offer]").val(parseFloat(total_offer_before) - parseFloat(row_offerval)); 
                $("[name=number_of_orders]").val(parseInt(number_orders_before) - 1);
                $("[name=sub_total]").val(parseFloat(sub_total_before) - parseFloat(row_tprice));
                getTotal();
            }

        };
        
        function addNewProduct() {
            $("#users-table").show();
            var index = $("[name=vendor_product_id]").find(":selected").index();
            var data  = window.add_product_data;
            //console.log(data[index]);
            var total_price =(data[index].offer_price)*parseInt($("[name=qty]").val());
            if(data[index].product.image != null){
            $("tbody").append('<tr><th><input type="hidden" value="'+data[index].id+'" name="product_ids[]">'+data[index].id+'</th><th class="t-price">'+total_price+'</th><th>'+data[index].offer_price+'</th><th>'+data[index].is_offer+'</th><th class="offer-val">'+((data[index].offer !==null)?data[index].offer.offer_value:  0)+'</th><th><input type="hidden" value="'+$("[name=qty]").val()+'" name="product_qtys['+data[index].id+']">'+ $("[name=qty]").val()+'</th><th><img src="'+data[index].product.image.name+'" height="75" width="75"></th><th class="p-name">'+data[index].product.name+'</th><th><button class="btn btn-danger remove-x" type="button" onclick="Remove(this);">Remove</button></th></tr>');
		}else{
			$("tbody").append('<tr><th><input type="hidden" value="'+data[index].id+'" name="product_ids[]">'+data[index].id+'</th><th class="t-price">'+total_price+'</th><th>'+data[index].offer_price+'</th><th>'+data[index].is_offer+'</th><th class="offer-val">'+((data[index].offer !==null)?data[index].offer.offer_value:  0)+'</th><th><input type="hidden" value="'+$("[name=qty]").val()+'" name="product_qtys['+data[index].id+']">'+ $("[name=qty]").val()+'</th><th>No image</th><th class="p-name">'+data[index].product.name+'</th><th><button class="btn btn-danger remove-x" type="button">Remove</button></th></tr>');
			
			
		}
        var tp = 0;
        var to = 0;

           
            $(".t-price").each(function(){
               tp += parseInt($(this).text());
            });
            $(".offer-val").each(function(){
               to += parseInt($(this).text());
            });
             window.total_order =tp;
             var tableRowCount = $('#users-table tr').length - 1;
            window.number_of_orders = tableRowCount;
            
            //window.total_offer+=parseInt((data[index].offer !==null)?data[index].offer.offer_value:  0);
            window.total_offer=to;
            window.total = parseFloat(window.total_order)+parseFloat(window.delivery_charge);
             var DC =  $("[name=delivery_charge]").val();
            $("[name=total_order]").val(window.total_order);
            $("[name=total_offer]").val(window.total_offer);
            $("[name=number_of_orders]").val(window.number_of_orders);
            $("[name=sub_total]").val(window.total_order);
            if( DC != 0){
               // $("[name=total]").val(parseFloat($("[name=sub_total]").val())+parseFloat($("[name=total_offer]").val())-parseFloat($("[name=promo_discount]").val())-parseFloat($("[name=admin_discount]").val())+parseFloat(DC));
               
               $("[name=total]").val(parseFloat($("[name=sub_total]").val())-parseFloat($("[name=promo_discount]").val())-parseFloat($("[name=admin_discount]").val())+parseFloat(DC));
            }else{
                //$("[name=total]").val(parseFloat($("[name=sub_total]").val())+parseFloat($("[name=total_offer]").val())+/parseFloat($("[name=delivery_charge]").val())-parseFloat($("[name=promo_discount]").val())-parseFloat($("[name=admin_discount]").val()));
                
                $("[name=total]").val(parseFloat($("[name=sub_total]").val())+parseFloat($("[name=delivery_charge]").val())-parseFloat($("[name=promo_discount]").val())-parseFloat($("[name=admin_discount]").val()));
            }
            
             $("#submit-manual-order").removeAttr('disabled');

        }
        
        function getTotal() {

           // var total = parseFloat($("[name=sub_total]").val())+parseFloat($("[name=total_offer]").val())+parseFloat($("[name=delivery_charge]").val())-parseFloat($("[name=promo_discount]").val())-parseFloat($("[name=admin_discount]").val());
           if($("[name=delivery_charge]").val() == ''){
                $("[name=delivery_charge]").val(0);
            }
            if($("[name=promo_discount]").val() == ''){
                $("[name=promo_discount]").val(0);
            }
            if($("[name=admin_discount]").val() == ''){
                $("[name=admin_discount]").val(0);
            }
           
        var total = parseFloat($("[name=sub_total]").val())+parseFloat($("[name=delivery_charge]").val())-parseFloat($("[name=promo_discount]").val())-parseFloat($("[name=admin_discount]").val());
            $("[name=total]").val(total);
        }

        $("[name=delivery_charge],[name=promo_discount],[name=admin_discount]").on('change keyup',function () {
            
            getTotal();
        });



        function openOrderAddressModel(id) {
            $.ajax({
                url: "{!! route('get-user-by-param') !!}",
                type: 'GET',
                data: {
                    id : id,
                    _token: '{{ csrf_token() }}'
                },
                success: function( data ) {
                    $("#label-name").text(data.data.name);
                    window.delivery_locations = data.data.deliveryLocation;

                    var html = '<option value="0">create new</option>';
                    for (var i in delivery_locations){
                        html+="<option value='"+delivery_locations[i].id+"'>"+delivery_locations[i].name+"</option>";
                    }
                    $("[name=shipping_location]").html(html);
                
                },
                error: function( data, status, error ) {

                    new PNotify({
                        title: 'Error',
                        text: data.responseJSON.message,
                        type: "error",
                        styling: 'bootstrap3'
                    });
                }
            });
            $("#selectOrderAddress").modal('show');
        }
        
        function addAddress() {
            if($("#shipping_location").val() == 0){
                 if($("#name").val() == '' || $("[name=address]").val() == '') {
                    return false;
                 }
            }
            $.ajax({
                url: "{!! route('delivery_location.store') !!}",
                type: 'POST',
                data:  $('#modify-address-from').serialize()+'&user_id='+$("[name=user_id]").val()+'&_token={{ csrf_token() }}',
                success: function( data ) {
                    //console.log(data);
                    
                    if(data.data.zone_id == 0 || data.data.zone_id == 0){
                        $("#zone_id").val(data.data.zone_id);
                        $('#zone_id')
                        .find('option')
                        .remove()
                        .end()
                        .append('<option value="">There is no matching zone</option>')
                        .val('0');
                    }else{
                        //console.log('hi');
                            var zoneOption = <?php echo json_encode($zones) ?>;
                            /* $.each(zoneOption, function(key, value) {
                                 $('#zone_id')
                                    .append($("<option></option>")
                                    .attr("value",key)
                                    .text(value));
                            });*/
                            $('#zone_id').find('option').remove();
                                $('#zone_id')
                                    .append($("<option></option>")
                                    .attr("value",data.data.zone_id)
                                    .text(zoneOption[data.data.zone_id]));
                            
                            $("#zone_id").val(data.data.zone_id);
                           
                    }
                    //$("#delivery_address_id").val(data.data.diver);
                    $("#delivery_address_id_val").val(data.data.id);
                    if(data.data.driver != null){
                         $("#diver_id_val").val(data.data.driver.id);
                         $('#driver_id').find('option').remove();
                                $('#driver_id').append($("<option></option>")
                                    .attr("value",data.data.driver.id)
                                    .text(data.data.driver.name));
                            
                    }else{
                         $("#diver_id_val").val(0);
                    }
                    if(data.data.shoper != null){
                         $("#shoper_id_val").val(data.data.shoper.id);
                          $('#shopper_id').find('option').remove();
                                $('#shopper_id')
                                    .append($("<option></option>")
                                    .attr("value",data.data.shoper.id)
                                    .text(data.data.shoper.name));
                    }else{
                         $("#shoper_id_val").val(0);
                    }
                    if(data.data.vendor != null){
                         $("#vendor_id_val").val(data.data.vendor.id);
                          $('#vendor_id').find('option').remove();
                                $('#vendor_id')
                                    .append($("<option></option>")
                                    .attr("value",data.data.vendor.id)
                                    .text(data.data.vendor.name));
                    }else{
                         $("#vendor_id_val").val(0);
                    }
                    $("#zone_id_val").val(data.data.zone_id);
                    $("#delivery_charges_val").val(data.data.delivery_charges);
                    $("#add_product_model").removeAttr('disabled');
                    $("#delivery_date").removeAttr('readonly');
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: "success",
                        styling: 'bootstrap3'
                    });
                    $("#selectOrderAddress").modal('hide');

                },
                error: function( data, status, error ) {

                    new PNotify({
                        title: 'Error',
                        text: data.responseJSON.message,
                        type: "error",
                        styling: 'bootstrap3'
                    });
                }
            });
        }

        function showUserModel() {
            $("#addNewUser").modal('show');
        }
        function addNewUser() {
            var phone = $("[name=add_customer_phone]").val();
            var name = $("[name=add_customer_name]").val();
            var country_phone_code = $("[name=country_phone_code]").val();

            $.ajax({
                url: "{!! route('user.store') !!}",
                type: 'POST',
                data: {
                    phone_number : phone,
                    name : name,
                    phone_code : country_phone_code,
                    _token: '{{ csrf_token() }}'
                },
                success: function( data ) {
                    console.log(data);
                   
                    var type = "success";
                    var title = "success";
                    if(data.error == 'true'){
                        type = "error";
                        title = "Error";
                    }else{
                        $("[name=customer_phone]").val(phone);
                        $("[name=customer_name]").val(name);
                        $("[name=user_id]").val(data.user_id);
                        $("#btn_add_model").removeAttr('disabled');
                        $("#addNewUser").modal('hide');
                    }
                     new PNotify({
                        title: title,
                        text: data.message,
                        type: type,
                        styling: 'bootstrap3'
                    });
                   
                   

                },
                error: function( data, status, error ) {

                    new PNotify({
                        title: 'Error',
                        text: data.responseJSON.message,
                        type: "error",
                        styling: 'bootstrap3'
                    });

                }
            });
        }
        function getUserByPhone(phone_number){
            if($("[name=customer_phone]").val()== ''){
                new PNotify({
                        title: 'Error',
                        text: 'Please enter phone number',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                return false;
            }
            $.ajax({
                url: "{!! route('get-user-by-param') !!}",
                type: 'GET',
                data: {
                    phone_number : phone_number,
                    _token: '{{ csrf_token() }}'
                },
                success: function( data ) {
                     $("#btn_add_model").removeAttr('disabled');
                    var delivery_locations = data.data.delivery_location;
                    console.log(data);
                    $("[name=customer_name]").val(data.data.name);
                    $("[name=user_id]").val(data.data.id);
                    var html ;
                     html+="<option value=''>Select delivery address</option>";
                    for (var i in delivery_locations){
                        html+="<option value='"+delivery_locations[i].id+"'>"+delivery_locations[i].name+"</option>";
                    }
                    $("[name=delivery_address_id]").html(html);
                    $('#zone_id').find('option').remove();
                    $('#vendor_id').find('option').remove();
                    $('#shopper_id').find('option').remove();
                    $('#driver_id').find('option').remove();
                    $("#add_product_model").attr('disabled','disabled');

                },
                error: function( data, status, error ) {

                    new PNotify({
                        title: 'Error',
                        text: data.responseJSON.message,
                        type: "error",
                        styling: 'bootstrap3'
                    });
                }
            });
        }

    </script>
@endpush
