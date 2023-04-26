@extends('admin.layouts.app')

@section('title', ' Order Invoice |')
@push('css')
    <link href="{{asset('css/bootstrap-toggle.min.css')}}" rel="stylesheet">
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
<?php //echo "<pre>"; print_r($orders_details->toArray());?>
        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">
                             <div id="divToPrint">
                            
						<div class="border_bottom" >
                            <span class="section"> Invoice </span>
                            
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
                            
                            @if (!empty($orders_details->transaction_id))
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Transaction Id: </label>
                                {{$orders_details->transaction_id}}
                               <hr>
                            </div>
                             @endif

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Payment Mode: </label>
                                <?php echo Helper::paymentmodebyid($orders_details->payment_mode_id); ?> 
                               <hr>
                            </div>
                             
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Transaction Status: </label>
                                {{Helper::$transaction_status[$orders_details->transaction_status]}}
                               <hr>
                            </div>
                           
                            
                            <div class="item form-group">
                                <label class="control-label col-md-2 " > Time Slot: </label>
                                 <?php $delivery_time_array = collect($orders_details->delivery_time)->toArray(); 
                                    $from_time = $to_time = '';
                                    if(isset($delivery_time_array['from_time'])){
                                        $from_time = $delivery_time_array['from_time'];
                                    }
                                    if(isset($delivery_time_array['to_time'])){
                                        $to_time = $delivery_time_array['to_time'];
                                    }
                                ?>
                                @if($from_time && $to_time)
                                    {{ $from_time . '-'. $to_time }}
                                @else
                                    Fast Delivery
                                @endif 
                                <hr>
                            </div>

                             <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();
                             if(!empty($shipping_location_array['name'])){
                             ?>
                        
                            <div class="item form-group">
                                <label class="control-label col-md-2 " > Delivery Address Name: </label>
                               {{ $shipping_location_array['name'] }}
                                 <hr>
                            </div>
                        <?php } 
                        if(!empty($shipping_location_array['address'])){ ?>

                              <div class="item form-group">
                                <label class="control-label col-md-2 " > Delivery Address Location: </label>
                                {{ $shipping_location_array['address'] }}
                                 <hr>
                            </div>
                        <?php } ?>                           

                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " >  Delivery Address Description / Shipping Address: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>
                                 <?php  if(isset($shipping_location_array['description'])){
                                    echo $shipping_location_array['description'];
                                }else{'';} ?>
                                <hr>
                            </div>
                            
                      
							<div class="item form-group blue_background" 
							style="height: 80px;padding: 10px;background: #5a28b7;color: white;font-size: 20px;">
                               <div style="float: left;width: 50%;margin-top: 1%;">Delivery Date:
                                {{date('d/m/Y',strtotime($orders_details->delivery_date))}}
                                
                                </div>
                                <div style="float: right;width: 50%;text-align: right;margin-top: 1%;">
									Total Amount: 
                                    ₹ {{$orders_details->offer_total + $orders_details->delivery_charge - $orders_details->coupon_amount}}
                            </div>
                            </div>
                        
							</div>
													  
							
<div class="border_bottom" style="border-bottom:5px solid #5a28b7 !important;">
                      <table class="table table-striped table-bordered" id="users-table">
                                <thead  class="success">
                                <tr>
									<th>Items</th>
									<th>Unit</th>
									<th>Qty</th>
                                    <th>Price</th>
                                    <th>Offer Price </th>
									<th>Total Price </th>
										
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders_details->ProductOrderItem as $ProductOrderItem)
                                    <?php
										$data = json_decode($ProductOrderItem->data,true);

                                        $price = $data['vendor_product']['price'];
                                 	?>
                                   
                                <tr>
									<th>{{ $data['vendor_product']['product']['name'] }}</th>
									<th>@if(isset($data['vendor_product']['measurementclass'])){{ $data['vendor_product']['measurementclass'] }}@endif
                                    @if(isset($data['vendor_product']['product']['measurement_class']['name'])){{ $data['vendor_product']['product']['measurement_class'] ['name']}}@endif
                                    </th>
									<th>{{$ProductOrderItem->qty}}</th>
                                   <th>₹ {{number_format($data['vendor_product']['best_price'],2,'.','')}}</th>
									<th> ₹ {{number_format($data['vendor_product']['offer_price'],2,'.','')}}</th>
                                   
									<th> ₹ {{number_format(($data['vendor_product']['offer_price'] * $ProductOrderItem->qty),2,'.','')}}</th>
                                </tr>
                                  @endforeach
                                </tbody>
                            </table>
                            </div>
                              <hr>
                            <div class="border_bottom" >
                            <div class="col-sm-12 actionbutton">
                              <div class="item form-group">
                                <label class="control-label col-md-2 " >Offer Price: </label>
                                 ₹ {{$orders_details->total_amount}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Sub Total: </label>
                                 ₹ {{$orders_details->offer_total}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Product Dis.: </label>
                                 ₹ {{($orders_details->total_amount  - $orders_details->offer_total)}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Delivery Charge: </label>
                                {{$orders_details->delivery_charge}}
                                <hr>
                            </div>


                           
                           
                             <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Promo Code Disc: </label>
                                ₹ {{$orders_details->coupon_amount}}
                                <hr>
                            </div>

                             <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Total Saving: </label>
                                ₹ {{$orders_details->total_amount - $orders_details->offer_total + $orders_details->coupon_amount}} ( {{ number_format(((($orders_details->total_amount - $orders_details->offer_total + $orders_details->coupon_amount) / $orders_details->total_amount ) * 100),2,'.','') }} %  )
                                <hr>
                            </div>
                            @php
                                if(!empty($orders_details->coupon_amount)){
                                    $final_amount = $orders_details->offer_total + $orders_details->delivery_charge-$orders_details->coupon_amount;
                                }else{
                                    $final_amount = $orders_details->offer_total + $orders_details->delivery_charge;
                                }
                            @endphp
                            <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Final Amount: </label>
                                ₹ {{$final_amount}}
                                <hr>
                            </div>

                             </div> </div>
                            <div class="col-sm-12 text-center padd40">
                                <button type="" class="btn btn-default" onclick="window.history.back();">Cancel</button>
                                <a onclick="PrintDiv()" class="btn btn-primary">Print</a>
                            </div>

                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
       var popupWin = window.open('', '_blank', 'width=800,height=800');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>
@endpush

