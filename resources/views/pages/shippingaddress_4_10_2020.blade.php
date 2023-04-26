@extends('layouts.app')
@section('content')
@push('css')
  <style type="text/css"></style>
@endpush
<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>My Account</li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>Address</li>		
	</ul>
	</div>	
</section>

<section class="section-area">
<div class="container">	
 <div class="form-group clearfix">
<div class="col-sm-12">
<h5>Your Address For Order </h5>
<hr/><?php ?>
 {!! Form::open(['route' => "placeorder",'method'=>'post','id'=>'formaddress','class'=>'form-horizontal']) !!}
@foreach($deliveryLocation as $address )   
<?php   if($selectedid==$address->id){ $add='checked="checked"';  }else{ $add='';  } ?> 
<span class="address-radio">
<input {{$add}}  disbale="disbale" onclick="selectaddress()"  type="radio" name="shipping_location_id" id="{{$address->name}}" value="{{$address->id}}" >
<label for="{{$address->name}}">{{$address->address}}</label>
</span> 
@endforeach
            <hr />
            <div class="form-group clearfix">
            <div class="col-sm-12">
            <h5>Payment Mode </h5>    
            <span class="custome-radio col-md-3">
            <input checked="checked" type="radio" name="payment_mode_id" id="payment_mode_id1" value="1">
            <label for="payment_mode_id1">Cod</label>
            </span>
             <span class="custome-radio col-md-3">
            <input type="radio" name="payment_mode_id" id="payment_mode_id2" value="2">
            <label for="payment_mode_id2">Oline Payment</label>
            </span> 
            </div>              
            </div> 
<hr />
             <div class="form-group clearfix">
            <div class="col-sm-12 col-md-12">
            <input type="text" class="form-control col-sm-6 col-md-4" name="notes" placeholder="Notes for order">
            </div>
            </div>
             
            <div class="plc_ord_crt">
                 <?php if($selectedid==0){ $dis = 'disabled="disabled"'; $class="btn btn-default" ;
                echo "<p class='alert alert-info col-sm-9 ' id='exalert'>
                Sorry !!! You do not added any address for this location . Please Add a new address for this location</p>"; }
                else{  $dis =''; $class="common-btn" ; } ?>
               <button type="submit" {{$dis}} id="placeorder" class="{{$class}}">PLACE ORDER</button>
            </div>

  {!! Form::close() !!}
</div>              
</div>  
</div>	
</section>
@endsection
@push('scripts')
<script type="text/javascript">
function selectaddress(){

$('#placeorder').attr('class','common-btn');
$('#exalert').css('display','none');

}
</script>
@endpush
