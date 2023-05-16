@extends('admin.layouts.pdf')


@push('css')
    <link href="{{asset('css/bootstrap-toggle.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">
						<div class="border_bottom" >
                            <h1 style="text-align:center"> Invoice </h1>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Customer Name : </label>
                                {{isset($orders_details->user->name) ? $orders_details->user->name: ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " > Coustomer Phone: </label>
                             {{ $orders_details->User->phone_code}} - {{ $orders_details->User->phone_number}}
                                <hr>
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Order Code : </label>
                                {{$orders_details->order_code}}
                                <hr>
                            </div>
                           
                            <div class="item form-group">
                                <label class="control-label col-md-2 " > Time Slot: </label>
                                <?php $delivery_time_array = collect($orders_details->delivery_time)->toArray();?>
                                {{ $delivery_time_array['from_time'] . '-'. $delivery_time_array['to_time'] }}
                                <hr>
                            </div>
                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " > Delivery Address Name: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>
                                {{ $shipping_location_array['name'] }}
                                 <hr>
                            </div>
                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " > Delivery Address Location: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>
                                {{ $shipping_location_array['address'] }}
                                <hr>
                            </div>
                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " > Delivery Address Description / Shipping Address: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>

                                <?php  if(isset($shipping_location_array['description'])){
                                    echo $shipping_location_array['description'];
                                }else{'';} ?>
                                  <hr>
                            </div>
                      
							<div class="item form-group blue_background" 
							style="height: 80px;padding: 10px;background: #5a28b7;color: white;font-size: 20px;">
                               <div style="float: left;width: 50%;"> <label class="control-label col-md-2 " >Delivery Date: </label>
                                {{date('d/m/Y',strtotime($orders_details->delivery_date))}}
                                
                                </div>
                                <div style="float: right;width: 50%;text-align: right;">
									Total Amount: 
                                  {{$orders_details->total_amount + $orders_details->delivery_charge -$orders_details->admin_discount - $orders_details->promo_discount}}
                            INR</div>
                            </div>
                        
							</div>
													  
							  <hr>
<div class="border_bottom" style="border-bottom:5px solid #5a28b7 !important;">
                      <table class="table table-striped table-bordered" id="users-table">
                                <thead  class="success">
                                <tr>
									<th>Items</th>
									<th>Mts</th>
									<th>Qty</th>
									<th>Price </th>
									<th>Total Price </th>
										
                                    
                                  
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders_details->ProductOrderItem as $ProductOrderItem)
                                    <?php
										$data = json_decode($ProductOrderItem->data,true);
										
										?>
                                   
                                <tr>
									<th>{{ $data['vendor_product']['product']['name'] }}</th>
									<th>@if(isset($data['vendor_product']['measurementclass'])){{ $data['vendor_product']['measurementclass'] }}@endif</th>
									<th>{{$ProductOrderItem->qty}}</th>
									<th>{{$ProductOrderItem->price/$ProductOrderItem->qty}} INR</th>
									<th>{{$ProductOrderItem->price}} INR</th>
                                </tr>
                                  @endforeach
                                </tbody>
                            </table>
                            </div>
                           <hr>
                            <div class="border_bottom" >
                            <div class="col-sm-12 actionbutton">
                              <div class="item form-group">
                                <label class="control-label col-md-2 " >Sub-Total: </label>
                                {{$orders_details->total_amount}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Delivery Charge: </label>
                                {{$orders_details->delivery_charge}}
                                <hr>
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Promo Discount: </label>
                               {{$orders_details->promo_discount}}
                                <hr>
                            </div>
                           
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Admin Discount: </label>
                               {{$orders_details->admin_discount}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Final Amount: </label>
                                <!-- total amount in database is already including delivery charges -->
                              {{$orders_details->total_amount + $orders_details->delivery_charge -$orders_details->admin_discount - $orders_details->promo_discount}}
                                <hr>
                            </div>

                             </div>
                            

                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
@endsection


