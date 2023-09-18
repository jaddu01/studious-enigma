@extends('layouts.app')
@section('content')
@push('css')
<style type="text/css">
    .style{ display: block !important; }
    .inactive {
     pointer-events: none;
     cursor: default;
 }
 li.selected{
    background: #ca0000;
}
</style>
@endpush
<section class="topnave-bar">
  <?php     $categories = Helper::Category_arr(); ?>
  <div class="container">
    <ul>
        <li><a href="{{url('/')}}">Home</a></li>
        <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li><a href="{{url('/list/all')}}">Shop By Categories</a></li>
        @if(!empty($parent_data))
        <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li><a href="{{url('/list/'.$slug)}}">{{ucfirst($parent_data['name'])}}</a></li>
        @endif
        <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li><a href="{{url('/list/'.$slug)}}">{{ucfirst($slug)}}</a></li>
    </ul>
</div>
</section>
<?php     $categories = Helper::Category_arr(); 
$parent_slugs = Helper::getParentCategories($slug);   
          //echo '<pre>';
          //print_r($parent_slugs);
?>
<section class="product-listing-body">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <div class="browse-categories">
                    <div class="nav">
                        <input type="checkbox" id="nav-check">
                        <div class="nav-header">
                            <div class="nav-title">
                                Filter
                            </div>
                        </div>
                        <div class="nav-btn">
                            <label for="nav-check">
                                <span></span>
                                <span></span>
                                <span></span>
                            </label>
                        </div>
                        <div class="nav-links">
                            <div class="left_navtab">
                              <h2>Browse Categories</h2>
                              <div class="left_navarea">
                                <!-- Navigation -->
                                <div class="mainNav">
                                    <ul>
                                        <?php if($slug=='all'){ $class_all="active";}else{ $class_all=""; }?> 
                                        <li  class="{{$class_all}}"><a href="{{url('/list/all')}}" >All</a></li>
                                        @foreach( $categories as  $category)
<?php //if(isset($slug)  && !empty($slug)  && ($slug==$category->slug)){ $selected_class="selected"; } else { $selected_class=""; }
if(in_array($category->slug,$parent_slugs)) { $class_all = 'active'; $selected_class="selected"; } else { $class_all = '';  $selected_class="";} ?> 
<?php  $subcategories = Helper::SubCategory_arr($category->id); 
if(count($subcategories)>0){ $class="  ".$slug; } else{ $class=""; }?> 
<li  class="{{$class_all}}"><a href="{{url('/list/'.$category->slug)}}" class="{{$selected_class}}"><img src="{{$category->image}}" alt="{{$category->name}}" style="max-width:15%;"/> {{$category->name}}</a>
    <?php    // echo "<pre>"; print_r($subcategories);// die;?>  
    @if(!empty($subcategories)) 
    <ul  class="{{($class_all=='active')?'show-ul':''}}" style="{{($class_all=='active')?'display:block':'display:none'}}">
        @foreach( $subcategories as  $subcat)
        <?php  $innercategories = Helper::SubCategory_arr($subcat->id); 
        if(count($innercategories)>0){ $class=" has-subnav ".$slug; }else{ $class=""; }
        if(in_array($subcat->slug,$parent_slugs)) { $class_all = 'active';  $selected_class="selected"; } else { $class_all = ''; $selected_class="";}?> 
        <li class="{{$class_all}}"><a href="{{url('/list/'.$subcat->slug)}}" class="{{$selected_class}}"><img src="{{$subcat->image}}" alt="{{$subcat->name}}" style="max-width:15%;"/> {{$subcat->name}}</a>
            @if(!empty($innercategories))
            <ul  class="{{($class_all=='active')?'show-ul':''}}" style="{{($class_all=='active')?'display:block':'display:none'}}">
                @foreach( $innercategories as  $incat)
                <?php if(isset($slug)  && !empty($slug)  && ($slug==$incat->slug)){  $selected_class="selected"; }else{ $selected_class="";}?>
                <li class=""><a href="{{url('/list/'.$incat->slug)}}" class="{{$selected_class}}"><img src="{{$incat->image}}" alt="{{$incat->name}}" style="max-width:15%;"/> {{$incat->name}}</a></li>
                @endforeach
            </ul>
        </li>
        @endif 
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

