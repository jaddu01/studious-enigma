<?php

/**
 * @Author: abhi
 * @Date:   2021-09-21 00:52:09
 * @Last Modified by:   Younet Digital Life
 * @Last Modified time: 2021-10-26 03:25:57
 */
?>
@extends('admin.layouts.app')

@section('title', ' Add Order |')
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
    <link href="{{asset('public/css/pos/ui.css')}}" rel="stylesheet"/><!--pos-design-->
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
       ffhd
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
                     <!-- <a href="{{url('admin/order')}}" id="export_button" class="btn btn-primary">Export Invoice AS PDF</a> -->
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

                            {!!  Form::select('country_phone_code',['91'],null, array('class' => 'form-control select2-multiple','id'=>'country_phone_code')) !!}
                            {{ Form::filedError('country_phone_code') }}
                        </div>
                    </div>
                    <div class="row item form-group{{ $errors->has('add_customer_phone') ? ' has-error' : '' }}">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Customer Phone <span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            {!!  Form::text('add_customer_phone', null, array('placeholder' => 'Customer Phone','class' => 'form-control priceNum col-md-7 col-xs-12','pattern'=>'[789][0-9]{9}','maxLength'=>10 )) !!}
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
                    <!-- <div class="row item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load Zone<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <p class="pname"><span id="label-zone"></span></p>
                        </div>
                    </div> -->
                    <div class="row item form-group{{ $errors->has('vendor_product_id') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                             {!!  Form::select('vendor_product_id',[], null, array('class' => 'form-control select2-multiple  col-md-10 col-xs-12','placeholder'=>'product','id'=>'vendor_product_id','required','style'=>'width:300px !important')) !!}
                            <!-- {!!  Form::select('vendor_product_id',[], null, array('placeholder' => 'Admin discount','class' => 'form-control select2-multiple col-md-7 col-xs-12' )) !!} -->
                            @if ($errors->has('vendor_product_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('vendor_product_id') }}</strong>
                                </span>
                            @endif
                            <span>
                                <img src="{{url('/public/images/barcode-scanner.png')}}" width="30px" onclick="selectBarcode()" />
                                <input type="text" name="tag" id="focus" placeholder="Use handheld RFID scanner">
                            </span>
                        </div>
                    </div>

                    <div class="row item form-group" id="image" style="display:none;">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image">Image
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <img src="" id="product-image" width="100px">
                        </div>

                    </div>

                    <div class="row item form-group{{ $errors->has('qty') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Qty <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::number('qty', 1, array('placeholder' => 'qty','class' => 'form-control col-md-7 col-xs-12', 'min'=>'1', 'autocomplete'=>'off','id'=>'qty','onkeyup'=>'getTotalPrice()')) !!}
                            @if ($errors->has('total'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('qty') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                    
                    <div class="row item form-group{{ $errors->has('qty') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">Price <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('price', 0, array('placeholder' => 'price','class' => 'form-control col-md-7 col-xs-12', 'autocomplete'=>'off','id'=>'price','onchange'=>'getTotalPrice()')) !!}
                            @if ($errors->has('price'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('price') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                    <div class="row item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gst">GST
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="gst">
                            0
                        </div>

                    </div>
                    <div class="row item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="total_price">Total Price
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="total_price">
                           0 
                        </div>

                    </div>
                    <div id="product-details"></div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" style="margin-bottom: 5px;" onclick="addNewProduct()" class="btn btn-default" >Add</button>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div id="editProduct" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal-title-edit-product">Edit Product</h4>
                </div>
                <div class="modal-body">
                    <div class="row item form-group{{ $errors->has('qty') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Qty <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::number('qty', 1, array('placeholder' => 'qty','class' => 'form-control col-md-7 col-xs-12', 'min'=>'1', 'autocomplete'=>'off','id'=>'edit-qty','onkeyup'=>'getTotalEditPrice()')) !!}
                            @if ($errors->has('total'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('qty') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                    
                    <div class="row item form-group{{ $errors->has('qty') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">Price <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('price', 0, array('placeholder' => 'price','class' => 'form-control col-md-7 col-xs-12', 'autocomplete'=>'off','id'=>'edit-price','onchange'=>'getTotalEditPrice()')) !!}
                            @if ($errors->has('price'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('price') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                    <div class="row item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gst">GST
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="edit-gst">
                            0
                        </div>

                    </div>
                    <div class="row item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="total_price">Total Price
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="edit_total_price">
                           0 
                        </div>

                    </div>
                    <div id="product-details"></div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" style="margin-bottom: 5px;" onclick="updateProduct()" class="btn btn-default" >Update</button>
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
        var products = [];
        var currentProduct = [];
        var number_of_orders = 0;
        var order_sub_total = 0;
        var order_total = 0;
        var delivery_charge = 0;
        var total_gst = 0;
        var total_payment = 0;
        var total_changes = 0;
        var add_to_wallet = 0;
        var zone_id = 0;
        var shopper_id = 0;
        var driver_id = 0;
        var user_id = 0;
        var sodexo_charges = 0;
        var order_sub_total = 0;
        var user = [];
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
        // $('[name=promo_discount]').bind('keyup paste', function(){
        //     this.value = this.value.replace(/[^0-9].[0-9][0-9]/g, '');
        // });
        // $('[name=admin_discount]').bind('keyup paste', function(){
        //     this.value = this.value.replace(/[^0-9].[0-9][0-9]/g, '');
        // });
        
        
        /*$(".chosen").chosen();*/
        $(function () {
            $("#btn_add_model").attr('disabled','disabled');
            $("#add_product_model").attr('disabled','disabled');
            $("#submit-manual-order").attr('disabled','disabled');
            $("#submit-print-manual-order").attr('disabled','disabled');
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
            $('#customer_name').select2({
                placeholder: "Load Customer",
                allowClear: true
            });
            $('#vendor_product_id').select2({
                placeholder: "load product",
                allowClear: true
            });
            $('#vendor_id').select2({
                placeholder: "load store",
                allowClear: true
            });
            var delAdd = $("#delivery_address_id").val();
            if(delAdd != ''){
                $('.datepicker' ).datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    startDate: '-0m',
                     endDate: "+2d",
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

            $('input[type=radio][name=payment_mode_id]').change(function() {
                if (this.value == 4) {
                    sodexo_charges = (order_total/100)*3.50;
                    order_total = Number(order_total)+Number(sodexo_charges);
                    order_total = order_total.toFixed(2);
                    sodexo_charges = sodexo_charges.toFixed(2);
                    $('[name=total]').val(order_total);
                    $('[name=sodexo_charges]').val(sodexo_charges);
                }
                else {
                    if(sodexo_charges>0) {
                        //sodexo_charges = (order_total/100)*3.50;
                        //order_total = Number(order_total) - Number(sodexo_charges);
                        getOrderSummery();
                        sodexo_charges = 0;
                        $('[name=sodexo_charges]').val(0.00);
                    } 
                }
                getChanges();
                //console.log(order_total);
                //console.log(sodexo_charges);
            });
            $("#delivery_address_id").change(function (){
                if($(this).val()==""){
                        $("[name=delivery_charge]").val(0.00);
                        getOrderSummery();
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
                        $("[name=delivery_charge]").val(data.data.delivery_charges);
                        $("#delivery_charges_val").val(data.data.delivery_charges);
                        getOrderSummery();
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
            $("#customer_name").change(function (){
                var user_id = $(this).val();
                $.ajax({
                url: "{!! route('pos-get-user-by-param') !!}",
                type: 'GET',
                data: {
                    id : user_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function( data ) {
                    user = data.data;
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
                    $("[name=customer_phone]").val(data.data.phone_number);
                    /*$('#zone_id').find('option').remove();
                    $('#vendor_id').find('option').remove();
                    $('#shopper_id').find('option').remove();
                    $('#driver_id').find('option').remove();*/
                    $("#add_product_model").attr('disabled','disabled');
                    var label = document.getElementById('payment_mode_id_3');
                    console.log(label);
                    label.innerHTML = 'Wallet ('+user.wallet_amount+')';
                    showProductTable();

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
            });

              /*$("#delivery_address_id").change(function (){
                //alert($(this).val());
                 $("#delivery_date").val("");
                 $('#added_product_ids').val("");
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
                          //  console.log('hi');
                            var zoneOption = <?php echo json_encode($zones) ?>;
                          //  console.log(zoneOption[data.vendor.zone_id]);
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
                            var vandata = data.data.vendor;
                            vandata.forEach(function(item){
                                $('#vendor_id')
                                    .append($("<option></option>")
                                    .attr("value",item.id)
                                    .text(item.name));
                            });
                           
                             $('#shopper_id').find('option').remove();
                                $('#shopper_id')
                                    .append($("<option></option>")
                                    .attr("value",data.data.shoper.id)
                                    .text(data.data.shoper.name));
                            
                        $("#zone_id").val(data.data.zone_id);
                        $("#driver_id").val(data.data.driver.id);
                        $("#vendor_id").val(data.data.vendor.id);
                        $("#shopper_id").val(data.data.shoper.id);
                        $("[name=delivery_charge]").val(data.data.delivery_charges);
                        $("#delivery_charges_val").val(data.data.delivery_charges);
                        
                         
                           
                    }
                    
                    $("[name=delivery_date]").removeAttr('readonly'); 
                        $('.datepicker' ).datepicker({
                            autoclose: true,
                            format: 'yyyy-mm-dd',
                            startDate: '-0m',
                             endDate: "+2d",
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

              });*/
              
             
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
                        showProductTable();

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
                       // console.log(data.data);
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
                             endDate: "+2d",
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
                     
                if (typeof product[i].product != null) {
                         html+="<option value='"+product[i].product_id+"'>"+product[i].product.name+"</option>";
                     }
                 }
                     $("[name=vendor_product_id]").html(html);
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
            $("#addProduct").modal('show');
        }
         function Remove(button) {
            var rowCount = $('#users-table tr').length -1;
           
            var row = $(button).closest("TR");
            console.log()
            var row_tprice = row.find('.t-price').text();
            var row_offerval = row.find('.offer-val').text();
            var row_pname = row.find('.p-name').text();
            var row_id = row.find('.t-id').text();
           // alert(row_id);
            //var name = $("TD", row).eq(0).html();
            var total_order_before = $("[name=total_order]").val();
            var total_offer_before = $("[name=total_offer]").val();
            var number_orders_before = $("[name=number_of_orders]").val();


            var sub_total_before = $("[name=sub_total]").val();
            if (confirm("Do you want to delete: " + row_pname)) {

                var added_product_ids = $('#added_product_ids').val();
                 var res = added_product_ids.split(",");
                    res.forEach(function(ele){
                       if(ele==row_id){ 
                           var ret = added_product_ids.replace(','+row_id,'');
                           $('#added_product_ids').val(ret);
                       }
                  });
                
                var table = $("#users-table")[0];
                if(rowCount == 1){
                $("#users-table").hide();
                }
                //Delete the Table row using it's Index.
                table.deleteRow(row[0].rowIndex);
                $("[name=total_order]").val();
                $("[name=total_offer]").val(parseFloat(total_offer_before) - parseFloat(row_offerval)); 
                $("[name=number_of_orders]").val(parseInt(number_orders_before) - 1);
               var subTotalVal =parseFloat(sub_total_before) - parseFloat(row_tprice);
               $("[name=sub_total]").val(parseFloat(subTotalVal).toFixed(2));
                //console.log(subTotalVal);
                var mim_amount_for_free_delivery = $("#mim_amount_for_free_delivery").val();
              //  console.log(mim_amount_for_free_delivery);
             if(parseFloat(subTotalVal) > parseFloat(mim_amount_for_free_delivery)){
                    $("[name=delivery_charge]").val(0);
                }else{
                    $("[name=delivery_charge]").val($("#delivery_charges_val").val());
                }
                getTotal();
            }

        };

        function addNewProduct() {
            getTotalPrice();
            var html='';
            html+='<tr id="'+currentProduct['product_id']+'">';
            html+='<td>'+currentProduct['product_id']+'</td>';
            html+='<td><img src="'+currentProduct['image']+'" width="100px"></td>';
            html+='<td>'+currentProduct['name']+'</td>';
            html+='<td>'+currentProduct['qty']+'</td>';
            html+='<td>'+currentProduct['price']+'</td>';
            html+='<td>'+currentProduct['total_price']+'</td>';
            html+='<td><button class="btn btn-success" type="button" onclick="editProduct('+currentProduct['product_id']+');">Edit</button><button class="btn btn-danger remove-x" type="button" onclick="removeProduct('+currentProduct['product_id']+');">Remove</button></th></td>';    
            html+='</tr>';
            html = html.replace('','undefined');
            $('#product-data').append(html);
            $('#addProduct').modal('hide');
            $('#qty').val(0);
            $('#image').hide();
            $('#product-image').prop('src','');
            $('#price').val(0);
            $('#gst').html(0);
            $('#total_price').html(0);
            var total_price = currentProduct['total_price'];
            var gst = currentProduct['gst'];
            var total_gst = (total_price/100)*gst;
            console.log(total_gst);
            total_gst = total_price - total_gst;
            total_gst = total_price - total_gst;
            console.log(total_gst);
            currentProduct['total_gst'] = total_gst;
            products.push(currentProduct);
            currentProduct = [];
            getOrderSummery();

        }

        function updateProduct() {
            getTotalEditPrice();
            $('#'+currentProduct['product_id']).remove();
            var html='';

            html+='<tr id="'+currentProduct['product_id']+'">';
            html+='<td>'+currentProduct['product_id']+'</td>';
            html+='<td><img src="'+currentProduct['image']+'" width="100px"></td>';
            html+='<td>'+currentProduct['name']+'</td>';
            html+='<td>'+currentProduct['qty']+'</td>';
            html+='<td>'+currentProduct['price']+'</td>';
            html+='<td>'+currentProduct['total_price']+'</td>';
            html+='<td><button class="btn btn-success" type="button" onclick="editProduct('+currentProduct['product_id']+');">Edit</button><button class="btn btn-danger remove-x" type="button" onclick="removeProduct('+currentProduct['product_id']+');">Remove</button></th></td>';    
            html+='</tr>';
            html = html.replace('','undefined');
            $('#product-data').append(html);
            $('#editProduct').modal('hide');
            $('#edit-qty').val(0);
            $('#image').hide();
            $('#product-image').prop('src','');
            $('#edit-price').val(0);
            $('#edit-gst').html(0);
            $('#edit-total_price').html(0);
            var total_price = currentProduct['total_price'];
            var gst = currentProduct['gst'];
            var total_gst = (total_price/100)*gst;
            console.log(total_gst);
            total_gst = total_price - total_gst;
            total_gst = total_price - total_gst;
            console.log(total_gst);
            currentProduct['total_gst'] = total_gst;
            products.push(currentProduct);
            currentProduct = [];
            getOrderSummery();

        }

        function removeProduct(id) {
            index = getProductIndex(id);
            $('#'+id).remove();
            products.splice(index, 1);
            getOrderSummery();
        }

        function editProduct(id) {
            index = getProductIndex(id);
            currentProduct = products[index];
            console.log(products);
            console.log(index);
            console.log(id);
            console.log(products[index]);
            console.log(products[index].name);
            console.log(products[index]['name']);
            console.log(currentProduct[index]);
            //console.log(currentProduct[index]);
            //console.log(products[index]);
            //console.log(currentProduct[index]['name']);
            $('#modal-title-edit-product').html('Edit Product ::'+products[index].name);
            $('#edit-qty').val(products[index].qty);
            $('#edit-price').val(products[index].price);
            $('#edit_total_price').html(products[index].total_price);

            $('#editProduct').modal('show');
        }

        function getProductIndex(id) {
            console.log(products);
            return products.findIndex((item) => item.product_id === ''+id+'')
        }

        function getOrderSummery() {
            order_sub_total = 0;
            order_total = 0;
            total_gst = 0;
            number_of_orders = 0;
            order_discount = 0;
            products.map((value,index)=>{
                console.log(value.total_discount);;
                /*order_sub_total = parseFloat(order_sub_total)+parseFloat(value.total_price);
                order_total = parseFloat(order_sub_total)+parseFloat(delivery_charge);
                total_gst = parseFloat(total_gst)+parseFloat(value.total_gst);*/
                order_sub_total = order_sub_total+value.total_price;
                order_total = order_sub_total+delivery_charge;
                total_gst = total_gst+value.total_gst;;
                order_discount = order_discount+value.total_discount;
            });
            order_sub_total = Math.round(order_sub_total).toFixed(2);
            order_total = order_total+delivery_charge;
            order_total = Math.round(order_total).toFixed(2);
            total_gst = total_gst.toFixed(2);
            order_discount = Math.round(order_discount).toFixed(2);
            number_of_orders = Object.keys(products).length
            $('[name=sub_total]').val(order_sub_total);
            $('[name=total]').val(order_total);
            $('[name=total_gst]').val(total_gst);
            $('[name=number_of_orders]').val(number_of_orders);
            $('[name=order_discount]').val(order_discount);
            $('[name=delivery_charge]').val(delivery_charge);
            console.log(order_sub_total);
            console.log(order_total);
            console.log(total_gst);
            console.log(delivery_charge);
        }

        function getChanges() {
            var total_payment = $('[name=total_payment]').val();

            total_changes = total_payment - order_total;
            if(total_changes > 0) {
                total_changes = total_changes.toFixed(2);    
            } else {
                total_changes = 0.00;
            }
            $('[name=total_changes]').val(total_changes);
            $('#submit-manual-order').removeAttr('disabled');
            $('#submit-print-manual-order').removeAttr('disabled');

        }

        function addOrder(is_print=false) {
            var order_items = [];
            products.map((value,index)=>{
                order_items.push({id:value.id,product_id:value.product_id,price:value.price,total_price:value.total_price,qty:value.qty,total_price:value.total_price});
            });
            var vendor_id = $("[name=vendor_id]").val();
            zone_id = $("[name=zone_id]").val();
            shopper_id = $("[name=shopper_id]").val();
            user_id = $("[name=user_id]").val();
            var allDiscount = 0;
            var subTotalVal = $("[name=sub_total]").val();
            var customer_phone = $("[name=customer_phone]").val();
            var delivery_address_id = $("#delivery_address_id").val();
            if ($('input[name=add_to_wallet]:checked').length>0) {
                add_to_wallet = 1;
            } else {
                add_to_wallet = 0;
            }
            console.log(add_to_wallet);
            var payment_mode_id = $('input[name=payment_mode_id]:checked').val();
            console.log(payment_mode_id);
            //if(delivery_address_id == "" || delivery_address_id == null || customer_phone == "" || customer_phone == null){
            if(customer_phone == "" || customer_phone == null){
                if(customer_phone == "" || customer_phone == null){
                    new PNotify({
                        title: 'Error',
                        text: 'Please select customer first',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                 return false;
                }/* else if(delivery_address_id == "" || delivery_address_id == null) {
                    new PNotify({
                        title: 'Error',
                        text: 'Please select delivery address first',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                 return false;
                }*/
            } else {
                if(allDiscount > subTotalVal){
                    new PNotify({
                        title: 'Error',
                        text: 'Discount can not be greater than sub total',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                    return false;
                } else {
                    $.ajax({
                        url: "{!! route('pos-order.store') !!}",
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            vendor_id : vendor_id,
                            _token: '{{ csrf_token() }}',
                            order_items:order_items,
                            order_sub_total:order_sub_total,
                            order_total:order_total,
                            delivery_charge:delivery_charge,
                            total_gst:total_gst,
                            total_payment:total_payment,
                            total_changes:total_changes,
                            customer_phone:customer_phone,
                            delivery_address_id:delivery_address_id,
                            is_print:is_print,
                            allDiscount:allDiscount,
                            add_to_wallet:add_to_wallet,
                            payment_mode_id:payment_mode_id,
                            delivery_address_id:delivery_address_id,
                            zone_id:zone_id,
                            shopper_id:shopper_id,
                            user_id:user_id,
                            sodexo_charges:sodexo_charges
                        },
                        beforeSend: function() {
                            $('#loader').css('display','block');
                        },
                        success: function( data ) {
                             console.log(data);
                             $('#loader').css('display','none');
                            var type ='success';
                            if(data.error==true){
                                 type = 'error';
                                 title="Error"
                            }else{
                                title="Success"
                                 
                                 $('#loader').css('display','none');

                                $("#order_success_msg").text(data.data.msg);
                                $("#order_success_code").text(data.data.order_code);
                                $("#order_success_id").val(data.data.order_id);
                                var url = "{{url('admin/order/pdfdownload')}}/"+data.data.order_id;
                                $("#export_button").attr("href",url);
                                $("#orderSuccessfull").modal('show');
                                if(is_print==true) {
                                    var print_url = "{{url('admin/pos/order/print')}}/"+data.data.order_id;
                                    window.open(print_url,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=350,height=900')
                                    //window.open(window.location.href = print_url, '_blank');
                                }
                            }
                              $('#loader').css('display','none');
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
            console.log(is_print);
        }
        
        function addNewProductOld() {
                    var data  = window.add_product_data;
                    var index = $("[name=vendor_product_id]").find(":selected").index();
                    var qty = $("[name=qty]").val();
                     var added_product_ids = $('#added_product_ids').val();
                     var flag=0;
                       if(added_product_ids==0){ 
                        $('#added_product_ids').val(data[index].id); }
                    else{ 
                        var res = added_product_ids.split(",");
                            res.forEach(function(ele){
                               if(ele==data[index].id){
                                    new PNotify({
                                    title: 'Error',
                                    text: 'Product already added . To update quantity remove and add again',
                                    type: "error",
                                    styling: 'bootstrap3'
                                    }); 
                                    flag=1;
                                    $("#addProduct").modal('hide');
                                 }
                            });
                           // alert(flag);
                        }
                    if(flag==0){
                        $('#added_product_ids').val(added_product_ids+','+data[index].id); 
                    
                    if((data[index].per_order>0) && (qty>data[index].per_order)){ 
                          $('#loader').css('display','none');
                    new PNotify({
                    title: 'Error',
                    text: 'Max Quantity for this product is - '+data[index].per_order,
                    type: "error",
                    styling: 'bootstrap3'
                    });
                    //  alert('Max Quantity for this product is - '+data[index].per_order);
                    }
                    else{    $("#users-table").show();
            //console.log(data[index]);

            var total_price =(data[index].offer_price)*parseInt($("[name=qty]").val());
            var data_offer_price =  (data[index].offer_price);
            if(data[index].product.image != null){
            $("tbody").append('<tr><th><input type="hidden" value="'+data[index].id+'" name="product_ids[]"><span class="t-id">'+data[index].id+'</th><th class="t-price">'+parseFloat(total_price).toFixed(2)+'</span></th><th class="t-mts">'+data[index].product.measurement_value+' '+data[index].product.measurement_class.name+'</th><th>'+parseFloat(data_offer_price).toFixed(2)+'</th><th>'+data[index].is_offer+'</th><th class="offer-val">'+((data[index].offer !==null)?data[index].offer.offer_value:  0)+((data[index].offer !==null)?data[index].offer.offer_type:"")+'</th><th><input type="hidden" value="'+$("[name=qty]").val()+'" name="product_qtys['+data[index].id+']">'+ $("[name=qty]").val()+'</th><th><img src="'+data[index].product.image.name+'" height="75" width="75"></th><th class="p-name">'+data[index].product.name+'</th><th><button class="btn btn-danger remove-x" type="button" onclick="Remove(this);">Remove</button></th></tr>');
		    }else{
			$("tbody").append('<tr><th><input type="hidden" value="'+data[index].id+'" name="product_ids[]"><span class="t-id">'+data[index].id+'</th><th class="t-price">'+parseFloat(total_price).toFixed(2)+'</span></th><th class="t-mts">'+data[index].product.measurement_value+' '+data[index].product.measurement_class.name+'</th><th>'+parseFloat(data_offer_price).toFixed(2)+'</th><th>'+data[index].is_offer+'</th><th class="offer-val">'+((data[index].offer !==null)?data[index].offer.offer_value:  0)+((data[index].offer !==null)?data[index].offer.offer_type:"")+'</th><th><input type="hidden" value="'+$("[name=qty]").val()+'" name="product_qtys['+data[index].id+']">'+ $("[name=qty]").val()+'</th><th>No image</th><th class="p-name">'+data[index].product.name+'</th><th><button class="btn btn-danger remove-x" type="button" onclick="Remove(this);" >Remove</button></th></tr>');
		    }
        var tp = 0;
        var to = 0;
            $(".t-price").each(function(){
               tp += parseFloat($(this).text());
            });
            $(".offer-val").each(function(){
               to += parseFloat($(this).text());
            });
             window.total_order =tp;
             var tableRowCount = $('#users-table tr').length - 1;
             window.number_of_orders = tableRowCount;
             //alert(number_orders_before);
            
            //window.total_offer+=parseInt((data[index].offer !==null)?data[index].offer.offer_value:  0);
            window.total_offer=to;
            window.total = parseFloat(window.total_order)+parseFloat(window.delivery_charge);
            $("[name=total_order]").val(parseFloat(window.total_order).toFixed(2));
            $("[name=total_offer]").val(parseFloat(window.total_offer).toFixed(2));
            $("[name=number_of_orders]").val(parseInt(window.number_of_orders));
            var subTotalVal = parseFloat(window.total_order).toFixed(2);
            var mim_amount_for_free_delivery = $("#mim_amount_for_free_delivery").val();

             if(parseFloat(subTotalVal) > parseFloat(mim_amount_for_free_delivery)){
                    new PNotify({
                        title: 'Success',
                         text: 'Mimimim Amount For Free Delivery Reached',
                        type: "Success",
                        styling: 'bootstrap3'
                    });
                    $("[name=delivery_charge]").val(0);
                }
            $("[name=sub_total]").val(subTotalVal);
              var DC =  $("[name=delivery_charge]").val();
           
            if( DC != 0){
               // $("[name=total]").val(parseFloat($("[name=sub_total]").val())+parseFloat($("[name=total_offer]").val())-parseFloat($("[name=promo_discount]").val())-parseFloat($("[name=admin_discount]").val())+parseFloat(DC));
                var total =parseFloat($("[name=sub_total]").val())+parseFloat(DC);
               
            }else{
                //$("[name=total]").val(parseFloat($("[name=sub_total]").val())+parseFloat($("[name=total_offer]").val())+/parseFloat($("[name=delivery_charge]").val())-parseFloat($("[name=promo_discount]").val())-parseFloat($("[name=admin_discount]").val()));
                var total =parseFloat($("[name=sub_total]").val())+parseFloat($("[name=delivery_charge]").val());
            }
             $("[name=total]").val(parseFloat(total).toFixed(2));
             $("#submit-manual-order").removeAttr('disabled');
               $('#loader').css('display','none');
             new PNotify({
                        title: 'Success',
                        text: 'Product Added ',
                        type: "success",
                        styling: 'bootstrap3'
                    });
             
                   $("[name=qty]").val(1);
                    $("#addProduct").modal('hide');
        }
    }
    }
        
        function getTotal() {

           // var total = parseFloat($("[name=sub_total]").val())+parseFloat($("[name=total_offer]").val())+parseFloat($("[name=delivery_charge]").val())-parseFloat($("[name=promo_discount]").val())-parseFloat($("[name=admin_discount]").val());
           /*if($("[name=delivery_charge]").val() == ''){
                $("[name=delivery_charge]").val(0);
            }*/
            // if($("[name=promo_discount]").val() == ''){
            //     $("[name=promo_discount]").val(0);
            // }
            // if($("[name=admin_discount]").val() == ''){
            //     $("[name=admin_discount]").val(0);
            // }
            if($("[name=delivery_charge]").val() == ''){
                delivery_charge = 0;
            } else {
                delivery_charge = $("[name=delivery_charge]").val();
            }
           
        

        var total = parseFloat($("[name=sub_total]").val())+parseFloat(delivery_charge);
        $("[name=total]").val(parseFloat(total).toFixed(2));
        }

        $("[name=delivery_charge]").on('change keyup',function () {
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
                      $('#loader').css('display','none');
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
                    $('#loader').css('display','none');
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: "success",
                        styling: 'bootstrap3'
                    });
                    $("#selectOrderAddress").modal('hide');

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
                    $('#loader').css('display','none');
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
        function getUserByPhone(phone_number){
            if($("[name=customer_phone]").val()== ''){
                $('#loader').css('display','none');
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
                    //$("[name=customer_name]").val(data.data.name);
                    $("[name=user_id]").val(data.data.id);
                    $("[name=customer_name]").val(data.data.id).trigger("change");
                    var html ;
                     html+="<option value=''>Select delivery address</option>";
                    for (var i in delivery_locations){
                        html+="<option value='"+delivery_locations[i].id+"'>"+delivery_locations[i].name+"</option>";
                    }
                    $("[name=delivery_address_id]").html(html);
                    /*$('#zone_id').find('option').remove();
                    $('#vendor_id').find('option').remove();
                    $('#shopper_id').find('option').remove();
                    $('#driver_id').find('option').remove();*/
                    $("#add_product_model").attr('disabled','disabled');
                    showProductTable();

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
        function showProductTable() {
            var zone_id = $("[name=zone_id]").val();
            var vendor_id = $("[name=vendor_id]").val();
            var  shopper_id = $("[name=shopper_id]").val();
            //alert(zone_id+' - '+vendor_id+' - '+shopper_id);
            //setTimeout(()=> {
                if(zone_id!='' && vendor_id!='' && zone_id!=''){
                    $('.product-div').show();
                }
            //},100);
        }
        function getVendorProductList() {
            var vendor_id = $("#vendor_id").val();
            var html = '';
             $.ajax({
                 url: "{!! route('pos-get-vendor-product') !!}",
                 type: 'GET',
                 data: {
                     vendor_id : vendor_id,
                     _token: '{{ csrf_token() }}'
                 },
                 success: function( data ) {
                    var $select = $("[name=vendor_product_id]");
                    var dataArray = [];
                    if(Object.keys(data.data).length>0) {
                        html+='<option>Select product<option>';
                        $.each(data.data,(index,value)=>{
                            //console.log(index+' - '+value);
                            html+='<option id="'+index+'">'+value+'<option>';
                            $select.append(new Option(value, index, true, true));
                            dataArray.push(index);
                        });
                        //console.log(dataArray);
                        $select.val(dataArray).trigger('change');

                    }
                    $select.prepend('<option selected=""></option>').select2({placeholder: "Select Product"});
                    html = html.replace('undefined','');
                    //$("[name=vendor_product_id]").html(html);
                    $('#addProduct').modal('show');
                    /*var product = window.add_product_data = data.data;
                     var html ;
                     for (var i in product){
                     
                if (typeof product[i].product != null) {
                         html+="<option value='"+product[i].product_id+"'>"+product[i].product.name+"</option>";
                     }
                 }
                     $("[name=vendor_product_id]").html(html);*/
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
        function getProductDetail(id) {
            var vendor_id = $("#vendor_id").val();
            var html = '';
             $.ajax({
                 url: "{!! route('pos-get-vendor-product-detail') !!}",
                 type: 'GET',
                 data: {
                     product_id : id,
                     vendor_id : vendor_id,
                     _token: '{{ csrf_token() }}'
                 },
                 success: function( data ) {
                    console.log(data.data);
                    var qty = data.data.max_per_order_qty;
                    var price = data.data.price;
                    var image = data.data.product.image.name
                    if(data.data.qty<=data.data.max_per_order_qty) {
                        qty=data.data.qty;
                    }
                    var total_price = price*1;
                    var gst = data.data.product.gst;
                    if(gst=='' || gst==null) {
                        gst = 0;
                    }
                    $('#qty').val(1);
                    $('#qty').prop('max',qty);
                    $('#price').val(price);
                    $('#image').show();
                    $('#product-image').prop('src',image);
                    $('#total_price').html(total_price);
                    $('#gst').html(gst);
                    currentProduct['image'] = image;
                    currentProduct['gst'] = gst;
                    currentProduct['product_id'] = id;
                    currentProduct['name'] = data.data.product.name;
                    currentProduct['id'] = data.data.id;
                    currentProduct['product_price'] = data.data.price;

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
        function getTotalEditPrice(){
            var qty = $('#edit-qty').val();
            var price = $('#edit-price').val();
            var total_price = qty*price;
            var total_discount = (currentProduct['product_price']*qty)-total_price;
            currentProduct['qty'] = qty;
            currentProduct['price'] = price;
            currentProduct['total_price'] = total_price;
            currentProduct['total_discount'] = total_discount;
            $('#edit_total_price').html(total_price);
            console.log(currentProduct);
        }

        function getTotalPrice(){
            var qty = $('#qty').val();
            var price = $('#price').val();
            var total_price = qty*price;
            var total_discount = (currentProduct['product_price']*qty)-total_price;
            currentProduct['qty'] = qty;
            currentProduct['price'] = price;
            currentProduct['total_price'] = total_price;
            currentProduct['total_discount'] = total_discount;
            $('#total_price').html(total_price);
            console.log(currentProduct);
        }
        $('[name=vendor_product_id]').on('select2:selecting', function(e) {
            getProductDetail(e.params.args.data.id);
        });
        function getVendorProduct() {
            var vendor_id = $("#vendor_id").val();
            var html = '';
             $.ajax({
                 url: "{!! route('pos-get-vendor-product') !!}",
                 type: 'GET',
                 data: {
                     vendor_id : vendor_id,
                     _token: '{{ csrf_token() }}'
                 },
                 success: function( data ) {
                    if(Object.keys(data.data).length>0) {
                        html+='<tr>';
                        html+='<td>';
                        html+='<select name="product_id[" class="form-control select2-multiple product_ids">';
                        $.each(data.data,(index,value)=>{
                            //console.log(index+' - '+value);
                            html+='<option>'+value+'<option>';
                        });
                        html+='</select>';
                        html+='</td>';
                        html+='<td></td>';
                        html+='<td></td>';
                        html+='<td></td>';
                        html+='<td></td>';
                        html+='<td></td>';
                        html+='<td></td>';
                        html+='<td></td>';
                        html+='<td></td>';
                        html+='<td></td>';
                        html+='</tr>';
                    }
                    html = html.replace('undefined','');
                    $('#product-data').append(html);
                    $(".product_ids").select2({

                    });
                    /*var product = window.add_product_data = data.data;
                     var html ;
                     for (var i in product){
                     
                if (typeof product[i].product != null) {
                         html+="<option value='"+product[i].product_id+"'>"+product[i].product.name+"</option>";
                     }
                 }
                     $("[name=vendor_product_id]").html(html);*/
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
    </script>
    <script type="text/javascript">
        txt = '';
        function selectBarcode() {
            if (txt != $("#focus").val()) {
                setTimeout('use_rfid()', 1000);
                txt = $("#focus").val();
            }
            $("#focus").select();
            //setTimeout('selectBarcode()', 1000);
            //txt = '8901207025365';
            if(txt!='') {
                var vendor_id = $("#vendor_id").val();
                $.ajax({
                     url: "{!! route('pos-get-barcode-product') !!}",
                     type: 'GET',
                     data: {
                        vendor_id : vendor_id,
                         barcode : txt,
                         _token: '{{ csrf_token() }}'
                     },
                     success: function( data ) {
                        console.log(data);
                        console.log(data.data.id);
                        //$('[name=vendor_product_id]').select2().select2('val',data.data.id);
                        $("#vendor_product_id").select2().val(data.data.id).trigger("change");
                        getProductDetail(data.data.id);
                     }
                 });
            }

        }
        $(document).ready(function () {
            setTimeout(selectBarcode(),1000); 
        });

    </script>
@endpush
