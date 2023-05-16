@extends('layouts.app')
@section('content')
<div class="container">
  <section class="home-banner">
    <div class="banner_slider">
        @foreach($Slider as $row)
            <div>
              <?php //echo "<pre>";print_r($row); die; ?>
                {{$row['slug']}}
                @if($row['link_type'] == 'internal')
                    <?php $url = '#'; ?>
                    @if($row['link_url_type'] == 'category')
                        <?php $url = 'list/';
                                $url = $url.$row['rawslug']; ?>
                    @endif
                    @if($row['link_url_type'] == 'subcategory')
                            <?php $url = 'list/';
                                $url =$url.$row['rawslug'] ; ?>
                    @endif
                    @if($row['link_url_type'] == 'product')
                          <?php $url = 'product/';
                           $url = $url.$row['rawslug'] ; ?>
                    @endif
                        <a href="{{url('/')}}/{{$url}}">
                @endif
                @if($row['link_type'] == 'external')
                        <?php $url = $row['link'];?>
                            <a href="{{$url}}">
                @endif

                        <img src="{{$row['image']}}" class="img-thumbnail" alt="{{$row['title']}}">
                    </a>
            </div>
        @endforeach
    </div>
  </section>  
</div>



<section class="categories-area">
<div class="container">

  <div class="alert alert-info">
 Minimum Amount  <strong>  ₹  {{$appdata->mim_amount_for_order}}  </strong>for order .
 <?php if(empty(Auth::user()->membership)){ ?>
 Minimum Amount  <strong> ₹   {{$appdata->mim_amount_for_free_delivery}}  </strong>Free delivery for Non-Prime User .<br />
<?php } else { ?>
 Minimum Amount <strong>  ₹  {{$appdata->mim_amount_for_free_delivery_prime}}  </strong>Free delivery for Prime User .<br />
<?php } ?>
</div>
</div>

<div class="container">
@if(!empty($super_deal))
<?php //echo "<pre>"; print_r($offer); echo "</pre>"; die; ?>
<div class="super-duper-offer today-savar-product">
<h2>Super Duper Offer ({{count($super_deal)}})</h2>
<ul class="today-savar-product-slider">
@foreach($super_deal as $koff=>$valoff)
<li>
<div class="super-duper-offer-box" style="{{($valoff['qty']==0)?'background-color: #fff;':''}}">
  @if(isset($valoff['discount']) && $valoff['discount']>0)
    <div class="product-absolute-options">
        <span class="offer-badge-1">{{$valoff['discount']}}% off</span>
    </div>
  @endif
  @if($valoff['qty']==0) <b style="COLOR: #000;"> OUT OF STOCK </b> @endif
  <?php if(isset($valoff['is_offer']) && !empty($valoff['is_offer'])) {?>
      <img src="{{url('/public/images/offer.png')}}" class="offer_image" alt="offer">
    <?php } ?>
  <a href="{{url('/product/'.$valoff['id'])}}">
     
  <img src="{{$valoff['product']['image']['name']}}" alt="img"></a>
  <?php if(Auth::user()){   ?>
    <a class="" >
     <span onclick="addtowhishlist({{isset($valoff['id'])?$valoff['id']:url('/')}})" class="heart-icon @if(!empty($valoff['wish_list'])) wishlist @endif">
            <i class="fa fa-heart" aria-hidden="true"></i>
        </span>
    </a> <?php }else{  ?>
   <a data-target="#login_with_mobile" data-toggle="modal" >
     <span   class="heart-icon">
            <i class="fa fa-heart" aria-hidden="true"></i>
        </span>
    </a>
<?php } ?>
</div>
<div class="super-duper-offer-content">
<p> ₹ {{$valoff['offer_price']}}<span class="discount-price"> ₹ {{$valoff['mrp']}}</span></p>
 <a href="{{url('/product/'.$valoff['id'])}}" >
<h4>@if(strlen($valoff['product']['name'])>22) {{substr($valoff['product']['name'], 0, 20)}}... @else {{ucfirst($valoff['product']['name'])}} @endif
 <span>{{$valoff['product']['measurement_value']}}{{$valoff['product']['measurement_class']['name']}}</span> 