</div>
</div>
<script type="text/javascript">
    setTimeout(()=>{
        $('.show-ul').css('display','block');
    },2000);
    setTimeout(()=>{
        $('.show-ul').css('display','block');
    },3000);
    setTimeout(()=>{
        $('.show-ul').css('display','block');
    },4000);
    setTimeout(()=>{
        $('.show-ul').css('display','block');
    },5000);
    /*$('.mainNav ul ul li').hover(function () {
        $(this).addClass('li-hover');
    },
    function () {
        $(this).removeClass('li-hover');
    });*/
</script>
<div class="listing-add-area">
    @foreach($categoryAds as $row)
    <div>{{$row['slug']}}
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
              <img src="{{$row['image']}}" alt="{{$row['title']}}">
          </a>
      </div>
      @endforeach
  </div>
</div>

<div class="col-sm-8 col-md-9">
    <?php if(isset($category_data->banner_image) && $category_data->banner_image!=''){?>
        <div class="categories-banner">
            <img src="{{$category_data->banner_image}}" style="" />
        </div>
    <?php } ?>
    <div class="flash-message alert-block"> <button class="close" data-dismiss="alert"></button></div>
    <div class="must-have-product listing-product mt">
        <ul><?php  if(empty($products['product'])){
            echo "<h3>No product found</h3>";
        } else{ 

            // echo "<pre>";
            // print_r($products);
            // echo "</pre>";
            ?>
            @foreach($products['product'] as $key => $row)
    <li>   <?php //echo "<pre>"; print_r($row); die;
    $productslug=""; 
    if(isset($row['product']['translations'])){
      foreach($row['product']['translations'] as $trans){
      if($trans['locale']=='en') {//echo "<pre>"; print_r($trans); die;
      $productslug = $trans['slug']; }
  }}
  ?>
  <div class="must-have-product-box  {{$row['id']}}" style="{{($row['qty']==0)?'background-color: #fff;':''}}">
    @if($row['discount']>0)
    <div class="product-absolute-options">
        <span class="offer-badge-1">{{$row['discount']}}% off</span>
    </div>
    @endif
    @if($row['qty']==0) <b style="COLOR: #000;"> OUT OF STOCK </b> @endif
    <a href="{{ isset($row['id'])?url('/product/'.$row['id']):url('/') }}">
        @if(!empty($row['product']['image']))  <img src="{{$row['product']['image']}}" alt="img"  style="{{($row['qty']==0)?'opacity: 0.6;max-height: 200px;':'max-height: 200px;'}}">
        @else   <img src="{{url('/storage/app/public/upload/404.jpeg')}}" alt="img" style="{{($row['qty']==0)?'opacity: 0.6;':''}}">
        @endif
    </a>
    <?php if(!empty($row['offer_data'])){ ?>
     <a href="{{url('/offer/all')}}" class="upto-off"><?php if(strlen($row['offer_data']['name'])>8) { echo substr($row['offer_data']['name'],0,8).'...'; }else{ echo $row['offer_data']['name']; }?></a> 
 <?php }
 if(Auth::user()){   ?>
    <a class="" >
       <span   onclick="addtowhishlist({{isset($row['id'])?$row['id']:url('/')}})" class="heart-icon @if(!empty($row['wish_list'])) wishlist @endif">
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
<div class="savar-product-content" id="product-{{$row['id']}}" style="{{($row['qty']==0)?'background-color: #ddd;':''}}" >
    <?php  if(isset($row['product']['translations'])){
      foreach($row['product']['translations'] as $trans){
          if($trans['locale']=='en') {
              $productslug = $trans['slug']; }} } 
                            $memFlag = false;
                            if(!empty(Auth::user()->membership_to)){
                                $cu = date("Y-m-d h:i:s");
                                $date2=date_create(Auth::user()->membership_to);
                                $date1=date_create($cu);
                                $diff=date_diff($date1,$date2);
                                $lastd = $diff->format("%R%a");
                                if($lastd > 0)
                                {
                                    $memFlag = true;
                                } 
                            }
              ?>
              <a href="{{isset($row['id'])?url('/product/'.$row['id']):url('/') }}">
                @if($row['is_offer'])
                    <p> ₹ {{$row['offer_price']}}</p>
                    <p><span class="discount-price">₹ {{$row['mrp']}}</span></p>
                @else
                    <p>₹ {{$row['price']}}
                    </p>
                    <p>
                        @if($row['memebership_p_price'] > 0.00)
                            @if(Auth::user() && Auth::user()->membership && $memFlag)
                                <span>membership offer ₹ {{$row['memebership_p_price']}}</span>
                                @else
                                    <span><i class="fa fa-lock"></i> <a href="{{url('/membership')}}"> ₹ {{$row['memebership_p_price']}}</a></span>
                            @endif
                        @endif
                    </p>
                        <p><span class="discount-price">₹ {{$row['mrp']}}</span></p>
                @endif

                <h4>
                  <?php if(!empty($row['product']['name'])){
                   if(isset($row['product']['name']) & strlen($row['product']['name'])>20) { echo substr($row['product']['name'],0,18).'...'; }else{ echo $row['product']['name']; }
               }else{
                  echo "";
              }?>


              <span>{{isset($row['product']['measurement_value'])?$row['product']['measurement_value']:""}} {{isset($row['product']['measurement_class'])?$row['product']['measurement_class']['name']:""}}</span>
          </h4>
      </a>
      <?php 
      if(!empty($row['cart'])){ ?>
        <div id="" class="cstm_qty_inpt">
            <button type="button" id="sub" onclick="addtocart({{$row['id']}},'sub')" class="sub lft_btn_qty"><img src="{{url('public/images/arrow_left.png')}}" alt="arrow"></button>
            <input type="number" id="cartval{{$row['id']}}" value="{{$row['cart']['qty']}}" />
            <button type="button" id="add" onclick="addtocart({{$row['id']}},'add')"   class="add rt_btn_qty"><img src="{{url('public/images/arrow_right.png')}}" alt="arrow"></button>
        </div>
        @if($row['qty']==0) <button  onclick="notifyMe({{$row['id']}},0)" class="btn btn-success">Notify Me</button>  @endif
    <?php }else{
        if(Auth::user()){  ?>
            <button style="{{($row['qty']==0)?'background-color: #ddd;cursor: auto;':''}}" @if($row['qty']>0) onclick="addtocart({{isset($row['id'])?$row['id']:0}},1)" @endif class="add-to-card-btn">Add To Cart</button>
            @if($row['qty']==0) <button  onclick="notifyMe({{$row['id']}},0)" class="btn btn-success">Notify Me</button>  @endif
        <?php }else{ ?>
            <a data-target="#login_with_mobile" data-toggle="modal" >
                <button class="add-to-card-btn">Add To Cart </button>
            </a>
        <?php } ?><?php } ?>
    </div>
</li>
@endforeach
<?php } ?>
</ul>
</div>
<p> Total - {{ $vProduct->total() }}  (per page 16 products)</p>
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
  $('#preloader').css('display','block');
  $(document).ready(function () {
    $('#preloader').css('display','none');
});
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
                     // $('#product-'+productId).load(location.href+('#product-'+productId));

           window.location.reload();
       },
       error: function( data ) {
                    //  alert("Please Login TO ADD TO CART THIS PRODUCT");
        $('div.flash-message').addClass('alert  alert-danger');
        $('div.flash-message').html("Please Login TO Add This Product To Cart ");              
    }
});
}

</script>
@endpush
