@extends('layouts.app')
@section('content')
@section('title', ' Order Invoice |')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
@endpush

<?php 
//echo "<pre>"; print_r($data); die; ?>
<section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="{{url('/')}}">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li><a href="{{url('/profile')}}">My Account</a></li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li><a href="{{url('/orderhistory')}}">My Order</a></li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li><a href="{{url('/invoice/'.$id)}}">Invoice</a></li>  
    </ul>
    </div>  
</section>

<section class="section-area" id="divToPrint">
    <div class="container">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content">
						<div class="border_bottom" >
                             
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Customer Name : </label>
                                {{$user->name}}
                                <hr>
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Phone Number : </label>
                                {{$user->phone_number}}
                                <hr>
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Order Code : </label>
                                {{$orders_details->order_code}}
                                <hr>
                            </div>

                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Payment Status : </label>
                                <?php $payment_status = Helper::$transaction_status;
                                  echo $payment_status[$orders_details->transaction_status];
                                 ?>
                                <hr>
                            </div>
                            
                              
                            <div class="item form-group">
                                <label class="control-label col-md-2 " > Time Slot: </label>
                                <?php 
                                if(empty($orders_details->delivery_time)){
                                    echo "No Slot";

                                }else{
                                $delivery_time_array = collect($orders_details->delivery_time)->toArray();
                                 if(!empty($delivery_time_array)) {?>
                                {{ $delivery_time_array['from_time'] . '-'. $delivery_time_array['to_time'] }}
                                <?php }  }?>
                                <hr>
                            </div>
                        
                           
                         
                            <div class="item form-group">
                                <label class="control-label col-md-2 " > Delivery Address Name: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>
                                {{ $shipping_location_array['name'] }}
                                 <hr>
                            </div>

                              <div class="item form-group">
                                <label class="control-label col-md-2 " > Delivery Address Location: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>
                                {{ $shipping_location_array['address'] }}
                                 <hr>
                            </div>

                            @if(!empty($orders_details->notes))
                             <div class="item form-group">
                                <label class="control-label col-md-2 " > Notes: </label>
                                {{ $orders_details->notes}}
                                <br />
                                 <hr>
                            </div>
                            @endif

                        <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " >  Delivery Address Description / Shipping Address: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>
                                {{ $shipping_location_array['description'] }}
                                <hr>
                            </div> 
                      
							<div class="item form-group blue_background" 
							style="height: 80px;padding: 10px;background: #f77426;color: white;font-size: 20px;">
                               <div style="float: left;width: 50%;margin-top: 1%;">Delivery Date:
                                {{date('d/m/Y',strtotime($orders_details->delivery_date))}}
                                
                                </div>
                                <div style="float: right;width: 50%;text-align: right;margin-top: 1%;">
									Total Amount: 
                                  ₹ {{$orders_details->offer_total + $orders_details->service_charge + $orders_details->delivery_charge - $orders_details->promo_discount - $orders_details->coupon_amount}}
                            </div>
                            </div>
                        
							</div>
													  
							
                  <div class="border_bottom" style="border-bottom:5px solid #f77426 !important;">
                      <table class="table table-striped table-bordered" id="users-table">
                                <thead  class="success">
                                <tr>
									<th>Items</th>
									<th>Unit</th>
									<th>Qty</th>
                                   <th>MRP</th>
									<th>Total Price </th>
										
                                    
                                  
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($data->ProductOrderItem as $productordertem)
                                    <?php
										//$data = json_decode($ProductOrderItem->data,true);
                           //echo "<pre>"; print_r($data); die;
                                    	?>
                                   
                                <tr>
									<th>{{$productordertem->data->product->name}}</th>
									<th>{{$productordertem->data->product->measurement_value}} {{$productordertem->data->product->MeasurementClass['name']}}
                                   </th>
									<th>{{$productordertem->qty}}</th>
                  <th>₹ {{$productordertem->price}}</th>
									<th>₹ {{$productordertem->total_price}}</th>
                                </tr>
                                  @endforeach
                                </tbody>
                            </table>
                            </div>
                              <hr>
                            <div class="border_bottom" >
                            <div class="col-sm-12 actionbutton">
                              <div class="item form-group">
                                <label class="control-label col-md-2 " >MRP: </label>
                                ₹ {{number_format($orders_details->total_amount,2,'.','')}}
                                <hr>
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Product Discount: </label> -
                                ₹ {{number_format(($orders_details->total_amount - $orders_details->offer_total),2,'.','')}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Delivery Charge: </label> + 
                                ₹ {{$orders_details->delivery_charge}}
                                <hr>
                            </div>
                             <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Coupon Discount: </label> -
                               ₹ {{$orders_details->coupon_amount}}
                                <hr>
                            </div>

                             <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Total Saving: </label> -
                                ₹ {{$orders_details->total_amount - $orders_details->offer_total + $orders_details->coupon_amount}} ( {{ number_format(((($orders_details->total_amount - $orders_details->offer_total  + $orders_details->coupon_amount) / $orders_details->total_amount ) * 100),2,'.','') }} %  )
                                <hr>
                            </div>
                            <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Final Amount: </label>
                             ₹ {{$orders_details->offer_total + $orders_details->delivery_charge - $orders_details->promo_discount - $orders_details->coupon_amount}}
                                <hr>
                            </div>

                             </div>
                            <div class="col-sm-12 text-center padd40">
                                <button type="" class="btn btn-default" onclick="window.history.back();">Cancel</button>
                                <a onclick="PrintDiv()" class="btn btn-primary">Export AS PDF</a>
                            </div>

                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  </section>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
			
            #print_div, #print_div * {
                visibility: visible;
            }
            #print_div {
                position: absolute;
                left: 0;
                top: 0;
            }
           
        }
       
    </style>
    <style>
		 .border_bottom {
			
			
		} 
    </style>
@endsection
@push('scripts')
    <script type="text/javascript">     
    function PrintDiv() {    
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=300,height=300');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>
@endpush