</h4></a>
<!-- <button class="add-to-card-btn">Add To Cart</button> -->
<?php if(!empty($valoff['cart'])){ ?>
<div id="" class="cstm_qty_inpt">
<button type="button" id="sub" onclick="addtocart({{$valoff['id']}},'sub')" class="sub lft_btn_qty"><img src="{{url('public/images/arrow_left.png')}}" alt="arrow"></button>
<input type="number" id="cartval{{$valoff['id']}}" value="{{$valoff['cart']['qty']}}" />
<button type="button" id="add" onclick="addtocart({{$valoff['id']}},'add')"   class="add rt_btn_qty"><img src="{{url('public/images/arrow_right.png')}}" alt="arrow"></button>
</div>
<?php }else{ ?>
  @if($valoff['qty'] != 0)
    <button onclick="addtocart({{$valoff['id']}},1)" class="add-to-card-btn">Add To Cart</button>
  @endif

<?php } ?>
</div>
</li>
 @endforeach
</ul>
</div> 
@endif
</div>

@if(!empty($recentpurchage))
<div class="container"> 
<?php //echo "<pre>"; print_r($recentpurchage); echo "</pre>";  ?>
<div class="super-duper-offer today-savar-product">
<h2>Recently Purchased Items</h2>
<ul class="today-savar-product-slider">
@foreach($recentpurchage as $koff=>$valoff)
<li>
<div class="super-duper-offer-box" style="{{($valoff['qty']==0)?'background-color: #fff;':''}}">
  @if(isset($valoff['discount']) && $valoff['discount']>0)
    <div class="product-absolute-options">
        <span class="offer-badge-1">{{$valoff['discount']}}% off</span>
    </div>
  @endif
  @if($valoff['qty']==0) <b style="COLOR: #000;"> OUT OF STOCK </b> @endif
  <?php if(isset($valoff['is_offer']) && !empty($valoff['is_offer'])) {?>
      <img src="{{url('/public/images/offer.png')}}" class="offer_image" alt="offer">
    <?php } ?>
  <a href="{{url('/product/'.$valoff['id'])}}">
     
  <img src="{{$valoff['product']['image']['name']}}" alt="img"></a>
  <?php if(Auth::user()){   ?>
    <a class="" >
     <span onclick="addtowhishlist({{isset($valoff['id'])?$valoff['id']:url('/')}})" class="heart-icon @if(!empty($valoff['wish_list'])) wishlist @endif">
            <i class="fa fa-heart" aria-hidden="true"></i>
        </span>
    </a> <?php }else{  ?>
   <a data-target="#login_with_mobile" data-toggle="modal" >
     <span   class="heart-icon">
            <i class="fa fa-heart" aria-hidden="true"></i>
        </span>
    </a>
<?php } ?>
</div>
<div class="super-duper-offer-content">
<p> ₹ {{$valoff['offer_price']}}<span class="discount-price"> ₹ {{$valoff['mrp']}}</span></p>
 <a href="{{url('/product/'.$valoff['id'])}}" >
<h4>@if(strlen($valoff['product']['name'])>22) {{substr($valoff['product']['name'], 0, 20)}}... @else {{ucfirst($valoff['product']['name'])}} @endif
 <span>{{$valoff['product']['measurement_value']}}{{$valoff['product']['measurement_class']['name']}}</span> 
</h4></a>
<!-- <button class="add-to-card-btn">Add To Cart</button> -->
<?php if(!empty($valoff['cart'])){ ?>
<div id="" class="cstm_qty_inpt">
<button type="button" id="sub" onclick="addtocart({{$valoff['id']}},'sub')" class="sub lft_btn_qty"><img src="{{url('public/images/arrow_left.png')}}" alt="arrow"></button>
<input type="number" id="cartval{{$valoff['id']}}" value="{{$valoff['cart']['qty']}}" />
<button type="button" id="add" onclick="addtocart({{$valoff['id']}},'add')"   class="add rt_btn_qty"><img src="{{url('public/images/arrow_right.png')}}" alt="arrow"></button>
</div>
<?php }else{ ?>
  @if($valoff['qty'] != 0)
    <button onclick="addtocart({{$valoff['id']}},1)" class="add-to-card-btn">Add To Cart</button>
  @endif

<?php } ?>
</div>
</li>
 @endforeach
</ul>
</div>  
</div>

@endif

