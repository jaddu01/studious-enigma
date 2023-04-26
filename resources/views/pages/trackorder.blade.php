@extends('layouts.app')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <style type="text/css">
        .treack_price_left{
		    border: 2px solid #f77426;
		    padding-top: 22px;
		    width: 49%; 
	        min-height: 360px;
		}
        .treack_price_right{
			border: 2px solid #f77426;
			margin-left: 1%;
			padding-top: 22px;
		    min-height: 360px;
          }
          .max-w{     max-width: 260px;}
    </style>
@endpush

@section('content')
<?php //echo "<pre>"; print_r($data); die;?>
<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="{{url('/')}}">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>My Account</li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>My Order</li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>{{$data->order_code}}</li>	
	</ul>
	</div>	
</section><?php $user=Auth::user(); ?>
<?php 
	$f_price = 0;
	if(!empty($data->offer_total)){
		$f_price = $data->offer_total;
	}else{
		$f_price = $data->total_amount;
	}

	if(!empty($data->delivery_charge)){
		$f_price = $f_price + $data->delivery_charge;
	}
	if(!empty($data->coupon_amount)){
		$f_price = $f_price - $data->coupon_amount;
	}
	if(!empty($data->coin_payment)) {
		$f_price = $f_price - $data->coin_payment;
	}

?>
<section class="section-area">
	<div class="container">	
		<div class="delivery-time-box">
			<h2>Order Details  # {{$data->order_code}}</h2>	
			<div class="address-box col-md-6">
				<h3>Delivery Address</h3>
				<h4>{{$user->name}} {{$user->lname}}</h4>
				
				@if(!empty($user->email))
					<p>{{$user->email}} </p>
				@endif
				
				<p>{{$data->address}} </p>
				<p><b>Mobile Number -</b>{{$user->phone_code}}-{{$user->phone_number}}</p>	

				<div class="phone-number"></div>
			</div>
			<div class=" col-md-6 max-w">
				<p> <b>Delivered On</b> :  {{ date('d/m/Y',strtotime($data->delivery_date))}}</p>	
				<p><b>Order Total Amount :  ₹ {{ $f_price}} </b></p>
				<p><b>Payment Status : <?php $payment_status = Helper::$transaction_status;
                                  echo $payment_status[$order_detail->transaction_status];
                                 ?></b></p>
				
			</div>
			<div class="more-action-area ">	
				<h3>More Action</h3>
				<p> <a target="_blank" href="{{url('/invoice/'.$data->id)}}"> <img src="{{url('public/images/pdf-icon.png')}}" alt="img"> View Invoice </a></p>	<!-- 
				<p> <a href="{{url('/pdfdownload/'.$data->id)}}">  <img src="{{url('public/images/pdf-icon.png')}}" alt="img"> Download Invoice </a></p>	 -->
			</div>	
		</div>
		<?php //echo "<pre>";print_r($data);  ?>
		<div class="delivery-time-box">
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6 treack_price_left">
						<h5>Order Id : {{$data->order_code}} </h5><hr/>
						<h5>Delivered On  : {{ date('d/m/Y',strtotime($data->delivery_date))}} </h5><hr/>
						<h5>Time slot : {{$data->time_slot}} </h5><hr/>
						<h5>Order Date : {{  date('d/m/Y', strtotime($data->created_at))  }} </h5><hr/>
						<h5>Order Status :  @if($data->order_status=='PO'){{'Partially Ordered'}}
					    						@elseif($data->order_status=='N'){{'New'}}
					    						@elseif($data->order_status=='CF'){{'Confirmed'}}
					    						@elseif($data->order_status=='O'){{'Collected'}}
					    						@elseif($data->order_status=='S'){{'On the way'}} 
					    						@elseif($data->order_status=='A'){{'At Doorstep'}}
					    						@elseif($data->order_status=='UP'){{'Updated'}} 
					    						@elseif($data->order_status=='C'){{'Cancelled'}} 
					    					@endif  </h5><hr/>
						<?php if(!empty($order_detail->notes)){ ?> 
							<h5>Notes : {{$order_detail->notes}} </h5><hr/> 
						<?php } ?>
						<h5>Payment Method : @if($order_detail->payment_mode_id=="1"){{ 'Cash on Delivery' }}@elseif($order_detail->payment_mode_id=="2"){{ 'Online Payment' }}@elseif($order_detail->payment_mode_id=="3"){{ 'Wallet Payment' }}@else{{ 'Online & Wallet Payment' }}@endif </h5><hr/>
					</div>
					<div class="col-md-6 treack_price_right">

						<h5> MRP :  ₹ {{$data->total_amount}} </h5><hr/>
						<h5>Product Discount : <b>- </b>  ₹ {{$data->total_amount - $data->offer_total }} </h5><hr/>
						<h5>Delivery  Charge : <b>+ </b> ₹ {{number_format($data->delivery_charge,2,'.','')}} </h5><hr/>
						<h5>Promo Discount   : <b>- </b> ₹  {{(!empty($data->coupon_amount))?number_format($data->coupon_amount,2,'.',''):0}} </h5><hr/>
						@if(!empty($data->coin_payment))
						<h5>Darbaar Coin   : <b>- </b> ₹  {{(!empty($data->coin_payment))?number_format($data->coin_payment,2,'.',''):0}}</h5><hr/>
						@endif
						<h5>Total Saving  : <b>-</b> ₹ {{$order_detail->total_amount - $order_detail->offer_total + $order_detail->coupon_amount}} ( {{ number_format(((($order_detail->total_amount - $order_detail->offer_total  + $order_detail->coupon_amount) / $order_detail->total_amount ) * 100),2,'.','') }} %  )</h5><hr/>
						<h5> <b> Final Amount </b> :  ₹ {{number_format(($f_price),2,'.','')}}  </h5><hr/>
					</div>
				</div>
			</div>
		</div>

		<div class="delivery-time-box bottom-track-order-col">
			@foreach($data->ProductOrderItem as $productordertem)
				<div class="bottom-track-order-left-col">	
					@if(isset($productordertem->data->product) && !empty($productordertem->data->product))
						<div class="track-order-left-product">
							<?php if(!empty($productordertem->data->product->image)){?>
								<img src="{{$productordertem->data->product->image['name']}}" alt="img">
							<?php }else{ ?>
							<img src="{{url('/storage/app/public/upload/404.jpeg')}}" alt="img">
							<?php }?>
						</div>	
						<div class="order-mid-contant">
							<h4>{{$productordertem->data->product->name}}</h4>	
							<p></p>
							<ul>
 								<li>  <span class="waight-box">{{$productordertem->data->product->measurement_value}} {{$productordertem->data->product->MeasurementClass['name']}}</span> </li>
 							</ul>
						</div>
						<div class="track-order-right-col">
							<ul>
								<li> <strong>Price:</strong> <span class="orange-text">{{$productordertem->qty}} * {{number_format($productordertem->price,2,'.','')}} =   ₹ {{number_format($productordertem->total_price,2,'.','')}}</span> </li>	
							</ul>	
						</div>
					@endif
				</div>
			@endforeach

			<div class="bottom-track-order-right-col">	
				<h4>Order Track</h4>
