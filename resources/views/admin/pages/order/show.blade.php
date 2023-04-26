@extends('admin.layouts.app')

@section('title', ' order details |')
@push('css')
    <link href="{{asset('css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <style type="text/css">
        .tab-pane{margin-top:20px;}
        .actionbutton a {padding: 13px 8px;}
    </style>
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
								<ul class="nav nav-tabs">
								<li class="active">
                                    <a data-toggle="tab" href="#Premium">Order Details</a>
                                </li>
								<li><a data-toggle="tab" href="#Unpaid">Products</a></li>
								<li><a data-toggle="tab" href="#actions">Actions</a></li>
								
							</ul>
							
							
			<div class="tab-content">
								
				<div id="Premium" class="tab-pane  fade  in active">
        					<div class="item form-group">
                                <label class="control-label col-md-2 " >Order Code : </label>
                                    {{$orders_details->order_code}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Order Status: </label>
                                <div class="col-md-2">
                                <select name="order_status" class="form-control" onchange="changeStatus({{$orders_details->id}},$('[name=order_status]').val())">
                                  <?php $o=0;  ?>
                                    @foreach(Helper::$order_status as $key=>$order_status)
                                     <?php if($key==$orders_details->order_status){ $o=1; }  ?>
                                        <option value="{{$key}}" {{$key==$orders_details->order_status ? 'selected':''}}  {{(($o==0) && ($key!=$orders_details->order_status))?'disabled':''}}>{{$order_status}}</option>
                                    @endforeach
                                </select>
                                </div>
                                 <div class="clearfix"></div>
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Delivery Date: </label>
                                {{ date('d/m/Y',strtotime($orders_details->delivery_date)) }}
                                <hr>
                            </div>
                            <div class="item form-group"  style="clear: both">
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
                                <hr style="clear: both">
                            </div>
                            <div class="item form-group"  style="clear: both">
                                <label class="control-label col-md-2 " > Customer: </label>
                                {{ isset($orders_details->User->name) ? $orders_details->User->name : ''}}
                                <hr  style="clear: both">
                            </div>
                            <div class="item form-group" style="clear: both">
                                <label class="control-label col-md-2 " > Customer Phone: </label>
                             {{ isset($orders_details->User->phone_code) ? $orders_details->User->phone_code : ''}} -   {{ isset($orders_details->User->phone_number) ? $orders_details->User->phone_number : ''}}
                                <hr>
                            </div>
                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " > Delivery Address Name: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();//print_r($shipping_location_array);
                                if(isset($shipping_location_array['name'])){
                                ?>
                                {{ $shipping_location_array['name'] }}
                               <?php } ?>
                            </div>
                        
                            <hr>

                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " > Delivery Address Location: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();
                                 if(isset($shipping_location_array['address'])){
                                ?>
                                {{ $shipping_location_array['address'] }}
                              <?php } ?>
                            </div>
                              <hr>
                               <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " > Delivery Address Description / Shipping Address: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>

                                <?php  if(isset($shipping_location_array['description'])){
                                    echo $shipping_location_array['description'];
                                }else{'';} ?>
                            </div>
                            <hr>
                           
                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " >Zone: </label>
                                {{$orders_details->zone->name}}
                                <hr>
                            </div>
                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " >Vendor: </label>
                                {{$orders_details->vendor->full_name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Shopper: </label>
                                {{$orders_details->shopper->full_name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Driver: </label>
                                {{$orders_details->driver->full_name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Offer Price: </label>
                                 ₹ {{$orders_details->total_amount - $orders_details->delivery_charge}}
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
                                 ₹ {{$orders_details->delivery_charge}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Delivery Boy Tips: </label>
                                 ₹ {{($orders_details->delivery_boy_tip > 0)?$orders_details->delivery_boy_tip:0.00}}
                                <hr>
                            </div>
                            
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Promo Code Disc.: </label>
                                 ₹ {{$orders_details->coupon_amount}} 
                                <hr>
                            </div>
                            @php
                                if(!empty($orders_details->coupon_amount)){
                                    $final_amount = $orders_details->offer_total - $orders_details->coupon_amount;
                                }else{
                                    $final_amount = $orders_details->offer_total;
                                }
                            @endphp
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Total Amount: </label>
                                <!-- total amount in database is already including delivery charges -->
                                 ₹ {{$final_amount + $orders_details->delivery_charge}}
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
                                <label class="control-label col-md-2 " >Total Saving: </label>
                                 ₹ {{$orders_details->total_amount - $orders_details->offer_total + $orders_details->coupon_amount}} ( {{ number_format(((($orders_details->total_amount - $orders_details->offer_total  + $orders_details->coupon_amount) / $orders_details->total_amount ) * 100),2,'.','') }} %  )
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Number Of Items: </label>
                                {{count($orders_details->ProductOrderItem)}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Created At: </label>
                                {{ date('d/m/Y H:i:s',strtotime($orders_details->created_at))}}
                                <hr>
                            </div>


						</div>
								<div id="Unpaid" class="tab-pane fade">
									 <table class="table table-striped table-bordered" id="users-table">
                                <thead  class="success">
                                <tr>
									 <th>Image</th>
                                 
                                    <th>Total Price </th>
                                    <th>Price </th>
                                    <th>Is Offer</th>
                                    <th>Offer Value</th>
                                    <th>Qty</th>
                                   
                                    <th>Name</th>
                                      <?php if($orders_details->order_status =='N' || $orders_details->order_status =='CF') { ?>
                                    <th>Action</th>
                                       <?php } ?>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders_details->ProductOrderItem as $ProductOrderItem)
                                <tr>
                                    <?php
                                        $data = json_decode($ProductOrderItem->data,true); 
                                       $unit_price = $data['vendor_product']['price'];
                                        if(!empty($data['vendor_product']['offer'])){
                                            if($data['vendor_product']['offer']['offer_type']=='amount'){
                                                $price = $unit_price - $data['vendor_product']['offer']['offer_value'];
                                            }else if($data['vendor_product']['offer']['offer_type']=='percentages'){
                                                $price = $unit_price - ( ($unit_price * $data['vendor_product']['offer']['offer_value'] ) / 100 );
                                            }
                                        }else{ $price = $unit_price; }
                                        

                                    ?>
									<th><img src="{{ (!empty($data['vendor_product']['product']['image']['name']))?$data['vendor_product']['product']['image']['name']:url('storage/app/public/upload/404.jpeg') }}" height='70' /></th>
                                    <th>{{$price  * $ProductOrderItem->qty }}</th>
                                    <th>{{$price }}</th>
                                    <th>{{$ProductOrderItem->is_offer}}</th>
                                    <th>{{$ProductOrderItem->offer_value}} ( {{$data['vendor_product']['offer']['offer_type']}} )</th>
                                    <th>{{$ProductOrderItem->qty}}</th>
                                     
                                    <th>{{ $data['vendor_product']['product']['name'] }}</th>
                                     <?php if($orders_details->order_status =='N' || $orders_details->order_status =='CF') { ?>
                                    <th>
                                        
                                        <a class="btn btn-success" href="{{url('admin/order/edit-qty',[$ProductOrderItem->id])}}">Edit Qty</a>
                                        <a class="btn btn-danger" href="{{url('admin/order/remove-order-item',[$ProductOrderItem->id])}}">Remove</a>
                                   
                                    </th>
                                     <?php } ?>
                                    


                                </tr>
                                  @endforeach
                                </tbody>
                            </table>
									
								
				</div>
						
						
				<div id="actions" class="tab-pane fade">
                    <?php if($orders_details->order_status =='N' || $orders_details->order_status =='CF' || $orders_details->order_status =='UP') { ?>
										   
                    <div class="col-sm-12 actionbutton">
                        <a href="{{url('admin/order/add-product',$orders_details->id)}}" class="btn btn-primary customcolor">Add Product</a>
                        <a href="{{url('admin/order/modify-address',$orders_details->id)}}" class="btn btn-info">Modify Address</a>
                        <a href="{{url('admin/order/modify-delivery-date-or-slot',$orders_details->id)}}" class="btn btn-info">Modify Delivery Date or Slot</a>
                        <a href="{{url('admin/order/change-shopper-and-driver',$orders_details->id)}}" class="btn btn-info">Change Shopper and Driver</a>
                        <a style="display: none;" href="{{url('admin/order/add-discount',$orders_details->id)}}" class="btn btn-info">Add Discount</a>
                        <a href="{{url('admin/order/invoice',$orders_details->id)}}" class="btn btn-primary customcolor">Invoice</a>
                    </div>
                <?php } else{ ?>
                    <div class="col-sm-12 actionbutton">
                   
                        <a href="{{url('admin/order/invoice',$orders_details->id)}}" class="btn btn-primary customcolor">Invoice</a>
                    </div>
                <?php }  ?>
								
				</div>	

								
                </div>
                        <!-- end tab-->				
                  
                           <!--  <div class="col-sm-12 text-center padd40">
                                <button type="reset" class="btn btn-default">Cancle</button>
                                <button  class="btn btn-primary">Update Order</button>
                            </div> -->

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
@endsection
@push('scripts')
    <script>
        function changeStatus(id,status){
            $.ajax({
				 url: "{!! route('admin.order.status') !!}",
            type: 'PATCH',
      // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
      data: {
        _method: 'PATCH',
        status : status,
        id : id,      
        _token: '{{ csrf_token() }}'
      },
                success: function( data ) {
                   
                    alertify.success("Success "+data.message);

                },
                error: function( data ) {
                    alertify.error("some thinng is wrong");

                }
            });
            
          
        }
        function myFunction() {
            var printContents = window.print();
            /* w=window.open();
             w.document.write(printContents);
             w.print();
             w.close();*/
        }
    </script>
@endpush

