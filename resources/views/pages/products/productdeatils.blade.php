@extends('layouts.app')

@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
@endpush
@section('content')
<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>Shop</li>	
	</ul>
	</div>	
</section>

<section class="product-listing-body">
	<div class="container">
		<div class="detail-slider-area">
			
		<div class="item">            
            <div class="clearfix" style="max-width:500px;">
                <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
                    
					<li data-thumb="public/images/large-product01.jpg"> 
                        <img src="public/images/large-product01.jpg" />
                    </li>
					
					<li data-thumb="public/images/large-product01.jpg"> 
                        <img src="public/images/large-product01.jpg" />
                    </li>	
					
					<li data-thumb="public/images/large-product01.jpg"> 
                        <img src="public/images/large-product01.jpg" />
                    </li>
					
					<li data-thumb="public/images/large-product01.jpg"> 
                        <img src="public/images/large-product01.jpg" />
                    </li>	
					
					<li data-thumb="public/images/large-product01.jpg"> 
                        <img src="public/images/large-product01.jpg" />
                    </li>
					
					<li data-thumb="public/images/large-product01.jpg"> 
                        <img src="public/images/large-product01.jpg" />
                    </li>	
					
					<li data-thumb="public/images/large-product01.jpg"> 
                        <img src="public/images/large-product01.jpg" />
                    </li>
					
					<li data-thumb="public/images/large-product01.jpg"> 
                        <img src="public/images/large-product01.jpg" />
                    </li>					
                    
                </ul>
            </div>
        </div>
		
		<div class="detail-slider-content">
		
			
		<div class="detail-slider-topcontent">
		<button type="button">42% OFF</button>	
		<span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i></span>	
		<h2>Grofers Happy Day Tamato Chilli Sauce</h2>
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
		Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley	</p>
		</div>	
			
			<ul class="product-detail-content">
			<li class="line-through-text">$149.15</li>
			<li><h4>Discount Price: <span class="orange-text">$110.15</span> </h4> </li>
			<li>Seller: <span class="green-text">SuperComNet</span> </li>	
			<li> <label class="green-text">Availabel in:</label> <span class="waight-box">500 G</span> </li>
			<li class="quantity-box">
				<label>Quantity <br/>
				<input type="number" /> 
				</label>
				 
				<span><button type="button" class="common-btn">Add To Cart</button> </span>
				<span><button type="button" class="common-btn">Buy Now</button> </span>
				
				</li> 

			</ul>
			
		<div class="whyshop-content">
			<h3>Why shop from Zadcart?</h3>
			
		<ul>
		<li>
		<span class="whyshop-icon"><img src="public/images/easy-return.png" alt="img"></span>
		<h4>Easy returns & refunds</h4>
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
		</li>	
		
		
		<li>
		<span class="whyshop-icon"><img src="public/images/lowest-price.png" alt="img"></span>
		<h4>Lowest price guaranteed</h4>
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
		</li>	
			
		</ul>
			
			
		</div>
			
			
		</div>		
		
		<div class="bottom-product-detail-content">
			<h2>Product Detail</h2>
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley 
of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap 
into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of 
Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus 
PageMaker including versions of Lorem Ipsum.</p>	
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley 
of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap 
into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of 
Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus 
PageMaker including versions of Lorem Ipsum.</p>	
			</div>	
			
			
		</div>
		
		
		<div class="must-have-product related-product">
<h2>Related Product</h2>
<ul class="">

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>


</ul>
</div>
@endsection
@push('scripts')
<script src="public/js/lightslider.js"></script>	

<script>
    	 $(document).ready(function() {
			$("#content-slider").lightSlider({
                loop:true,
                keyPress:true
            });
            $('#image-gallery').lightSlider({
                gallery:true,
                item:1,
                thumbItem:4,
				thumbMargin: 5,
                slideMargin: 0,
                speed:500,
                auto:true,
                loop:true,
                onSliderLoad: function() {
                    $('#image-gallery').removeClass('cS-hidden');
                }  
            });
		});
    </script>
@endpush