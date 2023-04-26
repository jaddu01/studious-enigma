
<section class="section-area">
    <div class="container">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content">
						<div class="border_bottom" >
                             
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Customer Name : </label>
                                <label class="control-label col-md-10 " >{{$orders_details->user->name}}</label>
                                <hr>
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Phone Number : </label>
                                <label class="control-label col-md-10 " >{{$orders_details->user->phone_number}}</label>
                                <hr>
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Order Code : </label>
                                <label class="control-label col-md-10 " >{{$orders_details->order_code}}</label>
                                <hr>
                            </div>

                            
                              
                            <div class="item form-group">
                                <label class="control-label col-md-2 " > Time Slot: </label>
                                 <label class="control-label col-md-10 " ><?php 
                                if(empty($orders_details->delivery_time)){
                                    echo "No Slot";

                                }else{
                                $delivery_time_array = collect($orders_details->delivery_time)->toArray();
                                 if(!empty($delivery_time_array)) {?>
                                {{ $delivery_time_array['from_time'] . '-'. $delivery_time_array['to_time'] }}
                                <?php }  }?></label>
                                <hr>
                            </div>
                        
                           
                         
                            <div class="item form-group">
                                <label class="control-label col-md-2 " > Delivery Address Name: </label>
                                <label class="control-label col-md-10 " > <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>
                                <?php 
                                    if(isset($shipping_location_array['name'])){
                                      echo $shipping_location_array['name'];
                                    }else{
                                      echo '';
                                    }
                                ?>

                                </label>
                                 <hr>
                            </div>

                              <div class="item form-group">
                                <label class="control-label col-md-2 " > Delivery Address Location: </label>
                                 <label class="control-label col-md-10 " ><?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>
                                 <?php 
                                    if(isset($shipping_location_array['address'])){
                                      echo $shipping_location_array['address'];
                                    }else{
                                      echo '';
                                    }
                                ?></label>
                                 <hr>
                            </div>


                           

                            <div class="item form-group clearfix">
                                <label class="control-label col-md-2 " >  Delivery Address Description / Shipping Address: </label>
                                <?php $shipping_location_array = collect($orders_details->shipping_location)->toArray();?>
                                <?php 
                                    if(isset($shipping_location_array['description'])){
                                      echo $shipping_location_array['description'];
                                    }else{
                                      echo '';
                                    }
                                ?>
                                <hr>
                            </div> 
                            
                      
							<div class="item form-group blue_background" 
							style="height: 80px;padding: 10px;background: #f77426;color: white;font-size: 20px;">
                               <div style="float: left;width: 50%;margin-top: 1%;">Delivery Date:
                                {{date('d/m/Y',strtotime($orders_details->delivery_date))}}
                                
                                </div>
                                <div style="float: right;width: 50%;text-align: right;margin-top: 1%;">
									Total Amount: 
                                   {{$orders_details->offer_total + $orders_details->service_charge -$orders_details->admin_discount - $orders_details->promo_discount - $orders_details->coupon_amount}} INR
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
                    <th>Per Unit Price </th> 
                                    <th>Offer Price </th>
									<th>Total Price </th>
										
                                    
                                  
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders_details->ProductOrderItem as $ProductOrderItem)
                                    <?php
                                   	$data = json_decode($ProductOrderItem->data,true);
                                     //   echo "<pre>"; print_r($data); die;
									
                                    	?>
                                   
                                <tr>
								<th>{{$data['vendor_product']['product']['translations'][0]['name']}}</th>
                  <th>{{ $data['vendor_product']['product']['measurement_value'] }} @if(isset($data['vendor_product']['measurementclass'])){{$data['vendor_product']['measurementclass']}}@endif
                                   </th>
                  <th>{{$data['vendor_product']['qty']}}</th>
                                    <th>{{$data['vendor_product']['price']}} INR</th>
                    <th>{{$data['vendor_product']['offer_price']}} INR</th>
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
                                 <label class="control-label col-md-10 " >{{$orders_details->offer_total}} INR</label>
                                <hr>
                            </div>


                          
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Delivery Charge: </label>
                                <label class="control-label col-md-10 " > {{$orders_details->delivery_charge}} INR</label>
                                <hr>
                            </div>

                              @if($orders_details->admin_discount>0)
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Admin Discount: </label>
                                <label class="control-label col-md-10 " >{{$orders_details->admin_discount}} INR</label>
                                <hr>
                            </div>
                            @endif
                            @if($orders_details->promo_discount>0)
                             <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Promo Discount: </label>
                                <label class="control-label col-md-10 " >{{$orders_details->promo_discount}} INR</label>
                                <hr>
                            </div>
                              @endif
                               @if($orders_details->coupon_amount>0)
                             <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Coupon Discount: </label>
                               {{$orders_details->coupon_amount}} INR
                                <hr>
                            </div>
                              @endif
                            <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Final Amount: </label>
                              <label class="control-label col-md-10 " >{{$orders_details->offer_total + $orders_details->delivery_charge + -$orders_details->admin_discount - $orders_details->promo_discount}} INR</label>
                                <hr>
                            </div>

                             </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  </section>

    
