@extends('layouts.app')

@push('css')
    <link href="{{ asset('public/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endpush
@section('content')

    <section class="topnave-bar">
        <div class="container">
            <ul>
                <li><a href="{{ url('/') }}">Home</a> </li>
                <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
                <li>Shop</li>
            </ul>
        </div>
    </section>

    <section class="product-listing-body">
        <div class="container">
            <div class="detail-slider-area">

                <div class="item">
                    <?php if(isset($products['is_offer']) && !empty($products['is_offer'])) {?>
                    <img src="{{ url('/public/images/offer.png') }}" class="offer_image" alt="offer">
                    <?php } ?>
                    <div class="clearfix" style="max-width:500px;">
                        @if ($products['qty'] == 0)
                            <div class="out-of-stock">
                                <div class="outstk"><b> OUT OF STOCK</b> </div>
                            </div>
                        @endif
                        <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
                            <?php 
                    if(!empty($products['product']['images'])){

                        if($products['product']['images']->count() != '0'){  
                                foreach($products['product']['images'] as $image){  ?>
                            <li data-thumb="{{ $image['name'] }}">
                                <img src="{{ $image['name'] }}" />
                            </li>
                            <?php } }else{?>
                            <li data-thumb="{{ url('/storage/app/public/upload/404.jpeg') }}">
                                <img src="{{ url('/storage/app/public/upload/404.jpeg') }}" />
                            </li>
                            <?php }
                    }else{?>
                            <li data-thumb="{{ url('/storage/app/public/upload/404.jpeg') }}">
                                <img src="{{ url('/storage/app/public/upload/404.jpeg') }}" />
                            </li>
                            <?php }
                    ?>
                        </ul>
                    </div>
                </div>

                <div class="detail-slider-content">
                    <div class="detail-slider-topcontent">
                        <?php //echo "<pre>"; print_r($products['variantdata']); die;
                 if($products['is_offer']){  ?>
                        <button type="button">{{ $products['offer_data']['name'] }}</button>
                        <?php } 
           if(Auth::user()){ ?>
                        <span onclick="addtowhishlist({{ $products['id'] }})"
                            class="heart-icon  @if (!empty($products['wishList'])) wishlist @endif"><i class="fa fa-heart"
                                aria-hidden="true"></i></span>
                        <?php }else{ ?>
                        <a data-target="#login_with_mobile" data-toggle="modal">
                            <span class="heart-icon">
                                <i class="fa fa-heart" aria-hidden="true"></i>
                            </span>
                        </a>
                        <?php } 
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
            }?>
                        <h2>{{ $products['product']['name'] }}</h2>
                        <!-- <p>{{ $products['product']['description'] }}</p> -->
                    </div>
                    <ul class="product-detail-content">
                        <?php  
            if($products['membership'] !=''){ 
               // echo $products['membership']." string";
            } if($products['is_offer'] && ($products['mrp']>$products['offer_price'])){  ?>
                        <li class="line-through-text"> ₹ {{ $products['price'] }}</li>

                        <li>
                            <h4>Discounted Price: <span class="orange-text"> ₹ {{ $products['offer_price'] }}</span> </h4>
                        </li>
                        <?php }else{ ?>
                        <li>
                            <h4>Price: <span class="orange-text"> ₹ {{ $products['price'] }}</span> </h4>
                        </li>
                        <li>

                        </li>
                        <li class="line-through-text"> ₹ {{ $products['mrp'] }}</li>
                        <?php } ?>
                        @if ($products['discount'] > 0)
                            <li>Discount:
                                <span class="green-text">{{ $products['discount'] }}% off</span>
                            </li>
                        @endif
                        @if ($products['variantdata'])
                            <li>
                                <span>Color</span>
                                @foreach ($products['variantdata'] as $variantdata)
                                    <div class="container">
                                        <div class="selector">
                                            <div class="selecotr-item">
                                                <input type="radio" id="color_{{ $variantdata->id }}" name="color"
                                                    class="selector-item_radio" checked>
                                                <label for="color_{{ $variantdata->id }}"
                                                    class="selector-item_label">{{ $variantdata->color }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </li>
                            <li>
                                <span>Size</span>
                                @foreach ($products['variantdata'] as $variantdata)
                                    <div class="container">
                                        <div class="selector">
                                            <div class="selecotr-item">
                                                <input type="radio" id="size_{{ $variantdata->id }}" name="size"
                                                    class="selector-item_radio" checked>
                                                <label for="size_{{ $variantdata->id }}"
                                                    class="selector-item_label">{{ $variantdata->size }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </li>
                        @endif
                        <!-- <li>Seller: <span class="green-text">{{ $products['user']['name'] }} </label><li>  --><label
                            class="green-text">Available in:</label> <span
                            class="waight-box">{{ $products['product']['measurement_value'] }}
                            {{ $products['product']['MeasurementClass']['name'] }}</span> </li>

                        <li class="quantity-box">
                            <?php  //echo "<pre>";     print_r($products); die;
if(isset($products['cart']) &&  !empty($products['cart'])){ ?>
                            <div id="" class="cstm_qty_inpt">
                                <button type="button" id="sub" onclick="addtocart({{ $products['id'] }},'sub')"
                                    class="sub lft_btn_qty"><img src="{{ url('public/images/arrow_left.png') }}"
                                        alt="arrow"></button>
                                <input type="number" id="cartval{{ $products['id'] }}"
                                    value="{{ $products['cart']['qty'] }}" max='1' />
                                <button type="button" id="add" onclick="addtocart({{ $products['id'] }},'add')"
                                    class="add rt_btn_qty"><img src="{{ url('public/images/arrow_right.png') }}"
                                        alt="arrow"></button>
                            </div>
                            @if ($products['qty'] == 0)
                                <button onclick="notifyMe({{ $products['id'] }},0)" class="btn btn-success">Notify
                                    Me</button>
                            @endif <?php }else{
if(Auth::user()){ ?>
                            @if ($products['qty'] == 0)
                                <b> OUT OF STOCK</b>
                            @endif
                            <button @if ($products['qty'] == 0) style="cursor: auto;" @endif
                                @if ($products['qty'] > 0) onclick="addtocart({{ $products['id'] }},1)" @endif
                                class="add-to-card-btn">Add To Cart</button>
                            @if ($products['qty'] == 0)
                                <button onclick="notifyMe({{ $products['id'] }},0)" class="btn btn-success">Notify
                                    Me</button>
                            @endif
                            <?php }else{ ?>
                            <a data-target="#login_with_mobile" data-toggle="modal"><button class="add-to-card-btn">Add To
                                    Cart</button>
                            </a> <?php }} ?>
                        </li>
                    </ul>
                </div>
                <hr />

                <div class="bottom-product-detail-content">
                    <h2>Product Detail</h2>
                    <p>{{ $products['product']['description'] }}</p>
                    <h2>Shelf Life</h2>
                    <p>{{ $products['product']['self_life'] }}</p>
                    <h2>Manufacture Details</h2>
                    <p>{{ $products['product']['manufacture_details'] }}</p>
                    <h2>Marketed By</h2>
                    <p>{{ $products['product']['marketed_by'] }}</p>
                    <h4>Disclaimer</h4>
                    <p>{{ $products['product']['disclaimer'] }}</p>
                    <?php if($products['product']['expire_date']){?>
                    <h4>Expired on </h4>
                    <p>
                        {{ date_format(date_create($products['product']['expire_date']), 'Y-m-d') }}
                    </p>
                    <?php }?>
                </div>


    </section>
    <?php
    //print_r($products['related_products']);die;
    ?>
    @if (count($products['related_products']) > 0)
        <div class="container">

            <div class="must-have-product today-savar-product most-searche">
                <h2>Related Product</h2>
                <ul class="most-searched-slider">
                    <?php //echo "<pre>"; print_r($products['related_products']); die;
                    ?>
                    @if ($products['related_products'])
                        @foreach ($products['related_products'] as $related_product)
                            <?php //print_r($related_product->id); die;
                            ?>
                            @if ($related_product->Product)
                                <li>
                                    <div class="must-have-product-box"
                                        style="{{ $related_product->qty == 0 ? 'background-color: #fff;' : '' }}">
                                        @php
                                            //if($related_product['offer_price']>0) {
                                            //    $discount = ($related_product->price - $related_product['offer_price']) / $related_product->price;
                                            //} else {
                                            $discount = ($related_product->best_price - $related_product->price) / $related_product->best_price;
                                            //}
                                            
                                            $discount = $discount * 100;
                                            $discount = number_format($discount, 2, '.', '');
                                            if ($discount > 0) {
                                                echo '<div class="product-absolute-options"><span class="offer-badge-1">' . $discount . '% off</span></div>';
                                            }
                                        @endphp
                                        @if ($related_product->qty == 0)
                                            <b style="COLOR: #000;"> OUT OF STOCK </b>
                                        @endif
                                        <a href="{{ url('/product') }}/{{ $related_product->id }}"><img
                                                src="{{ $related_product->Product->image->name or '' }}"
                                                alt="img"></a>
                                        @if (Auth::user())
                                            <a class="addtowishlist" href="javascript:;"
                                                data-data="{{ $related_product->id }}">
                                                <span
                                                    class="heart-icon @if ($related_product->wishList) wishlist @endif">
                                                    <i onclick="addtowhishlist({{ $related_product->id }})"
                                                        class="fa fa-heart" aria-hidden="true"></i>
                                                </span>
                                            @else
                                                <a data-target="#login_with_mobile" data-toggle="modal">
                                                    <span class="heart-icon">
                                                        <i class="fa fa-heart" aria-hidden="true"></i>
                                                    </span>
                                                </a>
                                        @endif
                                        </a>
                                    </div>
                                    <div class="savar-product-content">
                                        <a href="{{ url('/product') }}/{{ $related_product->id }}">
                                            @if ($related_product->offer_id)
                                                <p>₹ {{ $related_product['offer_price'] }} <span class="discount-price">₹
                                                        {{ $related_product->price }}</span></p>
                                            @else
                                                <p>₹ {{ $related_product->price }} <span class="discount-price">₹
                                                        {{ $related_product->best_price }}</span></p>
                                            @endif
                                            <h4>{{ $related_product->Product->name }}</h4>
                                        </a>
                                        @if ($related_product->cart)
                                            <div id="" class="cstm_qty_inpt">
                                                <button type="button" id="sub"
                                                    onclick="addtocart({{ $related_product->id }},'sub')"
                                                    class="sub lft_btn_qty"><img
                                                        src="{{ url('public/images/arrow_left.png') }}"
                                                        alt="arrow"></button>
                                                <input type="number" id="cartval{{ $related_product->id }}"
                                                    value="{{ $related_product->cart->qty }}" />
                                                <button type="button" id="add"
                                                    onclick="addtocart({{ $related_product->id }},'add')"
                                                    class="add rt_btn_qty"><img
                                                        src="{{ url('public/images/arrow_right.png') }}"
                                                        alt="arrow"></button>
                                            </div>
                                        @else
                                            @if ($related_product->qty != 0)
                                                <button onclick="addtocart({{ $related_product->id }},1)"
                                                    class="add-to-card-btn">Add To Cart</button>
                                            @endif
                                        @endif
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>

    @endif

@endsection
@push('scripts')
    <script src="{{ url('public/js/jquery.min.js') }}"></script>
    <script src="{{ url('public/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('public/js/slick.js') }}"></script>
    <script src="{{ url('public/js/webslidemenu.js') }}"></script>
    <script src="{{ url('public/js/navAccordion.min.js') }}"></script>
    <script src="{{ url('public/js/lightslider.js') }}"></script>
    <script src="{{ url('public/js/custom.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('#preloader').css('display', 'none');
            $("#content-slider").lightSlider({
                loop: true,
                keyPress: true
            });
            $('#image-gallery').lightSlider({
                gallery: true,
                item: 1,
                thumbItem: 4,
                thumbMargin: 5,
                slideMargin: 0,
                speed: 500,
                auto: true,
                loop: true,
                onSliderLoad: function() {
                    $('#image-gallery').removeClass('cS-hidden');
                }
            });
        });
    </script>
@endpush
