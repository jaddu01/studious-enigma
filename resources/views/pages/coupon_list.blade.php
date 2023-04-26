@extends('layouts.app')
@push('css')
<style type="text/css">
table{ width:100%; }
table tr th,table tr td{ padding: 20px;  }
</style>
@endpush

@section('content')

<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="{{url('/')}}">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>Coupon list</li>	
	</ul>
	</div>	
</section>

  <section class="product-listing-body">
  <div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="wshlst_rt_mn clearfix">
  <table border='1'>
    <tr>
      <th>Coupon Name</th>
      <th>Coupon code</th>
      <th>Coupon Discount</th>
      <th>Coupon Ends On </th>
    </tr>
     @foreach($coupons as $coupon )
     <tr>
      <td>{{$coupon->name}}</td>
      <td>{{$coupon->code}}</td>
      <td> @if($coupon->coupon_type=="amount"){{ '₹ '.$coupon->coupon_value }}@else{{ $coupon->coupon_value.'%' }} @endif </td>
      <td>{{ date('d M Y',strtotime($coupon->to_time)) }}</td>
    </tr>
     @endforeach

  </table>
  
  </div>
   </div>
  </div>
  </div>
  </section>
 @endsection
