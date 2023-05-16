<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14/7/17
 * Time: 6:08 PM
 */
?>

<?php 
  if(Auth::user()){
    $phone = Auth::user()->phone_number;
    $email = Auth::user()->email;
  }else{
    $phone = "";
    $email = "";
  }
?>

 <?php  $SiteSetting = Helper::globalSetting(); ?>
<section class="subscribe-area">
<div class="container">
<div class="subscribe-text">
<img src="{{asset('public/images/subscribe-icon.png')}}" alt="img">
<h2>Subscribe for our offer news</h2>
</div>
<div class="subscribe-form">
<?php if(!(Auth::user())){  ?>
  <input type="text" placeholder="Enter your Email Address" />
<a href="{{url('/register')}}"><input type="submit" value="Subscribe Now" /></a>
<?php } ?>
</div>
<div class="socialicon">
<ul>
<li><a href="{{ $SiteSetting->facebook_page }}"> <i class="fa fa-facebook" aria-hidden="true"></i> </a> </li>
<li><a href="{{ $SiteSetting->twitter_page }}"> <i class="fa fa-twitter" aria-hidden="true"></i> </a> </li>
<li><a href="{{ $SiteSetting->instagram_page }}"> <i class="fa fa-instagram" aria-hidden="true"></i> </a> </li>
<li><a href="{{ $SiteSetting->linkedin_page }}"> <i class="fa fa-linkedin" aria-hidden="true"></i> </a> </li>
<li><a href="{{ $SiteSetting->youtube }}"> <i class="fa fa-youtube-play" aria-hidden="true"></i> </a> </li>
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
<img src="{{asset('storage/app/public/upload/logo.png')}}" alt="logo">	
<p><!-- It is a long established fact that a reader will be distracted by the readable content.--></p>	 
</div>	
<div class="detailbox">
<ul>
<li> 

<img src="{{asset('public/images/call-icon.png')}}" alt="img"> 
	<p>Need Help?
	<span>{{ $SiteSetting->phone}} {{empty($SiteSetting->mobile)?"":","}} {{$SiteSetting->mobile}}</span>
	</p>
</li>
<li> 
<img src="{{asset('public/images/mail-icon.png')}}" alt="img"> 
	<p>Email Address
	<span>{{ $SiteSetting->email }}</span>
	</p>
</li>
<li>
    <img src="/storage/app/public/upload/{{$SiteSetting->prime_memebership_image->image}}" style="width:15%" />
    <p>
        <a href="/membership"> {{$SiteSetting->prime_memebership_image->title}} </a>
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
<h3>Customer Care</h3>
<ul>

<li><a href="{{url('/about-us')}}">About us</a> </li>	<!-- 
<li><a href="{{url('/support')}}">Contact us</a> </li> -->
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
<!-- <li><a href="{{url('/offer/all')}}">Sale product</a> </li>		 -->
<li><a href="{{url('/list/all')}}">Product Categories</a> </li>		
</ul>	
</div>
</div>
<div class="col-md-3 col-sm-2 col-xs-4">
<div class="footer-nav">
<h3>Quick links</h3>
<ul>
  @if(Auth::user())
<li><a href="{{url('/profile')}}">My Account</a> </li>
<li><a href="{{url('/orderhistory')}}">Order History</a> </li>
<!-- <li><a href="{{url('/list/all')}}">Shop By products</a> </li> -->
<li><a href="{{url('/user/wishlist')}}">My Wishlist</a> </li>  
<li><a href="{{url('/mycart')}}">My Cart</a> </li>  
@else
<li><a data-target="#login_with_mobile" data-toggle="modal">My Account</a> </li>
<li><a data-target="#login_with_mobile" data-toggle="modal">Order History</a> </li>
<!-- <li><a data-target="#login_with_mobile" data-toggle="modal">Shop By products</a> </li> -->
<li><a data-target="#login_with_mobile" data-toggle="modal">My Wishlist</a> </li>	
<li><a data-target="#login_with_mobile" data-toggle="modal">My Cart</a> </li> 
@endif	
</ul>	
</div>
</div>
<div class="col-md-5 col-sm-5 text-right">
<div class="footer-right-col">
<h3>Payment Method</h3>
<ul class="payment-icon">
<li><a href="#"><img src="{{asset('public/images/mastcard-icon.png')}}" alt="img"></a> </li>
<li><a href="#"><img src="{{asset('public/images/visa-card-icon.png')}}" alt="img"></a> </li>
<li><a href="#"><img src="{{asset('public/images/paypal-icon.png')}}" alt="img"></a> </li>
<li><a href="#"><img src="{{asset('public/images/maestro-icon.png')}}" alt="img"></a> </li> 
</ul>
<ul class="download-logo mt">
<h3>Download Mobile App</h3>	
<li><a href="{{$SiteSetting->app_url_android}}"><img src="{{asset('public/images/google-play-icon.png')}}" alt="img"></a> </li>	
<li><a href="{{$SiteSetting->app_url_ios}}"><img src="{{asset('public/images/app-store.png')}}" alt="img"></a> </li>		
</ul>	
</div>
</div>
</div>	
</div>	
</div>
</div>
<input type="hidden" id="baseurl" value="{{url('/')}}" >
<div class="copyright">
	Copyright © Darbaar Mart. All Rights Reserved
