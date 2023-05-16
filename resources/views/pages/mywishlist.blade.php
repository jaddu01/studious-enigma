@extends('layouts.app')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <style type="text/css">
        .notavail{cursor: auto !important; }
        .not_avail{border: 1px solid red;
    font-size: 10px;
    padding: 2px;
    color: red;}
    </style>
@endpush
@section('content')
    <section class="topnave-bar">
        <div class="container">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
                <li>Wishlist {{$zone_id}}</li>
            </ul>
        </div>
    </section>

    <section class="product-listing-body">
    <div class="container">
        <div class="row">
        <div class="col-sm-4 col-md-3">
            <div class="sdbr_wshlst_mn">
             <div class="prfl_sdbr clearfix">
              <div class="prfl_sdbr_slf">
                <?php if(!empty($user->image)){?>
                <img src="{{$user->image}}" alt="profile">
                <?php }else{ ?>
                <img src="{{url('/storage/app/public/upload/404.jpeg')}}" alt="profile">
                <?php } ?>
              </div> 
              <div class="prfl_sdbr_con">
                <span>Welcome</span>
                <h4>{{$user->name}} {{$user->lname}}</h4>
              </div>
           </div>

           <div class="sdbr_othr_con">
             @if(!empty($user->referral_code))
              <div class="sdbr_oc_sngl">
                <h4>Referral Code: &nbsp;&nbsp; <strong>{{$user->referral_code}}</strong></h4>
                <p><a target="_blank" href="https://web.whatsapp.com/send?text=Referral code is {{$user->referral_code}}" data-original-title="whatsapp" rel="tooltip" data-placement="left" data-action="share/whatsapp/share"><i class="fa fa-whatsapp" aria-hidden="true"></i> Share via Whatsapp</a></p>
              </div>
            @endif  
             <div class="sdbr_oc_sngl">
                <h4>Account Setting</h4>
                <ul>
                  <li>
                    <a href="{{url('/profile')}}">Profile Information</a>
                  </li>
                  <li>
                    <a href="{{url('/addnewaddress')}}">Manage Address</a>
                  </li>
                   <li>
                   <a href="{{url('/change-password')}}"> Change Password </a>
                  </li>
                </ul>
              </div>

            <div class="sdbr_oc_sngl">
                <h4>Payments</h4>
                <ul>
                  <li>
                    <a href="{{url('/mywallet')}}">My Wallet <span class="lbl_sdbr_wslst"> ₹ {{ number_format($user->wallet_amount,2,'.',',') }}</span></a>
                  </li>
                  
                   <li>
                    <a href="{{url('/membership')}}">Membership <span class="lbl_sdbr_wslst">
                      {{ (!empty($user->membership) &&  ($user->membership_to >= date('Y-m-d H:i:s')) ) ? "YES" : "NO"}} </span></a>
                  </li>
                  <li>
                    <a href="{{url('/orderhistory')}}">View shoping orders <span class="lbl_sdbr_wslst">{{$total_order}}</span></a>
                  </li>
                </ul>
              </div>

               <div class="sdbr_oc_sngl">
                <h4>Customer Service</h4>
                <ul>
                  <li>
                    <a href="{{url('/about-us')}}">Contact us</a>
                  </li>
                   <li>
                    <a href="{{url('/about-us')}}">About us</a>
                  </li>
                  <li>
                    <a href="{{url('/faq')}}">Faq's</a>
                  </li> 
                  <li>
                    <a href="{{url('/terms-and-condition')}}">Terms & conditions</a>
                  </li>
                  <li>
                    <a href="{{url('/privacy-policy')}}">Privacy policy</a>
                  </li>
                </ul>
              </div>

             <!--  <div class="sdbr_oc_sngl">
                <h4>Language</h4>
                <ul>
                  <li>
                    <a href="#">English</a>
                  </li>
                  <li>
                    <a href="#">Arabic</a>
                  </li>
                </ul>
              </div> -->
           </div>
        </div>
        </div>
                <div class="col-sm-8 col-md-9">
                    <div class="wshlst_rt_mn clearfix">
                        <h3>My Wishlist ({{$wishLish->total()}})</h3>
                        <ul>
                            @foreach($wishlist['wish_list'] as $wish)
                            <?php //echo "<pre>";print_r($wish); die; 
foreach($wish['vendor_product']['product']['translations'] as $translations){
    if($translations['locale']=='en'){
        $wish['vendor_product']['product']['slug'] = $translations['slug'];
        $wish['vendor_product']['product']['name'] = $translations['name'];
    }
}?>
                                <li>
                                <div class="sngl_wshlst">
                                    <div class="sngl_wshlst_img">
                                   <?php if($wish['not_avail']){  ?>
                                        <a href="{{url('/product')}}/{{$wish['vendor_product']['id']}}">
                                   <?php }else{ ?>
                                      <a href="#">
                                   <?php }
                                  // echo "<pre>"; print_r($wish); die; 
                                   if(!empty($wish['vendor_product']['product']['image'])){
                                   ?>
                                            <img src="{{$wish['vendor_product']['product']['image']['name']}}" alt="product">
                                        <?php }else{ ?>
                                            <img src="{{url('/storage/app/public/upload/404.jpeg')}}" alt="product">
                                        <?php } ?>
                                      </a>
                                        </div>
                                    <div class="sngl_wshlst_con">
                                      <?php if($wish['not_avail']){  ?>
                                        <a href="{{url('/product')}}/{{$wish['vendor_product']['id']}}" class="wshlst_a_tg">{{$wish['vendor_product']['product']['name']}}</a>
                                      <?php }else{  ?>
                                        <a href='#' class='wshlst_a_tg notavail  '   >{{$wish['vendor_product']['product']['name']}} <span class="not_avail">Not Availible</span></a> 
                                      <?php  } ?>
                                        <span class="qnty_spn">{{$wish['vendor_product']['product']['measurement_value']}} {{$wish['vendor_product']['product']['measurement_class']['name']}} </span>
{{--                                        <span class="slr_spn">Seller: SuperComNet</span>--}}
                                        @if(!empty($wish['is_offer']))
                                        <p>Discounted price: <span
                                                    class="prc_orgnl">₹ {{$wish['offer_price']}}</span> <!-- <strike>{{$wish['price']}} INR</strike> --><span
                                                    class="dscnt_prc">{{$wish['offer_data']['name']}}</span></p>
                                        @else
                                            <p>Price: <span class="prc_orgnl">₹ {{$wish['vendor_product']['price']}}</span></p>
                                        @endif

                                    </div>
                                    <!-- <div class="dlt_dv">
                                        <a class="removetowishlist"  href="javascript:;" data-data="{{$wish['vendor_product']['id']}}"><img src="{{url('/')}}/public/images/delete.png" align="delete"></a>
                                    </div> -->
                                <span onclick="addtowhishlist({{$wish['vendor_product']['id']}})"  class="heart-icon   wishlist"><i class="fa fa-heart" aria-hidden="true"></i></span>  
        
                                </div>
                            </li>
                            @endforeach
                        </ul>

                    </div>
                     <p>Total- {{$wishLish->total()}} (per page 10 products)</p>
                        @include('pagination.default', ['paginator' => $wishLish])
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts') 
<script>
          $('#preloader').css('display','block');
      $(document).ready(function () {
        $('#preloader').css('display','none'); 
        })

    </script>
    @endpush