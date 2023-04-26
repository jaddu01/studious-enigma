@extends('layouts.app')
@section('content')
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

            <h3>{{$message}}</h3>
             <hr />
             <a href="{{url('/home')}}">Go to Home page</a>

</div>              
</div>  
</div>	
</section>
@endsection
