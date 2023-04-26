@extends('layouts.app')
@push('css')
<style type="text/css">
span.con_spn_prc_dtl.con_spn_prc_dtl_clr1{ color: #f93c3a;  font-weight: 500;}
.must-have-product{width: 100% !important}
</style>

@endpush

@section('content')


<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="{{url('/')}}">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>My Cart</li>	
	</ul>
	</div>	
</section>
   @if (Session::has('message'))
                    <div class="alert alert-info">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <p>{{ Session::get('message') }}</p>
                    </div>
                @endif
<section class="product-listing-body">
	<div class="container">
		<div class="row">
	
  		<div class="col-md-9 col-sm-8">

  			 <div class="wshlst_rt_mn clearfix">
            <h3>My Cart ({{$response['cart_count']}})</h3>   
            <ul>
                  @foreach($response['cart_list'] as $car_item )
                  <li>
                <div class="sngl_wshlst sngl_crt_n" style="{{($car_item['vendor_product']['qty']==0)?'background-color: #f6e8e8b3;':''}}">
                  <div class="crt_gnrl_dtl">
                    <div class="sngl_wshlst_img">
                       @if($car_item['vendor_product']['qty']==0) <b style="COLOR: #000;"> OUT OF STOCK </b> @endif
                      <?php if(empty($car_item['vendor_product']['product']['image'])){ ?>
                      <img src="{{url('/storage/app/public/upload/404.jpeg')}}" alt="product">
                      <?php }else{ //echo "<pre>"; print_r($car_item['vendor_product']['product']['image']); die; ?>
                      <img src="{{$car_item['vendor_product']['product']['image']['name']}}" alt="product">
                      <?php }?>
                    </div>
                    <div class="sngl_wshlst_con sngl_crt_sngl">
                      <a href="#" class="wshlst_a_tg">{{$car_item['vendor_product']['product']['name']}}</a>
                      <!-- <p class="crt_txt_desc">{{$car_item['vendor_product']['product']['description']}}</p> -->
                      <span class="qnty_spn">{{$car_item['vendor_product']['product']['measurement_value']}} {{$car_item['vendor_product']['product']['measurement_class']['name']}}</span>
                      <span class="slr_spn">Seller: {{$car_item['user_name']}}</span>
                      <?php if(!empty($car_item['is_offer'])){ ?>
                      <p>Discounted price: <span class="prc_orgnl">₹ {{$car_item['offer_price']}}</span><strike>₹ {{$car_item['price']}}</strike><a href="{{url('/offer/all')}}"><span class="dscnt_prc">{{$car_item['offer_data']['name']}}</a></span></p>
                      <?php }else{ ?>
                      <p>Price: <span class="dscnt_prc">₹ {{$car_item['price']}}</span></p>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="crt_dlvry_con">
                  </div> 
                  <div class="qty_sv_fr_ltr">
                    <ul>
                      <li>
                        <div id="" class="cstm_qty_inpt">
                          <button type="button" id="sub" onclick="addtocart({{$car_item['vendor_product']['id']}},'sub')" class="sub lft_btn_qty"><img src="{{url('public/images/arrow_left.png')}}" alt="arrow"></button>
                            <input type="number" id="cartval{{$car_item['vendor_product']['id']}}" value="{{$car_item['qty']}}" />
                            <button type="button" id="add" onclick="addtocart({{$car_item['vendor_product']['id']}},'add')"   class="add rt_btn_qty"><img src="{{url('public/images/arrow_right.png')}}" alt="arrow"></button>
                           </div>
                      </li>
                      <li>
                     <a class="" >
                      <?php //echo "<pre>"; print_r($car_item['vendor_product']); die;  ?>
                          <span   onclick="addtowhishlist({{$car_item['vendor_product']['id']}})" class="heart-icon @if(!empty($car_item['vendor_product']['wish_list'])) wishlist @endif">
                          <i class="fa fa-heart" aria-hidden="true"></i>
                          </span>
                          </a>  </li>
                          @if($car_item['vendor_product']['qty']==0)
                            <li>
                              <a href="#"  onclick="notifyMe({{$car_item['vendor_product']['id']}},0)" class="btn btn-success">Notify Me</a>
                            </li>
                          @endif
                      <li>
                        <a href="#"  onclick="addtocart({{$car_item['vendor_product']['id']}},0)" class="rmv_cls_clr">REMOVE</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </li>
              @endforeach
             
              <?php if(($response['cart_count']>0)){

                //echo $response['coupon_text']." ".$response['coupon_discount']; die;
                if(empty($response['coupon_discount'])){ ?>
                  <!-- Apply Promocode -->
                  <div class="form-group clearfix">
                    <div class="col-sm-12">
                      <h3>Promocode</h3>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="promocode" name="promocode" value="" placeholder="Enter Promocode">
                      </div>
                      <div class="col-sm-2">
                        <button type="button" class="common-btn" onclick="applypromocode()">Apply</button>
                      </div>
                    </div>
                  </div>
                  <!-- Apply Promocode -->
                 <?php }else{ ?>
                    <div class="form-group clearfix">
                    <div class="col-sm-12">
                      <h3>Promocode</h3>
                      
                      <div class="col-sm-12">
                        <button type="button" class="common-btn" onclick="removepromocode()">Remove Promocode</button>
                      </div>
                    </div>
                  </div>
             <?php } } ?>
                
              <?php 
              $globalSetting =  Helper::globalSetting();
              if($response['cart_count']>0){ ?>

         {!! Form::open(['route' => "order",'method'=>'post','class'=>'form-horizontal form-label-left validation']) !!}
                
            <div class="form-group clearfix">
            <div class="col-sm-12"> <br/><br/> 
            <h3>Your Order Type</h3> 
            <P class="custome-radio">
            <input checked="checked" type="radio" name="order_type" id="Schedule" value="Schedule">
            <label for="Schedule">Schedule( Selected Slots to  Schedule your delivery time )</label>
            </P> 
          <!--   <P class="custome-radio">
            <input  type="radio" name="order_type" id="Standard" value="Standard">
            <label for="Standard">Standard( {{$globalSetting->standard_delivery_time}} with Extra {{$globalSetting->standard_delivery_charges}} {{$globalSetting->currency}} )</label>
            </P> 
            <P class="custome-radio">
            <input disabled="disabled" type="radio" name="order_type" id="Express" value="Express">
            <label style="    color: #aaa;" for="Express">Express( {{$globalSetting->express_delivery_time}} with Extra {{$globalSetting->express_delivery_charges}} {{$globalSetting->currency}} )</label>
            </P> --> 
            </div>              
            </div>  
       
            <div class="plc_ord_crt">
              <input type="hidden" name="cart_total"  value="{{$response['total_price_amount']}}" />
             <button type="submit"  class="common-btn">NEXT>></button>
            </div>
              {!! Form::close() !!}

              <?php }else{ ?>
              <div class=" alert alert-info">Oops!! Your Cart is Empty</div>
              <div class="plc_ord_crt">
             <a href="{{url('/list/all')}}" ><button type="button"  class="common-btn">Continue Shopping</button></a>
            </div>
              <?php } ?>
         </div>
         <div class="row clearfix" style="display:none;">

            <div class="col-sm-12">                
              <?php //echo "<pre>"; print_r($offer); echo "</pre>"; die; ?>
                <div class="must-have-product today-savar-product">
                <ul class="today-savar-product-slider">
                @foreach($offer as $koff=>$valoff)
                <li>
                <div class="must-have-product-box" style="{{($valoff['qty']==0)?'background-color: #fff;':''}}">
                  <!-- @if($valoff['qty']==0) <b style="COLOR: #000;"> OUT OF STOCK </b> @endif -->
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
            </div>
         </div>

  		</div>
      <div class="col-md-3 col-sm-4">
        <div class="prc_dtl_rt">
          <h3>Price Details</h3>
          <ul>
            <li>
              <span class="ttl_prc_dtl">Price({{$response['cart_count']}} items)</span>
              <span class="con_spn_prc_dtl"> ₹  
                {{number_format($response['product_price'],2,'.','')}}
              </span>
            </li>
            <li>
              <span class="ttl_prc_dtl">Delivery Fee</span>
              @if($response['delivery_charge']>0)
                <span class="con_spn_prc_dtl con_spn_prc_dtl_clr1"> <b> + </b>
                    ₹ {{number_format($response['delivery_charge'],2,'.',',')}}</span></li>
              @else
                <span class="con_spn_prc_dtl con_spn_prc_dtl_clr" >{{ "Free" }}</span></li>
              @endif
                @if($response['darbaar_coin_price'] > 0)
                  <li>
                    <span class="ttl_prc_dtl">Darbaar Coin</span>
                    <span class="con_spn_prc_dtl con_spn_prc_dtl_clr1"> <b> - </b> ₹ {{number_format($response['darbaar_coin_price'],2,'.',',')}}</span>
                  </li>
                @endif

                @if(!empty($response['coupon_discount']))
                  <!-- Coupon Code -->
                  <li><span class="ttl_prc_dtl">
                    Coupon Dis.  
                     @if(!empty($response['coupon_text'])) ( {{ $response['coupon_text'] }} ) 
                      @endif
              </span>
              <span class="con_spn_prc_dtl con_spn_prc_dtl_clr"><b> - </b> ₹ {{$response['coupon_discount']}}</span></li>
            @endif
            <li class="ttl_amnt_li"><span class="ttl_prc_dtl">Total Amount</span><span class="con_spn_prc_dtl"> ₹ {{number_format($response['total_price'],2,'.','')}}</span></li>
          </ul>
          <p>You will save ₹ {{$response['total_saving']}}({{number_format($response['total_saving_percentage'],2,'.',"")}} %) on this order</p>
        <a class="btn btn-info" href="{{url('/couponList')}}">See all coupons</a>
        </div>

        <div class="sfty_txt_btm">
          <div class="shelid_img">
            <img src="public/images/sheild.png" align="shieild">
          </div>
          <p>Safe and secure payments. Easy returns. 100% Authentic Products.</p>
        </div>
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
        })
      
        // function addtocart(productId,qty){
        //     var cartval = $('#cartval'+productId).val();
        //   //  alert(cartval);
        //     if(qty=='add' ){ qty = ++cartval;  }else if(qty=='sub'){  qty = --cartval; }
        //         $.ajax({
        //             data: {
        //                 product_id : productId,  
        //                 qty : qty,  
        //                 _method:'POST'
        //             },
        //             type: "POST",
        //             url: "{!! route('addtocart') !!}",
        //             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //             success: function( data ) {   
        //                  $('div.flash-message').addClass('alert alert-success');
        //                  $('div.flash-message').html(data.message);
        //                //  $('#cartval'+productId).val(data.cart.qty);
        //                     window.location.reload();
        //             },
        //             error: function( data ) {
        //                 $('div.flash-message').addClass('alert  alert-danger');
        //                  $('div.flash-message').html(data.message);              
        //            }
        //      });
        //  }
      
        
</script>
@endpush