@extends('layouts.app')
@section('content')
<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>Register</li>		
	</ul>
	</div>	
</section>

<section class="section-area">
<div class="container">	
<div class="delivery-time-box">
<h2>Hello <b>{{$data->name}}</b></h2>
<p>You are successfully registered to <b>Zcart</b>.</p>
<p>Please verify your account using OTP received on your phone number - <b>{{$data->phone_code}}-{{$data->phone_number}}</b></p>
<p> and enjoy services of <b>Zcart</b>.</p>		
<p><a target="_blank" href="{{ url('/') }}">LOGIN</a></p>
</div>	
</div>	
</section>
@endsection