</div>	
</footer>
@push('scripts') 
      <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
    <script>
        function notifyMe(productId,qty){
            var cartval = $('#cartval'+productId).val(); 
            
               

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('notifyme') }}",
                    type: 'POST',
                    data: {
                       vendor_product_id : productId,  
                        qty : qty, 
                    },
                    success: function(data) {
                        alertify.success(data.message); 
                    },
                    error: function() {
                        console.log('There is some error in user deleting. Please try again.');
                    }
                    });
         }
        function removeOutStock(zone_id){
             
                $.ajax({
                    data: {
                        zone_id : zone_id,
                        _method:'POST'
                    },
                    type: "POST",
                    url: "{!! route('removeOutStock') !!}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( data ) {   
                        alertify.success(data.message); 
                        console.log(data.data);
                        // window.location.reload();
                    },
                    error: function( data ) {
                            alertify.error("Please Login"); 
                            var baseurl = $('#baseurl').val();
                           window.location.href= baseurl+"/login";
                        }
             });  
         }
        function addtocart(productId,qty){
            var cartval = $('#cartval'+productId).val();
          //  alert(cartval);
            if(qty=='add' ){ qty = ++cartval;  }else if(qty=='sub'){  qty = --cartval; }
                $.ajax({
                    data: {
                        vendor_product_id : productId,  
                        qty : qty,  
                        _method:'POST'
                    },
                    type: "POST",
                    url: "{!! route('addtocart') !!}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( data ) {   
                        alertify.success(data.message);
                        
                    
//$('.must-have-product').load(document.URL +  ' .must-have-product');

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
                        vendor_product_id : productId,  
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
<script>
  function applypromocode(){
    var promocode = $('#promocode').val();
    //alert(promocode);
    if(promocode != ""){
      $.ajax({
            data: {
                promocode : promocode,
                _method:'POST'
            },
            type: "POST",
            url: "{!! route('applypromocode') !!}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( data ) {
              console.log(data);
              if(data.error){
                alertify.error(data.message);
              }else{
                alertify.success(data.message);
                window.location.reload();
              } 
              //
            },
            error: function( data ) {
                alertify.error("Please Login"); 
                var baseurl = $('#baseurl').val();
               //window.location.href= baseurl+"/login";
            }
     });
    }else{
      alertify.error('Please Enter Promocode');
    }   
 }
   function removepromocode(){
   
      $.ajax({
            data: {
            
                _method:'GET'
            },
            type: "GET",
            url: "{!! route('removepromocode') !!}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( data ) {
            //  console.log(data);
              if(data.error){
                alertify.error(data.message);
              }else{
                alertify.success(data.message);
                window.location.reload();
              } 
              
            },
            error: function( data ) {
                alertify.error("Please Login"); 
                var baseurl = $('#baseurl').val();
               //window.location.href= baseurl+"/login";
            }
     });
   
 }
</script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
   var SITEURL = '{{URL::to('')}}';
   $.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': "{!!csrf_token()!!}"
     }
   }); 
   $('body').on('click', '.buy_now', function(e){
     var totalAmount = $(this).attr("data-price");
     var membership_id =  $(this).attr("data-id");
     var options = {
       "key": "{{ env('razor_key') }}",
       "amount": (totalAmount*100), // 2000 paise = INR 20
       "name": "Membership Recharge",
       "description": "Membership Recharge Payment",
       "image": "{{asset('storage/app/public/upload/logo.png')}}",
       "handler": function (response){
           $.ajax({
             url: SITEURL + '/membership-success',
             type: 'post',
             dataType: 'json',
             data: {
              razorpay_payment_id: response.razorpay_payment_id , 
               totalAmount : totalAmount ,membership_id : membership_id,
             }, 
             success: function (msg) {
                 window.location.href = SITEURL + '/membership-thank-you';
             },
             error:function(argument) {
               alertify.error("Server Error Occured!");
               window.location.href = SITEURL + '/membership';
             }
         });
       
       },
      "prefill": {
           "contact": "<?= $phone; ?>",
           "email":   "<?= $email; ?>",
       },
       "theme": {
           "color": "#ae2220"
       }
     };
   var rzp1 = new Razorpay(options);
   rzp1.open();
   e.preventDefault();
   });
</script>
<script>
        $(".login_with_mobile").keypress(function (e) {
           
          //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57 )) {
            //display error message
            $(".has_error").html("Digits Only").show().fadeOut("slow");
            return false;
        }
        var number=$(this).val();
        var compare=/[0-9]{10}/;
        if(number.match(compare)){
          return false;
        }

     });
    </script>
@endpush


