@extends('layouts.app')
@section('content')
   @push('css')
    <style type="text/css">
    .style{ display: block !important; }
    </style>
@endpush
    <section class="home-banner">
    <div class="banner_slider">
        @foreach($Slider as $row)
            <div>
              <?php //echo "<pre>";print_r($row); die; ?>
                {{$row['slug']}}
                    <a href="{{url('/offer/all')}}">
                        <img src="{{$row['image']}}" class="img-thumbnail" alt="{{$row['title']}}">
                    </a>
            </div>
        @endforeach
    </div>
</section>
 <section class="topnave-bar">
        <div class="container">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
                <li>Shop By Products On offers</li>
                <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
                <li><a href="{{url('/offer/'.$slug)}}">{{ucfirst($slug)}}</a></li>
            </ul>
        </div>
    </section>
    <?php     $categories = Helper::Category_arr(); ?>
    <section class="product-listing-body">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-md-3">
                    <div class="browse-categories">
                        <div class="left_navtab">
                            <h2>Browse Categories</h2>
                            <div class="left_navarea">
                                <!-- Navigation -->
<div class="mainNav">
<ul>
<?php if($slug=='all'){ $class_all="active";}else{ $class_all=""; }?> 
<li  class="{{$class_all}}"><a href="{{url('/list/all')}}" >All</a></li>
@foreach( $categories as  $category)
<?php if(isset($slug)  && !empty($slug)  && ($slug==$category->slug)){ 
       $class_active="active"; $style='style'; 
      }else if(!empty($parent_data) && ($parent_data['slug']==$category->slug)){
        $class_active="active"; $style='style';  }else{ $style=$class_active=""; }?> 
<?php  $subcategories = Helper::SubCategory_arr($category->id); 
if(count($subcategories)>0){ $class=" has-subnav ".$slug; }else{ $class=""; }?> 
<li  class="{{$class}} {{$class_active}}"><a href="{{url('/offer/'.$category->slug)}}"> {{$category->name}}</a>
<?php    // echo "<pre>"; print_r($subcategories);// die;?>  
@if(!empty($subcategories)) 
<ul  class="{{$class}} {{$style}}" >
@foreach( $subcategories as  $subcat)
<?php if(isset($slug)  && !empty($slug)  && ($slug==$subcat->slug)){  $class_sub_active="selected"; }else{ $class_sub_active="";}?> 
<li class="{{$class_sub_active}}"><a href="{{url('/offer/'.$subcat->slug)}}">{{$subcat->name}}</a></li>
@endforeach
</ul>
@endif
</li>
<?php    // die;?>  
@endforeach
</ul>
</div>
                            </div>

                        </div>
                    </div>

                    <div class="listing-add-area">
                        <img src="{{url('/')}}/public/images/listing-add.png" alt="img">

                    </div>

                </div>

                <div class="col-sm-8 col-md-9">
                  <div class="flash-message alert-block"> <button class="close" data-dismiss="alert"></button></div>

                    <div class="must-have-product listing-product">

                        <h3>Offers</h3>
                        <hr />
                        <ul><?php  if(empty($products['product'])){
                                echo "<h4>No product found</h4>";
                            } else{ ?>
                            @foreach($products['product'] as $key => $row)
                                <li>   <?php //echo "<pre>"; print_r($row); die;
                                $productslug=""; 
                                  foreach($row['product']['translations'] as $trans){
                                  if($trans['locale']=='en') {//echo "<pre>"; print_r($trans); die;
                                  $productslug = $trans['slug']; }} ?>
                                    <div class="must-have-product-box" style="{{($row['qty']==0)?'background-color: #fff;':''}}">
                                        @if($row['qty']==0) <b style="COLOR: #000;"> OUT OF STOCK </b> @endif
                                        <?php if(isset($row['is_offer']) && !empty($row['is_offer'])) {?>
                                            <img src="{{url('/public/images/offer.png')}}" class="offer_image" alt="offer">
                                      <?php } ?>
                                        <a href="{{ url('/product/'.$row['id']) }}">
                                            @if(!empty($row['product']['image']))  <img src="{{$row['product']['image']}}" alt="img"  style="max-height: 200px;">
                                            @else   <img src="{{url('/storage/app/public/upload/404.jpeg')}}" alt="img">
                                            @endif
                                        </a>
                                        <!-- <a href="{{url('/offer/all')}}" class="upto-off"><?php if(strlen($row['offer_data']['name'])>8) { echo substr($row['offer_data']['name'],0,8).'...'; }else{ echo $row['offer_data']['name']; }?></a>  -->

                                        <a class="addtowishlist"  href="javascript:;" data-data="{{$row['product_id']}}">
                                         <span   onclick="addtowhishlist({{$row['id']}})" class="heart-icon @if($row['wish_list']) wishlist @endif">
                                                <i class="fa fa-heart" aria-hidden="true"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="savar-product-content">
                                <?php    foreach($row['product']['translations'] as $trans){
                                  if($trans['locale']=='en') {
                                  $productslug = $trans['slug']; }}
                                  ?>

                                        <a href="{{ url('/product') }}/{{$row['id']}}">
                                            @if($row['offer_price'] < $row['price'] )
                                                <p>₹ {{$row['offer_price']}} <span class="discount-price">₹ {{$row['price']}} </span></p>
                                            @else
                                                <p>₹ {{$row['price']}} </p>
                                             @endif
                                            <h4><?php if(strlen($row['product']['name'])>20){ echo substr($row['product']['name'],0,20)."..."; }else{ echo $row['product']['name']; }  ?></h4>
                                        </a>
                                        <p>{{$row['product']['measurement_value']}}{{$row['product']['measurement_class']['name']}}</p>
                                         <?php if(!empty($row['cart'])){ ?>
                      <div id="" class="cstm_qty_inpt">
                             <button type="button" id="sub" onclick="addtocart({{$row['id']}},'sub')" class="sub lft_btn_qty"><img src="{{url('public/images/arrow_left.png')}}" alt="arrow"></button>
                            <input type="number" id="cartval{{$row['id']}}" value="{{$row['cart']['qty']}}" />
                            <button type="button" id="add" onclick="addtocart({{$row['id']}},'add')"   class="add rt_btn_qty"><img src="{{url('public/images/arrow_right.png')}}" alt="arrow"></button>
                        </div>
                                         <?php }else{ ?>
                                            @if($row['qty'] != 0)
                                                <button onclick="addtocart({{$row['id']}},1)" class="add-to-card-btn">Add To Cart</button>
                                              @endif

                                        
                                        <?php } ?>
                                    </div>
                                </li>
                            @endforeach
                            <?php } ?>
                        </ul>
                        
                    </div>
                            <p>Total - {{$vProduct->total()}}( per page 16 products )</p>
                          @include('pagination.default', ['paginator' => $vProduct])
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts') 
      <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
    <script>
        function addtocart(productId,qty){
            var cartval = $('#cartval'+productId).val();
         //   alert(cartval);
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
                         $('div.flash-message').addClass('alert alert-success');
                         $('div.flash-message').html(data.message);
                        $('#cartval'+productId).val(data.cart.qty);
                        window.location.reload();
                    },
                    error: function( data ) {
                        $('div.flash-message').addClass('alert  alert-danger');
                         $('div.flash-message').html(data.message);              
                   }
             });
         }
        
</script>
@endpush