<div class="container">
@if(!empty($offer))
<?php //echo "<pre>"; print_r($offer); echo "</pre>"; die; ?>
<div class="must-have-product today-savar-product">
<h2>Deal of The Week({{count($offer)}})</h2>
<ul class="today-savar-product-slider">
@foreach($offer as $koff=>$valoff)
<li>
<div class="must-have-product-box" style="{{($valoff['qty']==0)?'background-color: #fff;':''}}">
  @if($valoff['qty']==0) <b style="COLOR: #000;"> OUT OF STOCK </b> @endif
  <?php if(isset($valoff['is_offer']) && !empty($valoff['is_offer'])) {?>
    <img src="{{url('/public/images/offer.png')}}" class="offer_image" alt="offer">
  <?php } ?>
  <a href="{{url('/product/'.$valoff['id'])}}">
  <img src="{{$valoff['product']['image']}}" alt="img"></a>
  <?php if(Auth::user()){   ?>
    <a class="" >
     <span onclick="addtowhishlist({{isset($valoff['id'])?$valoff['id']:url('/')}})" class="heart-icon @if(!empty($valoff['wish_list'])) wishlist @endif">
            <i class="fa fa-heart" aria-hidden="true"></i>
        </span>
    </a> <?php }else{  ?>
   <a data-target="#login_with_mobile" data-toggle="modal" >
     <span   class="heart-icon">
            <i class="fa fa-heart" aria-hidden="true"></i>
        </span>
    </a>
<?php } ?>
</div>
<div class="savar-product-content">
 
<p> ₹ {{$valoff['offer_price']}} <span class="discount-price"> ₹ {{$valoff['mrp']}} </span></p>
 <a href="{{url('/product/'.$valoff['id'])}}" >
<h4>@if(strlen($valoff['product']['name'])>22) {{substr($valoff['product']['name'], 0, 20)}}... @else {{ucfirst($valoff['product']['name'])}} @endif
 <span>{{$valoff['product']['measurement_value']}}{{$valoff['product']['measurement_class']['name']}}</span> 
</h4></a>
<!-- <button class="add-to-card-btn">Add To Cart</button> -->
<?php if(!empty($valoff['cart'])){ ?>
<div id="" class="cstm_qty_inpt">
<button type="button" id="sub" onclick="addtocart({{$valoff['id']}},'sub')" class="sub lft_btn_qty"><img src="{{url('public/images/arrow_left.png')}}" alt="arrow"></button>
<input type="number" id="cartval{{$valoff['id']}}" value="{{$valoff['cart']['qty']}}" />
<button type="button" id="add" onclick="addtocart({{$valoff['id']}},'add')"   class="add rt_btn_qty"><img src="{{url('public/images/arrow_right.png')}}" alt="arrow"></button>
</div>
<?php }else{ ?>
  @if($valoff['qty'] != 0)
    <button onclick="addtocart({{$valoff['id']}},1)" class="add-to-card-btn">Add To Cart</button>
  @endif

<?php } ?>
</div>
</li>
 @endforeach
</ul>
</div> 
@endif
</section>



<section class="advertisement-area ads-blk">
<div class="container">
<div class="ads-slider">
 @foreach($Ads as $ak=>$row)
  <div class="col-sm-4">
   <div class="add-box">
      {{$row['slug']}}
      @if($row['link_type'] == 'internal')
          <?php $url = '#'; ?>
      @if($row['link_url_type'] == 'category')
          <?php $url = 'list/';
          $url = $url.$row['rawslug']; ?>
      @endif
      @if($row['link_url_type'] == 'subcategory')
          <?php $url = 'list/';
          $url =$url.$row['rawslug'] ; ?>
      @endif
      @if($row['link_url_type'] == 'product')
          <?php $url = 'product/';
          $url = $url.$row['rawslug'] ; ?>
      @endif
          <a href="{{url('/')}}/{{$url}}">
      @endif
      @if($row['link_type'] == 'external')
          <?php $url = $row['link'];?>
          <a href="{{$url}}">
      @endif
  <img src="{{$row->image}}" alt="img"></a>
   </div>
  </div>
 @endforeach
</div>
</div>
</section>
<section class="advertisement-area ads-blk">
  <h3>Combo Offer</h3>
