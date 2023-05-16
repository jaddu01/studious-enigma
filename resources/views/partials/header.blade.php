     <?php  if(Auth::user()){
    $zone_id=Session::get('zone_id');
    if(empty($zone_id)){
     Session::put('zone_id',Auth::user()->zone_id);  
     $zone_id=Auth::user()->zone_id;  }
     $cartTotalArray= Helper::cartTotal(Auth::user()->id,$zone_id); 
     $globalSetting= Helper::globalSetting();
     if(Auth::user()->device_type=='A'){
               $app_link =  $globalSetting->app_url_android;
     }else if(Auth::user()->device_type=='I'){
               $app_link =  $globalSetting->app_url_ios;
     }else{
         $app_link = $globalSetting->app_url_android;
     }
     if(!empty(Session::get('coupon_discount'))){
        $coupon_discount = Session::get('coupon_discount');
     }else{
         $coupon_discount =  0;
     }
     }else{

        $globalSetting= Helper::globalSetting();
       $searchData=$searchType="";
       $cartTotalArray=[];
       $zone_id="";
        $app_link = $globalSetting->app_url_android;
    } 
    $darbaar_coin_price = Session::get('darbaar_coin_price'); 

    if(url()->current()==url('/') || url()->current()==url('/home') ){
        $searchData=$searchType="";
    }else{
    $searchData=Session::get('searchData');
    $searchType=Session::get('searchType');
    // dd(Session::all());
      }?>   
    <?php  if(Request::is('login')){ $url ="loginpage"; }else{  $url ="";  } ?>
    <style type="text/css">
        .media-left img{
            max-width: 30px;
        }
    </style>
    <div class="top_header">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 pull-right">
                    <div class="top-left-col">Welcome you to Darbaar Mart! </div>
                    <div class="top_social">
                        <ul> @if(Auth::user())
                            <li><a href="{{url('/membership')}}">Membership</a> </li>
                            <li><a href="{{url('/support')}}">Help & Support</a> </li>
                            <li><a href="{{$app_link}}">Download App</a> </li>
                             @else
                            <li><a  href="{{url('/membership')}}">Membership</a> </li>
                            <li><a href="{{url('/support')}}">Help & Support</a> </li>
                            <li><a href="{{$app_link}}">Download App</a> </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wsmenucontainer clearfix">
        <div class="overlapblackbg"></div>
        <div class="wsmobileheader clearfix"> <a id="wsnavtoggle" class="animated-arrow"><span></span></a> </div>
        <div class="header">
            <div class="container">
                <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('storage/app/public/upload/logo.png')}}" alt="img"></a>
                <div class="top-location">
                   <?php if(Auth::user()){ ?>
                    <div class="select_box">
                        <span>
                            <select name="zone_id" id="zone_id">
                            <?php  $Zone= Helper::Zone_list();  
                           // if(Auth::user()){ $zone_id = Auth::user()->zone_id; }?>
                            @foreach($Zone as $row)
                            <option value="{{$row->id}}" <?php if(Auth::user()){ if($row->id==$zone_id){ echo "selected='selected'"; } }   ?>onchange="getzone(this.id);">{{$row->name}}</option>
                            @endforeach
                            </select>
                        </span>
                    </div>
                    <?php } ?>
                </div>

                <div class="top-search-bar">
                 
                   {!! Form::open(['route' => "search",'method'=>'post','class'=>'form-horizontal form-label-left validation']) !!}
                         <input type="text" value="{{$searchData}}"  name="searchbox" id="search" placeholder="Search for products..." onkeyup="search_result();" autocomplete="off" />
                         <input type="submit" value="Search" />
                     {!! Form::close() !!}
                     <div class="instant-results"></div>
                </div>
                <div class="top-login-area">
                    <div class="top-login-col">
                        <span class="cart-icon"><img src="{{asset('public/images/cart-icon.png')}}" alt="img"></span>
                      @if(Auth::user())
                        <a href="{{url('/mycart')}}"><h4>Shopping Cart <span>
                        <?php $total = $cartTotalArray['offer_price_total'] + $cartTotalArray['delivery_charge'] - $darbaar_coin_price;
                        if($coupon_discount >  $total){ $coupon_discount = $total;   }  ?>
                     ({{$cartTotalArray['count']}})  ₹ {{$cartTotalArray['offer_price_total'] + $cartTotalArray['delivery_charge'] - $coupon_discount - $darbaar_coin_price}}  </span></h4></a>
                      @else
                        <a   @if(empty($url)) data-target="#login_with_mobile" data-toggle="modal" @else href="{{url('/login')}}"  @endif><h4>Shopping Cart<span>(0) ₹ 0</span></h4></a>
                      @endif
                    </div>
                    <div class="top-login-col top-login-register">
                        
                       <!--  <span class="userimg">
                            <img src="{{asset('public/images/userimg.png')}}" alt="img">
                        </span> -->

                         <span class="userimg">
                        <?php if((Auth::user()) && ($user->image!='')){?>
                        <img src="{{$user->image}}" alt="img">
                        <?php }else{ ?>
                       <img src="{{asset('public/images/userimg.png')}}" alt="img">
                        <?php } ?>
                        </span>
                        <h4> @if(Auth::user())
                            <a href="{{ url('/profile') }}">{{strlen(Auth::user()->name)>15?substr(Auth::user()->name,0,12).'...':Auth::user()->name}}</a>
                            @else
                        <a  @if(empty($url)) data-target="#login_with_mobile" data-toggle="modal" @else href="{{url('/login')}}"  @endif>My Account</a>
                            @endif
                            <span>
                                <ul>
                                    @if(Auth::user())
                                    <a href="{{ url('/profile') }}">Profile</a>
                                    <li>/</li>
                                    <a href="{{ url('/logout') }}">Logout</a>
                                    @else
                                    <li><a  @if(empty($url)) data-target="#login_with_mobile" data-toggle="modal" @else href="{{url('/login')}}"  @endif>Login</a> </li>
                                    <li>/</li>
                                    <li><a href="{{ url('/register') }}">Register</a> </li>
                                    @endif
                                </ul>
                            </span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div id="mainmenu">
                <nav class="wsmenu clearfix pull-right">
                    <ul class="mobile-sub wsmenu-list">
                        <?php  $Category = Helper::Category_arr();  

                        $Category_list_f5 = Helper::Category_list_f5(); 
                        $Category_list_a5 = Helper::Category_list_a5();  ?>
                        @if(Auth::user())
                        @foreach($Category_list_f5 as $row)
                        @if(isset($row->sub_category))
                        @if(count($row->sub_category) == 0)
                        <li> <a href="{{ url('/list') }}/{{$row['slug']}}">{{$row->name}}</a></li>
                        @else
                        <li><a href="{{ url('/list') }}/{{$row['slug']}}">{{$row->name}}
                                 <i class="fa fa-angle-down hidden-sm hidden-xs" aria-hidden="true"></i>
                             </a>
                               <ul class="wsmenu-submenu">
                                    @foreach($row['sub_category'] as $subrow)
                                        <li>
                                            <a href="{{ url('/list') }}/{{$subrow['slug']}}">{{$subrow['name']}} </a>
                                        </li>
                                    @endforeach
                                </ul>
                         </li>
                        @endif
                        @else
                        <li>
                        <a href="{{ url('/list') }}/{{$row['slug']}}">{{$row->name}}</a>
                        </li>
                        @endif
                        @endforeach
                        @else
                         @foreach($Category_list_f5 as $row)
                         <li><!-- <a   @if(empty($url)) data-target="#login_popup" data-toggle="modal" @else href="{{url('/login')}}"  @endif> -->
                           <a href="{{ url('/list') }}/{{$row['slug']}}">
                             {{$row->name}}
                        </a></li>
                           @endforeach
                        @endif
                     <li><a href="#">All<i class="fa fa-angle-down hidden-sm hidden-xs" aria-hidden="true"></i></a>
            <ul class="wsmenu-submenu">
              @foreach($Category_list_a5 as $row)
              <li><!-- <a   @if(empty($url)) data-target="#login_popup" data-toggle="modal" @else href="{{url('/login')}}"  @endif> -->
                           <a href="{{ url('/list') }}/{{$row['slug']}}">
                             {{$row->name}}
                        </a></li>
               @endforeach
            </ul>
          </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<script type="text/javascript">
    function search_result() {
        var html = '';
        var text = $('#search').val();
        console.log(text);
        console.log(text.length);
        if(text.length ==0) {
            html = html.replace('undefined','');
            $('.instant-results').html(html);
        }
        if(text.length >=3) {
            $.ajax({
                url:'/search-product',
                data:{text:text},
                dataType:'json',
                type:'get',
                success:function(res) {
                    if(res.products_count>0 || res.categories_count>0) {
                        if(res.categories_count>0) {
                            html+='<h3 class="instant-results-heading">CATEGORIES</h3>';
                            html+='<ul class="list-unstyled result-bucket">';
                            $.each(res.categories, function( index, value ) {
                                //html+='<li class="result-entry" data-suggestion="Target 1" data-position="1" data-type="type" data-analytics-type="merchant">';
                                html+='<li class="result-entry">';
                                html+='<a href="https://darbaarmart.com/list/'+value.slug+'" class="result-link" tabindex="0">';
                                html+='<div class="media">';
                                html+='<div class="media-left">';
                                html+='<img  src="'+value.image+'" class="" width="50px">';
                                html+='</div>';
                                html+='<div class="media-body">';
                                //html+='<h4 class="media-heading">Heading 1</h4>';
                                html+='<p>'+value.name+'</p>';
                                html+='</div>';
                                html+='</div>';
                                html+='</a>';
                                html+='</li>';
                            });
                        }

                        if(res.products_count>0) {
                            html+='<h3 class="instant-results-heading">PRODUCTS</h3>';
                            html+='<ul class="list-unstyled result-bucket">';
                            $.each(res.products, function( index, value ) {
                                //html+='<li class="result-entry" data-suggestion="Target 1" data-position="1" data-type="type" data-analytics-type="merchant">';
                                html+='<li class="result-entry">';
                                html+='<a href="https://darbaarmart.com/product/'+value.vendor_product_id+'" class="result-link" tabindex="0">';
                                html+='<div class="media">';
                                html+='<div class="media-left">';
                                html+='<i style="font-size:14px" class="fa">&#xf002;</i>';
                                html+='</div>';
                                html+='<div class="media-body">';
                                //html+='<h4 class="media-heading">Heading 1</h4>';
                                html+='<p>'+value.name+'</p>';
                                html+='</div>';
                                html+='</div>';
                                html+='</a>';
                                html+='</li>';
                            });
                        }
                        
                        /*html+='<li class="result-entry" data-suggestion="Target 1" data-position="1" data-type="type" data-analytics-type="merchant">';
                        html+='<a href="#" class="result-link">';
                        html+='<div class="media">';
                        html+='<div class="media-left">';
                        html+='<img src="http://mellon.co.tz/wp-content/uploads/2016/05/noimage.gif" class="media-object">';
                        html+='</div>';
                        html+='<div class="media-body">';
                        html+='<h4 class="media-heading">Heading 1</h4>';
                        html+='<p>0 offers available</p>';
                        html+='</div>';
                        html+='</div>';
                        html+='</a>';
                        html+='</li>';*/
                        html+='</ul>';
                    }

                    html = html.replace('undefined','');
                    $('.instant-results').html(html);
                }
            });
        }
    }
</script>
