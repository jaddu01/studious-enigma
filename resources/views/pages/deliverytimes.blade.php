@extends('layouts.app')
@push('css')
<style type="text/css"> .noclickable{ background: #9a9393  !important; color: #fff !important; cursor:none !important;}
li.fast_delivery {
    width: 20% !important;
}

 </style>
@endpush
@section('content')
<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>My Account</li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>Delivery Time</li>		
	</ul>
	</div>	
</section>

<section class="section-area">
<div class="container">	
<div class="delivery-time-box">
<h2>Delivery Time</h2>	

<div class="date-box-area">
 {!! Form::open(['route' => "getaddress",'method'=>'post','id'=>'formtime','class'=>'form-horizontal']) !!}
        
	<?php foreach($deliveryDay as  $days){ ?>
		<ul>
			<h3><?php
				$today = date('l');
				if($today == $days['name']){
					echo "Today";
				}else{
					echo $days['name'];		
				}
			?></h3>
			<?php 
				foreach($days['delivery_time'] as  $delivery_time){
					if($delivery_time->is_clickable=='N') {
						$class="noclickable"; 
					}else{ 
						$class="";  
					}?> 
    				<li class="{{$class}}" @if($delivery_time->is_clickable=='Y') onclick="selected(this.id,'{{$days['date']}}')" @endif id="{{$delivery_time->id}}" data-action="{{$delivery_time->is_clickable}}">
    					{{$delivery_time->from_time}}-{{$delivery_time->to_time}}
    				</li>	
    			<?php }?>
    			
    	</ul>  
    <?php }?>
    @if($is_membership == 'Y')
		<?php
			date_default_timezone_set('Asia/Calcutta');
			$currentHour = date('H');
			$openTime = 6;
			$closeTime = 18;
			if($currentHour >= $openTime && $currentHour < $closeTime){
				$class = "style='display:block;'";
			}else{
				$class = "style='display:none;'";
			}
		?>
		<ul <?= $class; ?>>
			<h3>Fast Delivery </h3>
			<li class="fast_delivery" onclick="selected('fast_delivery')" id="" data-action="Y">Fast Delivery-Delivered In 3 hours</li>	
		</ul>
	@endif
    <input type="hidden" name="delivery_time_id" id="delivery_time_id" value="">
    <input type="hidden" name="delivery_day" id="delivery_day" value="">

{!! Form::close() !!}
</div>
</div>	
</div>	
</section>
@endsection
@push('scripts') 
 <script>
	 function selected(data,delivery_day){
	 //	alert(data);

       $('#delivery_time_id').val(data);
       $('#delivery_day').val(delivery_day);
	   $('#formtime').submit();
          
         }
</script>
@endpush