<hr />
<?php
$status_one ="Order Confirmed";
$status_two ="Order Accepted";
$status_three ="At Doorstep";
$status_f ="Updated";
if($data->order_status == 'D'){
	$status = "Delivered";
}else if($data->order_status == 'C'){
	$status = "Canceled";
}else if($data->order_status == 'R'){
	$status = "Returned";
}else{
	$status = "Delivered";
}
  if($data->order_status=='CF'){ $class="stp_1_dne";  $status_one ="Order Confirmed"; }
  else if($data->order_status=='N'){ $class=""; $status_one="Order Accepted";  }
  else if($data->order_status=='O'){ $class="stp_2_dne"; $status_two ="Collected"; }
  else if($data->order_status=='S'){ $class="stp_2_dne"; $status_two ="On the way"; }
  else if($data->order_status=='A'){ $class="stp_3_dne";  $status_three ="At Doorstep";}
  else if($data->order_status=='UP'){ $class="stp_4_dne";  $status_f ="Updated";}
  else if($data->order_status=='D'){ $class="stp_5_dne";  $status = "Delivered";}
  else if($data->order_status=='C'){ $class="stp_5_dne";  $status = "Cancelled"; }
  else if($data->order_status=='R'){ $class="stp_5_dne";  $status = "Returned"; }
  else{ $class=""; } ?>
<div class="trck_mp_slf {{$class}}";>
<ul>
<li class="li_crcla"><p>{{$status_one}}</p></li>
<li class="li_crclb"><p>{{$status_two}}</p></li>
<li class="li_crclc"><p>{{$status_three}}</p></li>
<li class="li_crcld"><p>{{$status_f}}</p></li>
<li class="li_crcle"><p>{{$status}}</p></li>
</ul>
</div>	
</div>	
<?php if($data->order_status=='N'){ ?>
<div class="help-cancle-text">
<!-- <span><a href="{{url('/support')}}"> <i class="fa fa-question-circle" aria-hidden="true"></i> Need Help </a> </span>	
 --><span><a href="{{url('/update-order/').'/'.$data->id}}"> <i class="fa fa-times" aria-hidden="true"></i> Cancel Order </a> </span>		
</div>	
<?php }else if($data->order_status=='D'){ ?>
<div class="help-cancle-text">
<span><a href="{{url('/re-order/').'/'.$data->id}}"> <i class="fa fa-times" aria-hidden="true"></i> ReOrder </a> </span>		
</div>
<?php } ?>
</div>	
</section>
	
	
@endsection