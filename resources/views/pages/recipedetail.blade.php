@extends('layouts.app')
@section('content')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
@endpush
<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="{{url('/')}}">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li><a href="{{url('/recipe-landing')}}">Recipe</a></li>
	<?php if(!empty($recipe->recipe_category_data)){ ?>
	<li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li><a href="{{url('/list/recipe-listing/'.$recipe->recipe_category_data->slug)}}">{{$recipe->recipe_category_data->name}}</a></li>	
	<?php } ?>
	<li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
    <li><a href="{{url('/recipe/'.$slug)}}">{{ucfirst($slug)}}</a></li>
	</ul>
	</div>	
</section>

<section class="product-listing-body">
	<div class="container">
		<div class="detail-slider-area  recipe-slider-area">
		<div class="item">            
            <div class="clearfix" style="max-width:500px;">
                <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
                     @foreach($recipe->images as $image)
					<li data-thumb="{{$image->name}}"> 
                        <img src="{{$image->name}}" />
                    </li>
					@endforeach
					
                </ul>
            </div>
        </div>
		<div class="detail-slider-content">
		<div class="detail-slider-topcontent">
		<h2>{{$recipe->name}}</h2>
		<p>{{$recipe->description}}</div>
		<!-- <div class="ingradian-area"><h3>Ingredients</h3></div> -->
		</div>		
		</div>
		
		
<div class="must-have-product related-product">
<h2>Related Products</h2>
<div class="row">
@if(!empty($recipe->ingredients))
<?php  //print_r($recipe->ingredients); die; ?>
@foreach($recipe->ingredients->data as $ingredients  )
<div class="col-xs-15">
<div class="col-five-box">
	<?php foreach($ingredients['product']['translations'] as $kk=>$val){
		if($val['locale']=='en'){
			$ingredients['product']['slug'] = $val['slug'];
		}
	}  ?>
<div class="must-have-product-box"><a href="{{url('/product/'.$ingredients['product']['sku_code'])}}"><img src="{{$ingredients['product']['image']}}" alt="img"></a>  </div>
<div class="savar-product-content">
@if($ingredients['is_offer'])
<p>{{$ingredients['product']['price']}} QAR <span class="discount-price">{{$ingredients['product']['offer_price']}} QAR</span>  <span class="food-icon">
@else
<p>{{$ingredients['product']['price']}} QAR<span class="food-icon">
@endif
<!-- <img src="{{url('public/images/veg-food-icon.png')}}" alt="img"></span></p>
 --><a href="{{url('/product/'.$ingredients['product']['sku_code'])}}"><h4>{{(strlen($ingredients['product']['name'])>23)?substr($ingredients['product']['name'],0,20)."...":$ingredients['product']['name']}}</h4></a>
  <p>{{$ingredients['product']['measurement_value']}} {{$ingredients['product']['measurement_class']['name']}}</p>
  	<?php if(!empty($ingredients['cart'])){ ?>
	<div id="" class="cstm_qty_inpt">
	<button type="button" id="sub" onclick="addtocart({{$ingredients['product']['id']}},'sub')" class="sub lft_btn_qty"><img src="{{url('public/images/arrow_left.png')}}" alt="arrow"></button>
	<input type="number" id="cartval{{$ingredients['product']['id']}}" value="{{$ingredients['cart']['qty']}}" />
	<button type="button" id="add" onclick="addtocart({{$ingredients['product']['id']}},'add')"   class="add rt_btn_qty"><img src="{{url('public/images/arrow_right.png')}}" alt="arrow"></button>
	</div>
	<?php }else{ ?>
	<button onclick="addtocart({{$ingredients['product']['id']}},1)" class="add-to-card-btn">Add To Cart</button>
	<?php } ?>
	</div>
</div>	
</div>
@endforeach
@endif
</div>
 <p> Total - {{ $vProduct->total() }} Products</p>
</div>
  @include('pagination.default', ['paginator' => $vProduct])
</div>	
</section>
@endsection

@push('scripts')
<script src="{{url('public/js/lightslider.js')}}"></script>	

<script>
    	  $('#preloader').css('display','block');
      $(document).ready(function () {
        $('#preloader').css('display','none');
     
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