<div class="container">
<div class="ads-slider">
 @foreach($combo_offer as $ak=>$row)
  <div class="col-sm-4">
   <div class="add-box">
      {{$row['slug']}}
      @if($row['link_type'] == 'internal')
          <?php $url = '#'; ?>
      @if($row['link_url_type'] == 'category')
          <?php $url = 'list/';
          $url = $url.$row['rawslug']; ?>
      @endif
      @if($row['link_url_type'] == 'subcategory')
          <?php $url = 'list/';
          $url =$url.$row['rawslug'] ; ?>
      @endif
      @if($row['link_url_type'] == 'product')
          <?php $url = 'product/';
          $url = $url.$row['rawslug'] ; ?>
      @endif
          <a href="{{url('/')}}/{{$url}}">
      @endif
      @if($row['link_type'] == 'external')
          <?php $url = $row['link'];?>
          <a href="{{$url}}">
      @endif
  <img src="{{$valoff['product']['image']}}" alt="img"></a>
   </div>
  </div>
 @endforeach
</div>
</div>
</section>
<section class="categories-area">
<div class="container">
<div class="tab-area">
 <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#all-categories">All Categories</a></li>
   </ul>
<div class="tab-content">
    <div id="all-categories" class="tab-pane fade in active">
                    <ul>
                        @foreach($Category as $row)
                        <li><a href="{{ url('/list') }}/{{$row['slug']}}">
                                <div class="categories-product"><img src="{{$row['image']}}" alt="img"></div>
                                <h3>{{$row['name']}}</h3>
                            </a>
                        </li>
                        @endforeach

                    </ul>

                </div>
  
  </div>
</div>
</div>
</section>



@if($offer_sliders)
<section class="advertisement-area ads-blk">
<div class="container">
<div class="ads-slider">
 @foreach($offer_sliders as $ak=>$row)
  <div class="col-sm-4">
   <div class="add-box">
    {{$row['slug']}}
      @if($row['link_type'] == 'internal')
          <?php $url = '#'; ?>
      @if($row['link_url_type'] == 'category')
          <?php $url = 'list/';
          $url = $url.$row['rawslug']; ?>
      @endif
      @if($row['link_url_type'] == 'subcategory')
          <?php $url = 'list/';
          $url =$url.$row['rawslug'] ; ?>
      @endif
      @if($row['link_url_type'] == 'product')
          <?php $url = 'product/';
          $url = $url.$row['rawslug'] ; ?>
      @endif
          <a href="{{url('/')}}/{{$url}}">
      @endif
      @if($row['link_type'] == 'external')
          <?php $url = $row['link'];?>
          <a href="{{$url}}">
      @endif
  <img src="{{$row->image}}" alt="img"></a>
   </div>
  </div>
 @endforeach
</div>
</div>
</section>
@endif

<section class="bottom-product-area">
<div class="container">

@if(!empty($wish_list))
<?php //echo "<pre>"; print_r($wish_list); die;  ?>
<div class="must-have-product today-savar-product">
<h2>Your Favourite ({{ count($wish_list)}})</h2>
<ul class="most-searched-slider">
  @foreach($wish_list as $woff=>$waloff)
  <?php if(empty($waloff['VendorProduct']['product']['image']['name'])){ $img = url('/storage/app/public/upload/404.jpeg'); }else{
   $img =  $waloff['VendorProduct']['product']['image']['name'];
  } ?>
<li>
<div class="must-have-product-box" style="{{($waloff['VendorProduct']['qty']==0)?'background-color: #fff;':''}}">
  @if($waloff['VendorProduct']['qty']==0) <b style="COLOR: #000;"> OUT OF STOCK </b> @endif
  <?php if(isset($waloff['VendorProduct']['is_offer']) && !empty($waloff['VendorProduct']['is_offer'])) {?>
    <img src="{{url('/public/images/offer.png')}}" class="offer_image" alt="offer">
  <?php } ?>
  <a href="{{url('/product/'.$waloff['VendorProduct']['id'])}}">
    <img src="{{$img}}" alt="img"></a>
   <span   onclick="addtowhishlist({{$waloff['VendorProduct']['id']}})" class="heart-icon  wishlist ">
            <i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
   <p> ₹ {{number_format($waloff['VendorProduct']['offer_price'],2,'.','')}}<span class="discount-price"> ₹ {{number_format($waloff['VendorProduct']['mrp'],2,'.','')}}</span></p>
