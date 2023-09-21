<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14/7/17
 * Time: 6:09 PM
 */
?>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{url('/admin/dashboard')}}" class="site_title"><i class="fa fa-paw"></i>
                <span>{{config('setting.name')}}</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile">

            <div class="profile_pic">
                <?php  if(Auth::guard('admin')->user()->image){$image = Auth::guard('admin')->user()->image;} ?>
                    @if(Auth::guard('admin')->user()->image)
                      <img src="{{ url('storage/app/public/upload/'.$image) }}" alt="..." class="img-circle profile_img">
                    @else
                     <img src="{{asset('public/images/img.jpg')}}" alt="..." class="img-circle profile_img">
                    @endif
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ Auth::guard('admin')->user()->name }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br/>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
               <h3>&nbsp;</h3>
                <ul class="nav side-menu">
                    <!-- POS -->
                    <li>
                        <a><i class="fa fa-file"></i>POS  <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\PosOrders::class))
                                <li><a><i class="fa fa-image"></i>Orders <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\PosOrders::class))
                                        <li>
                                            <a href="{{url('admin/pos/orders/create')}}">Add Orders</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\PosOrders::class))
                                        <li>
                                            <a href="{{url('admin/pos/orders')}}">View Orders</a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>

                            @endif
                            <li>
                                <a><i class="fa fa-file"></i>Purchase  <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/pos/purchase/create')}}">Add Purchase</a></li>
                                    <li><a href="{{url('admin/pos/purchase/')}}">View Purchase</a></li>
                                </ul>
                            </li>
                            <li>
                                <a><i class="fa fa-file"></i>Expenses  <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/pos/expenses/create')}}">Add Expenses</a></li>
                                    <li><a href="{{url('admin/pos/expenses/')}}">View Expenses</a></li>
                                </ul>
                            </li>
                            <li>
                                <a><i class="fa fa-file"></i>Reports  <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/pos/reports/sales')}}">Sales</a></li>
                                    <li><a href="{{url('admin/pos/reports/purchase')}}">Purchase</a></li>
                                    <li><a href="{{url('admin/pos/reports/expenses')}}">Expenses</a></li>
                                </ul>
                            </li>

                            <li>
                                <a><i class="fa fa-users"></i>Users<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/pos/users')}}">View All</a></li>
                                    <li><a href="{{url('admin/pos/users/create')}}">Add New</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- Customer -->
                    @if (!Auth::guard('admin')->user()->can(['index'], App\User::class))
                        <li><a href="{{url('admin/customer')}}"><i class="fa fa-users"></i>Customers</a></li>
                    @endif
                    
                    <!-- Orders -->
                    @if (!Auth::guard('admin')->user()->can(['index'], App\ProductOrder::class))
                        <li><a href="{{url('admin/order')}}"><i class="fa fa-shopping-cart"></i>Order </a></li>
                    @endif
                    
                    <!-- Products -->
                    @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Product::class))
                        <li><a><i class="fa fa-product-hunt"></i>Products <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                @if (!Auth::guard('admin')->user()->can(['create'], App\Product::class))
                                <li>
                                    <a href="{{url('admin/product/create')}}">Add Product</a>
                                </li>
                                @endif
                                @if (!Auth::guard('admin')->user()->can(['view'], App\Product::class))
                                <li>
                                    <a href="{{url('admin/product')}}">View Products</a>
                                </li>
                                @endif
                                @if (!Auth::guard('admin')->user()->can(['view'], App\Product::class))
                                {{-- <li>
                                    <a href="{{url('admin/product/variantview')}}">Product Variant</a>
                                </li> --}}
                                @endif

                            </ul>
                        </li>
                    @endif

                    <!-- Store Products -->
                    @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\VendorProduct::class))
                        {{-- <li><a><i class="fa fa-product-hunt"></i>Store Product <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                @if (!Auth::guard('admin')->user()->can(['create'], App\VendorProduct::class))
                                <li>
                                    <a href="{{url('admin/vendor-product/create')}}">Add store-product</a>
                                </li>
                                @endif
                                @if (!Auth::guard('admin')->user()->can(['view'], App\VendorProduct::class))
                                <li>
                                    <a href="{{url('admin/vendor-product')}}">View store-product</a>
                                </li>
                                @endif

                            </ul>
                        </li> --}}
                    @endif
                    
                    <!-- Categories -->
                    @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Category::class))
                        <li><a><i class="fa fa-list-alt"></i>Categories <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                @if (!Auth::guard('admin')->user()->can(['create'], App\Category::class))
                                <li>
                                    <a href="{{url('admin/category/create')}}">Add Category</a>
                                </li>
                                @endif
                                @if (!Auth::guard('admin')->user()->can(['view'], App\Category::class))
                                <li>
                                    <a href="{{url('admin/category')}}">View Categories</a>
                                </li>
                                @endif
                                
                                

                            </ul>
                        </li>
                    @endif

                    <!-- Media -->
                    <li>
                        <a><i class="fa fa-users"></i>Media <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <!-- Banner -->
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Slider::class))
                                <li><a><i class="fa fa-image"></i>Banners <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\Slider::class))
                                        <li>
                                            <a href="{{url('admin/slider/create')}}">Add Banner</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\Slider::class))
                                        <li>
                                            <a href="{{url('admin/slider')}}">View Banner</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                    
                            <!-- Offer Banner -->
                             @if (!Auth::guard('admin')->user()->can(['index','create','view'], App\OfferSlider::class))
                                <li><a><i class="fa fa-image"></i>Offer Banners <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\OfferSlider::class))
                                        <li>
                                            <a href="{{url('admin/offer-slider/create')}}">Add Banner</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\OfferSlider::class))
                                        <li>
                                            <a href="{{url('admin/offer-slider')}}">View Banner</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <!-- Ads -->
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Ads::class))
                                <li><a><i class="fa fa-image"></i>Ads <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\Ads::class))
                                        <li>
                                            <a href="{{url('admin/ads/create')}}">Add Ads</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\Ads::class))
                                        <li>
                                            <a href="{{url('admin/ads')}}">View Ads</a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            <!-- How it works -->
                             @if (!Auth::guard('admin')->user()->can(['index','create','view'], App\OfferSlider::class))
                                <li><a><i class="fa fa-image"></i>How It Works <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\OfferSlider::class))
                                        <li>
                                            <a href="{{url('admin/how-it-works/create')}}">Add How It Works Image</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\OfferSlider::class))
                                        <li>
                                            <a href="{{url('admin/how-it-works')}}">View How It Works Image</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <!-- Category Ad Image -->
                            <li>
                                <a href="{{url('admin/category-image-ads/edit/1')}}">Categories Ads Image</a>
                            </li>
                            <!-- Membership Photo -->
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Media::class))
                                <li><a><i class="fa fa-image"></i>Prime Membership Image <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <!-- @if (!Auth::guard('admin')->user()->can(['create'], App\Media::class))
                                        <li>
                                            <a href="{{url('admin/medias/create')}}">Add Image</a>
                                        </li>
                                        @endif -->
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\Media::class))
                                        <li>
                                            <a href="{{url('admin/medias/prime-memembership-images')}}">View Image</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <!-- Refer & Earn Photo -->
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Media::class))
                                <li><a><i class="fa fa-image"></i>Refer Image <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <!-- @if (!Auth::guard('admin')->user()->can(['create'], App\Media::class))
                                        <li>
                                            <a href="{{url('admin/medias/create')}}">Add Image</a>
                                        </li>
                                        @endif -->
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\Media::class))
                                        <li>
                                            <a href="{{url('admin/medias/refer-images')}}">View Image</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                    
                    <!-- Group 1 -->
                    <li>
                        <a><i class="fa fa-users"></i>Group 1 <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <!-- Shoppers /Drivers / Stores -->
                            @if (!Auth::guard('admin')->user()->can(['create','view','delete','update'], App\User::class))
                                <li><a><i class="fa fa-users"></i>Shoppers/Drivers/Stores <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\User::class))
                                        <li>
                                            <a href="{{url('admin/user/create')}}">Add New</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\User::class))
                                        <li>
                                            <a href="{{url('admin/user')}}">View All</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <!-- Zone Management -->
                            @if (!Auth::guard('admin')->user()->can(['create','view','delete','update'], App\Zone::class))
                                <li><a><i class="fa fa-area-chart"></i>Zone Management <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\Zone::class))
                                        <li>
                                            <a href="{{url('admin/zone/create')}}">Add Zone</a>
                                        </li>
                                        @endif

                                        @if (!Auth::guard('admin')->user()->can(['view'], App\Zone::class))
                                        <li>
                                            <a href="{{url('admin/zone')}}">View Zone</a>
                                        </li>
                                        @endif

                                        @if (!Auth::guard('admin')->user()->can(['opration'], App\Zone::class))
                                        <li>
                                            <a href="{{url('admin/operation')}}">Operation</a>
                                        </li>
                                        @endif
                                          @if (!Auth::guard('admin')->user()->can(['opration'], App\Zone::class))
                                    <!--     <li>
                                            <a href="{{url('admin/view-tracking')}}">View Tracking</a>
                                        </li> -->
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <!-- Tracking -->
                            @if (!Auth::guard('admin')->user()->can(['index'], App\User::class))
                                <li><a><i class="fa fa-map-marker"></i>Tracking <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                         <li>
                                            <a href="{{url('admin/tracking/driver')}}">Driver</a>
                                        </li>
                                       
                                        <li>
                                            <a href="{{url('admin/tracking/shopper')}}">Shopper</a>
                                        </li>
                                         <li>
                                            <a href="{{url('admin/tracking/heatmap')}}">Order Hitmap</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/tracking/customer-heatmap')}}">Customer Addresses Hitmap</a>
                                        </li>
                             
                                    </ul>
                                </li>
                            @endif
                            <!-- City -->
                            @if (!Auth::guard('admin')->user()->can(['create','view','delete','update'], App\City::class))
                                <li><a><i class="fa fa-building-o"></i>City <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\City::class))
                                        <li>
                                            <a href="{{url('admin/city/create')}}">Add City</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\City::class))
                                        <li>
                                            <a href="{{url('admin/city')}}">View Cities</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                    
                            <!-- Region -->
                             @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Region::class))
                                <li class="hidden"><a><i class="fa fa-globe"></i>Region <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\Region::class))
                                        <li>
                                            <a href="{{url('admin/region/create')}}">Add Region</a>
                                        </li>
                                        @endif
                                            @if (!Auth::guard('admin')->user()->can(['view'], App\Region::class))
                                        <li>
                                            <a href="{{url('admin/region')}}">View Region</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <!-- Delivery Time -->
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\DeliveryTime::class))
                                <li><a><i class="fa fa-clock-o"></i>Delivery Times <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\DeliveryTime::class))
                                        <li>
                                            <a href="{{url('admin/delivery-time/create')}}">Add Delivery Time</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\DeliveryTime::class))
                                        <li>
                                            <a href="{{url('admin/delivery-time')}}">View Delivery Times</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <!-- Slot Time -->
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\SlotTime::class))
                                <li><a><i class="fa fa-clock-o"></i>Slot Times <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\SlotTime::class))
                                        <li>
                                            <a href="{{url('admin/slot-time/create')}}">Add slot</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\SlotTime::class))
                                        <li>
                                            <a href="{{url('admin/slot-time')}}">View slot</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <!-- Slot Group -->
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\SlotGroup::class))
                                <li><a><i class="fa fa-clock-o"></i>Slot group <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\SlotGroup::class))
                                        <li>
                                            <a href="{{url('admin/slot-group/create')}}">Add Slot group</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\SlotGroup::class))
                                        <li>
                                            <a href="{{url('admin/slot-group')}}">View slot group</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <!-- Week Package -->
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\WeekPackage::class))
                                <li><a><i class="fa fa-object-group"></i>Week Package <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\WeekPackage::class))
                                        <li>
                                            <a href="{{url('admin/week-package/create')}}">Add Week Package</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\WeekPackage::class))
                                        <li>
                                            <a href="{{url('admin/week-package')}}">View Week Package</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                             @endif
                            <!-- Load Slot Zone -->
                            @if (!Auth::guard('admin')->user()->can(['loadSlotZone'], App\Zone::class))
                                <li><a><i class="fa fa-map-marker"></i>Load Slot Zone <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li>
                                            <a href="{{url('admin/load-slot-zone')}}">View load-slot-zone</a>
                                        </li>

                                    </ul>
                                </li>
                            @endif
                            <!-- Shopper / Driver Management -->
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\VendorProduct::class))
                                <li><a><i class="fa fa-sliders"></i>Shopper/Driver Management <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['shoppersAssignment'], App\VendorProduct::class))
                                        <li>
                                            <a href="{{url('admin/vendor-product/shopperassignment')}}">Shopper Assignment</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['driverassignment'], App\VendorProduct::class))
                                        <li>
                                            <a href="{{url('admin/vendor-product/driverassignment')}}">Driver Assignment</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['map'], App\VendorProduct::class))
                                        <li>
                                            <a href="{{url('admin/vendor-product/map')}}">Map View</a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <!-- Group 2 -->
                    <li>
                        <a><i class="fa fa-users"></i>Group 2 <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                           <!-- Brand -->
                           @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Brand::class))
                                <li><a><i class="fa fa-product-hunt"></i>Brands <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\Brand::class))
                                        <li>
                                            <a href="{{url('admin/brand/create')}}">Add Brand</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\Brand::class))
                                        <li>
                                            <a href="{{url('admin/brand')}}">View Brand</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                           <!-- Membership -->
                           @if (!Auth::guard('admin')->user()->can(['create','view','delete','update'], App\Membership::class))
                                <li><a><i class="fa fa-user-plus"></i>Memerships <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\User::class))
                                        <li>
                                            <a href="{{url('admin/membership/create')}}">Add New</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\User::class))
                                        <li>
                                            <a href="{{url('admin/membership')}}">View All</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                           <!-- Refer & Earn -->
                           <!-- Coupons -->
                           @if (!Auth::guard('admin')->user()->can(['create','view','delete','update'], App\Coupon::class))
                                <li><a><i class="fa fa-gift"></i>Coupons <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\Coupon::class))
                                        <li>
                                            <a href="{{url('admin/coupon/create')}}">Add New</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\Coupon::class))
                                        <li>
                                            <a href="{{url('admin/coupon')}}">View All</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                           <!-- CMS Pages -->
                            @if (!Auth::guard('admin')->user()->can(['index'], App\Cms::class))
                                <li><a href="{{url('admin/cms')}}"><i class="fa fa-file"></i>Cms Pages </a></li>
                            @endif
                           <!-- Measurement Class -->
                           @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\MeasurementClass::class))
                                <li><a><i class="fa fa-balance-scale"></i>measurement class <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\MeasurementClass::class))
                                        <li>
                                            <a href="{{url('admin/measurement-class/create')}}">Add measurement class</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\MeasurementClass::class))
                                        <li>
                                            <a href="{{url('admin/measurement-class')}}">View measurement class</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                           <!-- Suppliers -->
                           @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Supplier::class))
                                <li><a><i class="fa fa-product-hunt"></i>Supplier <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\Supplier::class))
                                        <li>
                                            <a href="{{url('admin/supplier/create')}}">Add Supplier</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\Supplier::class))
                                        <li>
                                            <a href="{{url('admin/supplier')}}">View Supplier</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                           <!-- Access Level -->
                           @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\AccessLevel::class))
                                <li><a><i class="fa fa-mortar-board"></i>Access Level <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\AccessLevel::class))
                                        <li>
                                            <a href="{{url('admin/access_level/create')}}">Add Access Level</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\AccessLevel::class))
                                        <li>
                                            <a href="{{url('admin/access_level')}}">View Access Level</a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                           <!-- Permission Level -->
                           @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Product::class))
                                <li><a><i class="fa fa-lock"></i>Permission Access<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['index'], App\PermissionAccess::class))
                                        <li>
                                            <a href="{{url('admin/permission_access/create')}}">Add Permission Access</a>
                                        </li>
                                        @endif


                                    </ul>
                                </li>
                            @endif
                           <!-- Settings -->
                           @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Product::class))
                                <li><a><i class="fa fa-gear"></i>Settings <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li>
                                            <a href="{{url('admin/setting/general')}}">General</a>
                                        </li>
                                         <li>
                                            <a href="{{url('admin/setting/site_setting')}}">Site Setting</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/setting/social_media')}}">social media</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/setting/api_setting')}}">api setting</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/setting/app_setting')}}">app setting</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/setting/payment')}}">payment</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/setting/app_version')}}">app version</a>
                                        </li>
                                      <!--   <li>
                                            <a href="{{url('admin/setting/address_setting')}}">Address Setting</a>
                                        </li> -->

                                    </ul>
                                </li>
                            @endif
                           <!-- Financial Management -->
                            @if (!Auth::guard('admin')->user()->can(['index','create','view','revenue'], App\VendorCommission::class))
                                <li>
                                    <a><i class="fa fa-money "></i>Financial Management <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['create'], App\VendorCommission::class))
                                        <li>
                                            <a href="{{url('admin/vendor-commission/create')}}">Add Store Commission</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['view'], App\VendorCommission::class))
                                        <li>
                                            <a href="{{url('admin/vendor-commission')}}">View Store Commissions</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['revenue'], App\VendorCommission::class))
                                        <li>
                                            <a href="{{url('admin/revenue')}}">Revenue</a>
                                        </li>
                                        @endif
                                     </ul>
                                </li>
                            @endif
                     
                           <!-- Analytic & Reports -->
                           @if (!Auth::guard('admin')->user()->can(['index','order','customer','product','slotTimes','zone','vendor','shopper','driver'], App\Revenue::class))
                                <li>
                                    <a><i class="fa fa-file"></i>Analytics & Reports  <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @if (!Auth::guard('admin')->user()->can(['order'], App\Revenue::class))
                                        <li>
                                            <a href="{{url('admin/analytics/order')}}">Orders</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['customer'], App\Revenue::class))
                                        <li>
                                            <a href="{{url('admin/analytics/customer')}}">Customers</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['product'], App\Revenue::class))
                                        <li>
                                            <a href="{{url('admin/analytics/product')}}">Products</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['slotTimes'], App\Revenue::class))
                                        <li>
                                            <a href="{{url('admin/analytics/slot-times')}}">Slot Times</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['zone'], App\Revenue::class))
                                        <li>
                                            <a href="{{url('admin/analytics/zone')}}">Zone</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['vendor'], App\Revenue::class))
                                        <li>
                                            <a href="{{url('admin/analytics/vendor')}}">Stores</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['shopper'], App\Revenue::class))
                                        <li>
                                            <a href="{{url('admin/analytics/shopper')}}">Shoppers</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['driver'], App\Revenue::class))
                                        <li>
                                            <a href="{{url('admin/analytics/driver')}}">Drivers</a>
                                        </li>
                                        @endif
                                        @if (!Auth::guard('admin')->user()->can(['order'], App\Revenue::class))
                                        <li>
                                            <a href="{{url('admin/analytics/payment-history')}}">Paymemt History</a>
                                        </li>
                                        @endif
                                       
                                       

                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                    @if (!Auth::guard('admin')->user()->can(['index','addWallet','viewWallet','addCoin','viewCoin'], App\WalletManagement::class))
                        <li><a><i class="fa fa-product-hunt"></i>Wallet Management <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                @if (!Auth::guard('admin')->user()->can(['index'], App\WalletManagement::class))
                                <li>
                                    <a href="{{url('admin/wallet-management')}}">View</a>
                                </li>
                                @endif
                                @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\CoinSettings::class))
                                    <li><a><i class="fa fa-product-hunt"></i>Darbaar Coin Settings <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            @if (!Auth::guard('admin')->user()->can(['create'], App\CoinSettings::class))
                                            <li>
                                                <a href="{{url('admin/coin-settings/create')}}">Add</a>
                                            </li>
                                            @endif
                                            @if (!Auth::guard('admin')->user()->can(['view'], App\CoinSettings::class))
                                            <li>
                                                <a href="{{url('admin/coin-settings')}}">View</a>
                                            </li>
                                            @endif

                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if (!Auth::guard('admin')->user()->can(['index'], App\FirstOrder::class))
                         <li><a href="{{url('admin/first_order')}}"><i class="fa fa-gg"></i>First Order </a></li>
                        @endif
                        @if (!Auth::guard('admin')->user()->can(['index','create','view','delete','update'], App\Offer::class))
                    <li>
                        <a><i class="fa fa-gift"></i>Offers <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if (!Auth::guard('admin')->user()->can(['create'], App\Offer::class))
                            <li>
                                <a href="{{url('admin/offer/create')}}">Add Offer</a>
                            </li>
                            @endif
                            @if (!Auth::guard('admin')->user()->can(['view'], App\Offer::class))
                            <li>
                                <a href="{{url('admin/offer')}}">View Offers</a>
                            </li>
                            @endif

                        </ul>
                    </li>
                    @endif
                    
                    
                     @if (!Auth::guard('admin')->user()->can(['create','view','index','driverNotification','shopperNotification','orderStatusNotification','unavailableProducts'], App\Notification::class))
                    <li><a><i class="fa fa-bell"></i>Notifications <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                             @if (!Auth::guard('admin')->user()->can(['create'], App\Notification::class))
                            <li>
                                <a href="{{url('admin/admin-notification/create')}}">Send Push Notification</a>
                            </li>
                             @endif
                              @if (!Auth::guard('admin')->user()->can(['view'], App\Notification::class))
                            <li>
                                <a href="{{url('admin/admin-notification')}}">Send Notification</a>
                            </li>
                            @endif
                             @if (!Auth::guard('admin')->user()->can(['driverNotification'], App\Notification::class))
                            <li>
                                <a href="{{url('admin/notification/address')}}">Driver Notification</a>
                            </li>
                             @endif
                            
                            
                           <!--  <li>
                                <a href="{{url('admin/admin-notification/unavailable')}}">Unavailable Products</a>
                            </li> -->
                             @if (!Auth::guard('admin')->user()->can(['unavailableProducts'], App\Notification::class))
                             <li>
                                <a href="{{url('admin/notification/unavailable')}}">Unavailable Products</a>
                            </li>
                             @endif
                              @if (!Auth::guard('admin')->user()->can(['shopperNotification'], App\Notification::class))
                             <li>
                                <a href="{{url('admin/notification/shopper')}}">Shopper Notifications</a>
                            </li>
                             @endif
                             @if (!Auth::guard('admin')->user()->can(['orderStatusNotification'], App\Notification::class))
                             <li>
                                <a href="{{url('admin/notification/order')}}">Order Status Notification</a>
                            </li>
                            @endif
                            
                        </ul>
                    </li>
                    @endif
                </ul>
                <ul class="nav side-menu">
                    
                    

                        

                        

                        <!-- @if (!Auth::guard('admin')->user()->can(['manualOrder'], App\ProductOrder::class))
                        <li><a href="{{url('admin/manual-order')}}"><i class="fa fa-shopping-cart"></i>Manual Order</a></li>
                        @endif -->

                        

                        

                        

                       
                        

                        
                        
                    
                    
                    
                    

                    

                    

                    
                    
                    <!-- @if (!Auth::guard('admin')->user()->can(['site','auth','pagination','slider','user'], App\Revenue::class))
                    <li><a><i class="fa fa-language"></i>Language Management <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if (!Auth::guard('admin')->user()->can(['site'], App\Revenue::class))
                            <li>
                                <a href="{{url('admin/language/site/en')}}">Site</a>
                            </li>
                            @endif
                             @if (!Auth::guard('admin')->user()->can(['auth'], App\Revenue::class))
                             <li>
                                <a href="{{url('admin/language/auth/en')}}">Auth</a>
                            </li> 
                            @endif
                             @if (!Auth::guard('admin')->user()->can(['pagination'], App\Revenue::class))
                             <li>
                                <a href="{{url('admin/language/pagination/en')}}">Pagination</a>
                            </li>
                             @endif 
                              @if (!Auth::guard('admin')->user()->can(['slider'], App\Revenue::class))
                             <li>
                                <a href="{{url('admin/language/slider/en')}}">Slider</a>
                            </li> 
                            @endif 
                            <!--  <li>
                                <a href="{{url('admin/language/validation')}}">Validation</a>
                            </li> 
                             @if (!Auth::guard('admin')->user()->can(['user'], App\Revenue::class))
                             <li>
                                <a href="{{url('admin/language/user/en')}}">User</a>
                            </li> 
                             @endif 
                              @if (!Auth::guard('admin')->user()->can(['order'], App\Revenue::class))
                             <li>
                                <a href="{{url('admin/language/order/en')}}">Order</a>
                            </li> 
                             @endif 
                             <!-- <li>
                                <a href="{{url('admin/language/password')}}">Password</a>
                            </li> 
                             
                        </ul>
                    </li>
                    @endif -->
                    
                    
                    
                    
                    
                    
                </ul>
            </div>


        </div>
        <!-- /sidebar menu -->


    </div>
</div>
