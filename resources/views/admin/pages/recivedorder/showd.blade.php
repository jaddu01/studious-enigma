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
								<li class="active">  <a data-toggle="tab" href="#Unpaid">Products</a></li>
								<li><a data-toggle="tab" href="#Customers">Customers</a></li>
                                <li><a data-toggle="tab" href="#Premium">Order Details</a></li>
							</ul>
			<div class="tab-content">
				<div id="Premium" class="tab-pane  fade  ">
        					<div class="item form-group">
                                <label class="control-label col-md-2 " >Order Code : </label>
                                   <div class="col-md-2">  {{$orders_details->order_code or ''}}</div>
                                 <div class="clearfix"></div>
                                <hr>
                            </div>
                        
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Delivery Date: </label>
                                {{$orders_details->delivery_date or ''}}
                                <hr>
                            </div>
                            <div class="item form-group"  style="clear: both">
                                <label class="control-label col-md-2 " > Time Slot: </label>
                                <?php $delivery_time_array = collect($orders_details->delivery_time)->toArray(); 
                                  if(!empty($delivery_time_array)) {?>
                                {{ $delivery_time_array['from_time'] . '-'. $delivery_time_array['to_time'] }}
                                <?php } ?>
                                <hr style="clear: both">
                            </div>
                            <div class="item form-group"  style="clear: both">
                                <label class="control-label col-md-2 " > Coustomer: </label>
                                {{ isset($orders_details->User->name) ? $orders_details->User->name : ''}}
                                <hr  style="clear: both">
                            </div>
                            <div class="item form-group" style="clear: both">
                                <label class="control-label col-md-2 " > Coustomer Phone: </label>
                             {{ isset($orders_details->User->phone_code) ? $orders_details->User->phone_code : ''}} -   {{ isset($orders_details->User->phone_number) ? $orders_details->User->phone_number : ''}}
                                <hr>
                            </div>
                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " > Delivery Address Name: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();//print_r($shipping_location_array);?>
                                {{ $shipping_location_array['name'] }}
                               
                            </div>
                            <hr>
                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " > Delivery Address Location: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>
                                {{ $shipping_location_array['address'] }}
                              
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
                                <div class="col-md-2">{{$orders_details->zone->name}}</div>
                                 <div class="clearfix"></div>
                                
                                <hr>
                            </div>
                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " >Vendor: </label>
                                <div class="col-md-2"> {{$orders_details->vendor->full_name or ''}}</div>
                                 <div class="clearfix"></div>
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Shopper: </label>
                                <div class="col-md-2">{{$orders_details->shopper->full_name or ''}}</div>
                                 <div class="clearfix"></div>
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Driver: </label>
                                <div class="col-md-2">{{$orders_details->driver->full_name or ''}}</div>
                                 <div class="clearfix"></div>
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Sub-Total: </label>
                                 <div class="col-md-2">{{$orders_details->total_amount or '' }}</div>
                                 <div class="clearfix"></div>
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Delivery Charge: </label>
                                <div class="col-md-2">{{$orders_details->delivery_charge or ''}}</div>
                                 <div class="clearfix"></div>
                                <hr>
                            </div>
                            
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Promo Code Disc.: </label>
                                {{$orders_details->promo_discount}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Admin Discount: </label>
                                {{$orders_details->admin_discount}}
                                <hr>
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Total Amount: </label>
                                <!-- total amount in database is already including delivery charges -->
                                {{($orders_details->total_amount + $orders_details->delivery_charge -($orders_details->admin_discount + $orders_details->promo_discount))}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Transaction Id: </label>
									@if ($orders_details->transaction_id)
                                {{$orders_details->transaction_id}}
                                @else 
                                0
                                @endif
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Transaction Status: </label>
                                {{Helper::$transaction_status[$orders_details->transaction_status]}}
                                
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Number Of Items: </label>
                                {{count($orders_details->ProductOrderItem)}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Created At: </label>
                                {{$orders_details->created_at}}
                                <hr>
                            </div>


						</div>
                        <div id="Customers" class="tab-pane fade">
    <div class="item form-group">
    <label class="control-label col-md-2 " >Customer name : </label>
    <div class="col-md-2">  {{$cust_details->name or ''}}</div>
    <div class="clearfix"></div>
    <hr>
    </div>
    <div class="item form-group">
    <label class="control-label col-md-2 " >Mobile: </label>
    <div class="col-md-2">  {{$cust_details->phone_code.'-'.$cust_details->phone_number}}</div>
    <div class="clearfix"></div>
    <hr>
    </div>
    <div class="item form-group">
    <label class="control-label col-md-2 " >email: </label>
    <div class="col-md-2">  {{$cust_details->email or ''}}</div>
    <div class="clearfix"></div>
    <hr>
    </div>
    <div class="item form-group">
    <label class="control-label col-md-2 " >Address: </label>
    <div class="col-md-2">  {{$orders_details->shiping_location->address or ''}}</div>
    <div class="clearfix"></div>
    <hr>
    </div>

    </div>
								<div id="Unpaid" class="tab-pane fade in active">
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
                                      <?php if($orders_details->order_status =='N' || $orders_details->order_status =='CF' || $orders_details->order_status =='PO' || $orders_details->order_status =='D') { ?>
                                       <?php } ?>
                                       <th> Status </th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($productorderdetail as $ProductOrderItem)
                                <tr><th>
                                    <?php
                                        $data = json_decode($ProductOrderItem->data,true);
                                        if(isset($ProductOrderItem->Product->image)){ ?>
                                       <img src="{{ $ProductOrderItem->Product->image['name'] }}" height='70' /> 
                                    <?php  }else{?>
                                        <img src="" height='70' /> 
                                  
                                  <?php  } ?>
                                </th>
                                <th>{{$ProductOrderItem->price}}</th>
                                    <th>{{$ProductOrderItem->price/$ProductOrderItem->qty}}</th>
                                    <th>{{$ProductOrderItem->is_offer}}</th>
                                    <th>{{$ProductOrderItem->offer_value}}</th>
                                    <th>{{$ProductOrderItem->qty}}</th>
                                     
                                    <th>{{ $data['product']['name'] }}</th>
                                     <?php if($orders_details->order_status =='N' || $orders_details->order_status =='CF' || $orders_details->order_status =='PO' || $orders_details->order_status =='D') { ?>
                                    
                                     <?php } ?></th>
                                     <th>{{$ProductOrderItem->product_order_status}}</th>
                                    


                                </tr>
                                  @endforeach
                                </tbody>
                            </table>
									
								
				</div> 
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
                     location.reload();

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