<a href="{{url('/product/'.$waloff['VendorProduct']['id'])}}">
<h4>@if(strlen($waloff['VendorProduct']['product']['name'])>20) {{substr($waloff['VendorProduct']['product']['name'], 0, 20)}}... @else {{ucfirst($waloff['VendorProduct']['product']['name'])}} @endif <span>{{$waloff['VendorProduct']['product']['measurement_value']}} {{$waloff['VendorProduct']['product']['MeasurementClass']['name']}}</span>  </h4>
</a>
<?php if(!empty($waloff['VendorProduct']['cart'])){ ?>
<div id="" class="cstm_qty_inpt">
<button type="button" id="sub" onclick="addtocart({{$waloff['VendorProduct']['id']}},'sub')" class="sub lft_btn_qty"><img src="{{url('public/images/arrow_left.png')}}" alt="arrow"></button>
<input type="number" id="cartval{{$waloff['VendorProduct']['id']}}" value="{{$waloff['VendorProduct']['cart']['qty']}}" />
<button type="button" id="add" onclick="addtocart({{$waloff['VendorProduct']['id']}},'add')"   class="add rt_btn_qty"><img src="{{url('public/images/arrow_right.png')}}" alt="arrow"></button>
</div>
<?php }else{ ?>
  @if($waloff['VendorProduct']['qty'] != 0)
    <button onclick="addtocart({{$waloff['VendorProduct']['id']}},1)" class="add-to-card-btn">Add To Cart</button>
  @endif
<?php } ?>
</li>
@endforeach
</ul>
</div>
@endif
</section>

  <section class="categories-area">
    <div class="container">
    @if(!empty($topsellings))
    <?php //echo "<pre>"; print_r($offer); echo "</pre>"; die; ?>
      <div class="must-have-product today-savar-product">
        <h2>Top Sellings</h2>
        <ul class="today-savar-product-slider">
          @foreach($topsellings as $topselling)          
          <li>
            <div class="must-have-product-box" style="{{($topselling['qty']==0)?'background-color: #fff;':''}}">
               @if($topselling['qty']==0) <b style="COLOR: #000;"> OUT OF STOCK </b> @endif
               <?php if(isset($topselling['is_offer']) && !empty($topselling['is_offer'])) {?>
                <img src="{{url('/public/images/offer.png')}}" class="offer_image" alt="offer">
              <?php } ?>
              <a href="{{url('/product/'.$topselling['id'])}}">
              <img src="{{$topselling['product']['image']}}" alt="img"></a>
              <?php if(Auth::user()){   ?>
                <a class="" >
                 <span onclick="addtowhishlist({{isset($topselling['id'])?$topselling['id']:url('/')}})" class="heart-icon @if(!empty($topselling['wish_list'])) wishlist @endif">
                        <i class="fa fa-heart" aria-hidden="true"></i>
                    </span>
                </a> <?php }else{  ?>
               <a data-target="#login_with_mobile" data-toggle="modal" >
                 <span   class="heart-icon">
                        <i class="fa fa-heart" aria-hidden="true"></i>
                    </span>
                </a>
            <?php } ?>
            </div>
            <div class="savar-product-content">
              <p> ₹ {{number_format($topselling['offer_price'],2,'.','')}}<span class="discount-price"> ₹ {{number_format($topselling['mrp'],2,'.','')}}</span></p>
              <a href="{{url('/product/'.$topselling['id'])}}" >
                <h4>@if(strlen($topselling['product']['name'])>22) {{substr($topselling['product']['name'], 0, 20)}}... @else {{ucfirst($topselling['product']['name'])}} @endif
                <span>{{$topselling['product']['measurement_value']}}{{$topselling['product']['MeasurementClass']['name']}}</span> 
                </h4>
              </a>
              <!-- <button class="add-to-card-btn">Add To Cart</button> -->
              <?php if(!empty($topselling['cart'])){ ?>
                <div id="" class="cstm_qty_inpt">
                  <button type="button" id="sub" onclick="addtocart({{$topselling['id']}},'sub')" class="sub lft_btn_qty"><img src="{{url('public/images/arrow_left.png')}}" alt="arrow"></button>
                  <input type="number" id="cartval{{$topselling['id']}}" value="{{$topselling['cart']['qty']}}" />
                  <button type="button" id="add" onclick="addtocart({{$topselling['id']}},'add')"   class="add rt_btn_qty"><img src="{{url('public/images/arrow_right.png')}}" alt="arrow"></button>
                </div>
              <?php }else{ ?>
                @if($topselling['qty'] != 0)
                <button onclick="addtocart({{$topselling['id']}},1)" class="add-to-card-btn">Add To Cart</button>
                @endif

              <?php } ?>
            </div>
          </li>
          @endforeach
        </ul>
      </div> 
      @endif
    </div>
  </section>

@endsection