@extends('layouts.app')
@push('css')
    <style type="text/css">
        .bottom-pagination ul li{ width: 20%;}
    </style>
@endpush
@section('content')
<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="{{url('/')}}">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li><a href="#">My Account</a></li>	
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>Order History</li>	
	</ul>
	</div>	
</section>
<?php //echo "<pre>"; print_r($current_orders); die; ?>
<?php //echo "<pre>"; print_r($past_orders); die; ?>
<section class="product-listing-body">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="ordr_hstry_tb">
					<ul class="nav nav-pills">
					  <li class="active"><a data-toggle="pill" href="#crnt_ordr">Current Order</a></li>
					  <li><a data-toggle="pill" href="#pst_ordr">Past Order</a></li>
					</ul>

					<div class="tab-content">
					  <div id="crnt_ordr" class="tab-pane fade in active">
					    <div class="crnt_ordr">
					    	<ul>
					    		@foreach($current_orders as $current_order)
					    		<?php 
									$f_price = 0;
									if(!empty($current_order->offer_total)){
										$f_price = $current_order->offer_total;
									}else{
										$f_price = $current_order->total_amount;
									}

									if(!empty($current_order->delivery_charge)){
										$f_price = $f_price + $current_order->delivery_charge;
									}
									if(!empty($current_order->coupon_amount)){
										$f_price = $f_price - $current_order->coupon_amount;
									}

								?>
					    		<li>
					    			<div class="crnt_ordr_sngl">
					    				<div class="crnt_ordr_con">
					    					<h5>{{date('d F Y H:i',strtotime($current_order->created_at))}}</h5>
					    					<span>Order Id #{{$current_order->order_code}}</span><br/>
					    					<span>Total -  ₹ {{$f_price}}</span><br/>
					    					<span>Order Status -  @if($current_order->order_status=='PO'){{'Partially Ordered'}}
					    						@elseif($current_order->order_status=='N'){{'New'}}
					    						@elseif($current_order->order_status=='CF'){{'Confirmed'}}
					    						@elseif($current_order->order_status=='O'){{'Collected'}}
					    						@elseif($current_order->order_status=='S'){{'On the way'}} 
					    						@elseif($current_order->order_status=='A'){{'At Doorstep'}}
					    						@elseif($current_order->order_status=='UP'){{'Updated'}} 
					    						@elseif($current_order->order_status=='C'){{'Cancelled'}} 
					    					@endif </span><br/>
					    					<span>Payment Type - @if($current_order->payment_mode_id==1){{ 'Cash on Delivery' }}@elseif($current_order->payment_mode_id==2){{ 'Online Payment' }}@elseif($current_order->payment_mode_id==3){{ 'Wallet Payment' }}@else{{ 'Online & Wallet Payment' }}@endif</span><br/>
					    			</div>
					    				<div class="crnt_ordr_btn">
					    					<a href="{{ url('/track-order/'.$current_order->id) }}"> <button type="button" class="btn btn-default trck_btn">TRACK ORDER</button></a>
					    					<a href="{{ url('/re-order/'.$current_order->id) }}"> <button type="button" class="btn btn-default trck_btn">Re-ORDER</button></a>
					    				</div>
					    			</div>
					    		</li>
					    		@endforeach
					    	</ul>	
       					    </div>
       					   <div class="container">
                           <div class="row order_pagination">
                           <div class="col-sm-4 col-md-3"><p> Total - {{$current_orders->total()}}</p></div>
                          <div class="col-sm-8 col-md-9">@include('pagination.default', ['paginator' => $current_orders])</div>
                        </div>
					    </div>
					  </div>
					  <div id="pst_ordr" class="tab-pane fade">
					    <div class="crnt_ordr">
				            <ul>
				            	@foreach($past_orders as $past_order)
				            	<?php //echo "<pre>"; print_r($past_order); die; ?>
					    		<li>
					    			<div class="crnt_ordr_sngl">
					    				<div class="crnt_ordr_con">
					    				<h5>{{date('d F Y H:i',strtotime($past_order->created_at))}}</h5>
					    					<span>Order Id #{{$past_order->order_code}}</span><br/>
					    					<span>Total - ₹ {{$past_order->offer_total}}</span><br/>
					    					<span>Order Status - @if($past_order->order_status=='C'){{'Cancelled'}}@elseif($past_order->order_status=='D'){{'Delivered'}} @endif </span><br/>
					    					<span>Payment Type - @if($past_order->payment_mode_id==1){{ 'Cash on Delivery' }}@elseif($past_order->payment_mode_id==2){{ 'Online Payment' }}@elseif($past_order->payment_mode_id==3){{ 'Wallet Payment' }}@else{{ 'Online & Wallet Payment' }}@endif</span><br/>
					    				</div>
					    				<div class="crnt_ordr_btn">
					    					<?php if($past_order->order_status == 'D'){ ?>
					    					<a href="{{ url('/re-order/'.$past_order->id) }}"> <button type="button" class="btn btn-default trck_btn">Re-ORDER</button></a>
					    				<?php }else{ ?>
												<a href="{{ url('/track-order/'.$past_order->id) }}"> <button type="button" class="btn btn-default trck_btn">TRACK ORDER</button></a>
					    				<?php	} ?>
					    				</div>
					    			</div>
					    		</li>
					    		@endforeach
				            </ul>   
				        </div>
				        <div class="container">
                           <div class="row order_pagination">
                           <div class="col-sm-4 col-md-3"><p> Total - {{$past_orders->total()}}</p></div>
                          <div class="col-sm-8 col-md-9">@include('pagination.default', ['paginator' => $past_orders])</div>
                        </div>
					    </div>
					   </div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</section>

@endsection