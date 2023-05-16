<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14/7/17
 * Time: 6:08 PM
 */
?>

 <?php  $SiteSetting = Helper::globalSetting(); ?>
<section class="subscribe-area">
<div class="container">
<div class="subscribe-text">
<img src="https://zadcartbucket.s3-accelerate.amazonaws.com/subscribe-icon.png" alt="img">
<h2>Subscribe for our offer news</h2>
<p>...and receive 20 coupon for first shopping</p>
</div>
<div class="subscribe-form">
<?php if(!(Auth::user())){  ?>
  <input type="email" placeholder="Enter your Email Address" />
<a href="{{url('/register')}}"><input type="submit" value="Sign Up" /></a>
<?php } ?>
</div>
<div class="socialicon">
<ul>
<li><a href="{{ $SiteSetting->facebook_page }}"> <i class="fa fa-facebook" aria-hidden="true"></i> </a> </li>
<li><a href="{{ $SiteSetting->twitter_page }}"> <i class="fa fa-twitter" aria-hidden="true"></i> </a> </li>
<li><a href="{{ $SiteSetting->instagram_page }}"> <i class="fa fa-instagram" aria-hidden="true"></i> </a> </li>
<li><a href="{{ $SiteSetting->linkedin_page }}"> <i class="fa fa-pinterest-square" aria-hidden="true"></i> </a> </li>
</ul>
</div>
</div>
</section>

<footer>
<div class="container">
<div class="row">
<div class="col-md-4 col-sm-12">	
<div class="footer-leftcol">
<div class="footer-logo">
<img src="https://zadcartbucket.s3-accelerate.amazonaws.com/logo.png" alt="logo">	
<p><!-- It is a long established fact that a reader will be distracted by the readable content.--></p>	 
</div>	
<div class="detailbox">
<ul>
<li> 

<img src="https://zadcartbucket.s3-accelerate.amazonaws.com/call-icon.png" alt="img"> 
	<p>Need Help?
	<span>{{ $SiteSetting->phone}} {{empty($SiteSetting->mobile)?"":","}} {{$SiteSetting->mobile}}</span>
	</p>
</li>
<li> 
<img src="https://zadcartbucket.s3-accelerate.amazonaws.com/mail-icon.png" alt="img"> 
	<p>Email Address
	<span>{{ $SiteSetting->email }}</span>
	</p>
</li>	
</ul>
</div>	
</div>	
</div>	
	
<div class="col-md-8 col-sm-12">
<div class="row">
<div class="col-md-2 col-sm-2 col-xs-4">
<div class="footer-nav">
<h3>Quick links</h3>
<ul>

<li><a href="{{url('/about-us')}}">About us</a> </li>	
<li><a href="{{url('/support')}}">Contact us</a> </li>
<li><a href="{{url('/terms-and-condition')}}">Terms And Conditions </a> </li>	
<li><a href="{{url('/privacy-policy')}}">Privacy Policy</a> </li>
<li><a href="{{url('/faq')}}">FAQs</a> </li>
</ul>	
</div>
</div>
<div class="col-md-2 col-sm-2 col-xs-4">
<div class="footer-nav">
<h3>Category</h3>
<ul>
<li><a href="{{url('/offer/all')}}">Sale product</a> </li>		
<li><a href="{{url('/list/all')}}">Product Categories</a> </li>		
<li><a href="{{url('/recipe-landing')}}">Recipe Categories</a> </li>	
</ul>	
</div>
</div>
<div class="col-md-3 col-sm-2 col-xs-4">
<div class="footer-nav">
<h3>Customer Care</h3>
<ul>
  @if(Auth::user())
<li><a href="{{url('/profile')}}">My Account</a> </li>
<li><a href="{{url('/orderhistory')}}">Order History</a> </li>
<li><a href="{{url('/list/all')}}">Shop By products</a> </li>
<li><a href="{{url('/list/recipe-listing/all')}}">Shop By Recipes</a> </li>
<li><a href="{{url('/user/wishlist')}}">My Wishlist</a> </li>  
<li><a href="{{url('/mycart')}}">My Cart</a> </li>  
@else
<li><a data-target="#login_with_mobile" data-toggle="modal">My Account</a> </li>
<li><a data-target="#login_with_mobile" data-toggle="modal">Order History</a> </li>
<li><a data-target="#login_with_mobile" data-toggle="modal">Shop By products</a> </li>
<li><a data-target="#login_with_mobile" data-toggle="modal">Shop By Recipes</a> </li>
<li><a data-target="#login_with_mobile" data-toggle="modal">My Wishlist</a> </li>	
<li><a data-target="#login_with_mobile" data-toggle="modal">My Cart</a> </li> 
@endif	
</ul>	
</div>
</div>
<div class="col-md-5 col-sm-5 text-right">
<div class="footer-right-col">
<!-- 	<h3>Payment Method</h3>
<ul class="payment-icon">
<li><a href="#"><img src="https://zadcartbucket.s3-accelerate.amazonaws.com/mastcard-icon.png" alt="img"></a> </li>
<li><a href="#"><img src="https://zadcartbucket.s3-accelerate.amazonaws.com/visa-card-icon.png" alt="img"></a> </li>
<li><a href="#"><img src="https://zadcartbucket.s3-accelerate.amazonaws.com/paypal-icon.png" alt="img"></a> </li>
<li><a href="#"><img src="https://zadcartbucket.s3-accelerate.amazonaws.com/maestro-icon.png" alt="img"></a> </li>	
</ul> -->
<ul class="download-logo mt">
<h3>Download Mobile App</h3>	
<li><a href="{{$SiteSetting->app_url_android}}"><img src="https://zadcartbucket.s3-accelerate.amazonaws.com/google-play-icon.png" alt="img"></a> </li>	
<li><a href="{{$SiteSetting->app_url_ios}}"><img src="https://zadcartbucket.s3-accelerate.amazonaws.com/app-store.png" alt="img"></a> </li>		
</ul>	
</div>
</div>
</div>	
</div>	
</div>
</div>
<input type="hidden" id="baseurl" value="{{url('/')}}" >
<div class="copyright">
	Copyright © zadcart. All Rights Reserved
</div>	
</footer>
@push('scripts') 
      <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
    <script>
        function addtocart(productId,qty){
            var cartval = $('#cartval'+productId).val();
          //  alert(cartval);
            if(qty=='add' ){ qty = ++cartval;  }else if(qty=='sub'){  qty = --cartval; }
                $.ajax({
                    data: {
                        product_id : productId,  
                        qty : qty,  
                        _method:'POST'
                    },
                    type: "POST",
                    url: "{!! route('addtocart') !!}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( data ) {   
                        alertify.success(data.message);
                           window.location.reload();
                    },
                    error: function( data ) {
                            alertify.error("Please Login"); 
                            var baseurl = $('#baseurl').val();
                            window.location.href= baseurl+"/login";
                        }
             });  
         }

          function addtowhishlist(productId){
                $.ajax({
                    data: {
                        product_id : productId,  
                        _method:'POST'
                    },
                    type: "POST",
                    url: "{!! route('addtowishlist') !!}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( data ) {   
                       console.log(data);
                       alertify.success(data.message);
                       window.location.reload();
                    },
                    error: function( data ) {

                          alertify.error("Please Login"); 
                            var baseurl = $('#baseurl').val();
                            window.location.href= baseurl+"/login"; 
                      }
             });
         }
      
        
</script>
@endpush


