@extends('layouts.app')
@push('css')
    <style type="text/css">
    .discount-price{ color: #780202;
    text-decoration: line-through;
    margin-left: 20px; }
    </style>
@endpush
@section('content')
<section class="section-area">
<div class="container">	
<div class="delivery-time-box">
<h2>Membership Plan</h2>	

<?php  $membershipid = $membership_to = "";
      $can_add = false;
	if(Auth::user()){
		$phone = Auth::user()->phone_number;
		$email = Auth::user()->email;
		$Date = date('Y-m-d H:i:s');
		$membershipid =   Auth::user()->membership;
        $membership_to = Auth::user()->membership_to ;
        $nextDate = date('Y-m-d', strtotime($Date. ' + 2 days'));
        if($membership_to<$Date){
        	$can_add = true;
        }
		/*if((Auth::user()->membership_to <= $nextDate) && (Auth::user()->membership_to >= $Date) ) {
		$can_add = true;
        }*/
	}else{
		$phone = "";
		$email = "";
	}

	//echo "membership ==".$membershipid; die;
?>
<?php  $SiteSetting = Helper::globalSetting(); ?>
 <div class="row">
 	<div class="col-sm-12" style="text-align: center;">
 		<img src="/storage/app/public/upload/{{$SiteSetting->prime_memebership_image->image}}" style="width:20%" />
 		<p><h2>{{$SiteSetting->prime_memebership_image->title}}</h2></p>
 	</div>
 	@foreach($membership as $key=>$value)
		<div class="col-sm-4">
			<div class="pricing-plan plan1  <?php if(!empty($membershipid) && ($membershipid==$value->id)){ echo 'active'; }?> ">
				<div class="plan-name">
					<h3>{{$value->duration}}</h3>
				</div>
				<div class="price-wrap d-flex">
					<span class="plan-price color-main2">₹ <strong>{{$value->offer_price}}</strong></span></div>
                <div class="plan-features">
					<ul class="list-bordered">
						<li><span class="plan-price color-main2 @if($value->offer_price!=$value->price)  discount-price @endif">₹{{$value->price}} </span></li>
					</ul>
				</div>
				<div class="plan-button">
					<input type="hidden" name="membership_id" value="{{$value->id}}" />
					<input type="hidden" name="membership_price" value="{{$value->offer_price}}" />
					<button class="btn <?php if($can_add==1) { echo 'buy_now'; } ?>"  type="submit"  data-id="{{$value->id}}" data-price="{{$value->offer_price}}" ><span><?php if(!empty($membershipid) && ($membershipid==$value->id)){ echo "Purchased"; }else{ echo "Purchase"; }  ?></span></button>
				<?php //@if(!empty($membershipid) && ($membershipid==$value->id)) "disabled" @endif  @if(empty($membershipid)) @endif  ?>
				</div>
			</div>
			<div class="divider-45 d-block d-lg-none"></div>
		</div>
     @endforeach
						
				
</div>
    
</div>
	
</div>	
</section>
@endsection