@extends('layouts.app')
@section('content')

 <?php  $SiteSetting = Helper::globalSetting();   ?>
<section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="{{url('/')}}">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li>Support</li>   
    </ul>
    </div>  
</section>

<section class="product-listing-body">
    <div class="container">
        <div class="row">
        <div class="col-md-12">
            <h3>Contact us with</h3>   
            <hr/>
<div class="detailbox">
<ul>
<li> 

<img src="{{asset('public/images/call-icon.png')}}" alt="img"> 
    <p>Need Help?
    <span>{{ $SiteSetting->phone}},{{$SiteSetting->mobile}}</span>
    </p>
</li>
<li> 
<img src="{{asset('public/images/mail-icon.png')}}" alt="img"> 
    <p>Email Address
    <span>{{ $SiteSetting->email }}</span>
    </p>
</li>   
</ul>
</div>
            
         </div>
        </div>
        </div>
</section>

@endsection