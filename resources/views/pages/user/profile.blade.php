@extends('layouts.app')

@section('content')
    <section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="{{url('/')}}">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
     <li><a href="{{url('/profile')}}">Profile</a></li>   
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
                <?php
                 if(($user->image!='')){?>
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
                <p><a  style="    padding: 18px;" target="_blank" href="https://web.whatsapp.com/send?text=Referral code is {{$user->referral_code}}" data-original-title="whatsapp" rel="tooltip" data-placement="left" data-action="share/whatsapp/share"><i class="fa fa-whatsapp" aria-hidden="true"></i> Share via Whatsapp</a></p>
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
                  </li><!-- 
                   <li>
                   <a href="{{url('/change-password')}}"> Change Password </a>
                  </li> -->
                </ul>
              </div>

              <div class="sdbr_oc_sngl">
                <h4>Payments</h4>
                <ul>
                  <li>
                    <a href="{{url('/mywallet')}}">My Wallet <span class="lbl_sdbr_wslst">₹ {{ number_format($user->wallet_amount,2,'.',',') }}</span></a>
                  </li>
                  <li>
                    <a href="{{url('/mycoins')}}">My Coins <span class="lbl_sdbr_wslst" style="text-align: right"><img src="{{url('/public/images/daarbar-coin.webp')}}" style="width: 8%" /> {{ number_format($user->coin_amount,2,'.',',') }}</span></a>
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
                    <a href="{{url('/support')}}">Contact us</a>
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
            <h3>My Profile</h3>  
            <div class="my-profile-detail-area" id ="tab_content3">
            <div class="row">
            <div class="form-group clearfix">
            <div class="col-sm-12"> 
            <h4>Personal Information  <!-- <span> <a href=""> Edit </a> </span> --> </h4>
            </div>
            {!! Form::model($user,['route' => 'updateprofile','method'=>'post','class'=>'form-horizontal form-label-left ','enctype'=>'multipart/form-data','id'=>'form-user-password']) !!}
            {{csrf_field()}} 
            <div class="col-sm-6"> 
            <div class="form-group clearfix">
            <div class="col-sm-12">
            <div class="col-sm-12 col-md-12">
            <input type="text" class="form-control" placeholder="First Name" name="name" value="{{$user->name}}">
            </div>
           <!--  <div class="col-sm-6 col-md-6">
            <input type="text" class="form-control" placeholder="Last Name" name="lname" value="{{$user->lname}}">
            </div> -->
            </div>
            </div>
             <div class="col-sm-12">
              <div class="form-group clearfix">
             <h5 class="col-sm-12">Your Phone number</h5>  
             <div class="col-sm-12">
            <input type="text" class="form-control" readonly="readonly"  placeholder="mobile number" value="{{$user->phone_code}}-{{$user->phone_number}}">
            </div>
            </div>
           </div>
            <div class="col-sm-12">
            <div class="form-group clearfix">
            <h5 class="col-sm-12">Your Gender</h5>    
            <span class="custome-radio col-sm-3 ">
            <input <?php if($user->gender=='male'){ echo 'checked="checked"'; } ?> value ="Male"  type="radio" name="gender" id="test1">
            <label for="test1">Male</label>
            </span> 
            <span class="custome-radio col-sm-3">
            <input <?php if($user->gender=='female'){ echo 'checked="checked"'; } ?> value ="Female"  type="radio" name="gender" id="test2">
            <label for="test2">Female</label>
            </span> 
            </div>
             </div> 
            <div class="col-sm-12"> 
              <div class="form-group clearfix">
            <h4 class="col-sm-12">Date of Birth <!--  <span> <a href=""> Edit </a> </span>  --></h4>
            <?php  $time_input = strtotime($user->dob);
            $dob = date('Y-m-d',$time_input); ?>
            <div class="col-sm-12">
              <?php 
                $cdate = date("Y-m-d");
                $afdate = date("Y-m-d", strtotime("-6 months"));
              ?>
            <input type="date" name="dob" value="{{$dob}}" class="form-control" max="<?= $afdate; ?>">
            </div>
            </div>
          </div>
           <div class="col-sm-12"> 
             <div class="form-group clearfix">
            <h4 class="col-sm-12">Email Address <!--  <span> <a href=""> Edit </a> </span> <span> <a href="{{url('/change-password')}}"> Change Password </a> </span> --></h4>
           <div class="col-sm-12">
            <input type="text" name="email"  class="form-control" value="{{$user->email}}" placeholder="umesh.kumar@brsoftech.org">
            </div>
          </div></div>
            </div>
            <div class="col-sm-6"> 
             <div class="form-group clearfix">
              <div class="col-sm-12">
                <div class="col-sm-12 col-md-12 profile_left">
                <div class="profile_img" >
                <input type="file" name="image" id="profile_img"/>
                  <!-- end of image cropping -->
                  <div id="crop-avatar">
                     @if( $errors->has('image'))
                              {{ Form::filedError('image') }}
                            @endif
                    <!-- Current avatar -->
                    @if(($user->image!="https://zadcartbucket.s3-accelerate.amazonaws.com/"))
                     <img id="image_upload_preview" class="profile_img mg-responsive avatar-view" src="{{$user->image}}" style="max-height: 225px;max-width: 225px;" />
                    @else
                    <img id="image_upload_preview" class="profile_img mg-responsive avatar-view" src="{{ url('public/images/picture.jpg') }}" alt="Change the avatar" style="max-height: 225px;max-width: 225px;" /> 
                    @endif
                   <!--  <img class="img-responsive avatar-view" src="{{ asset('public/images/picture.jpg') }}" alt="Avatar" title="Change the avatar"> -->
                    <!-- /.modal -->
                    <!-- Loading state -->
                    <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                  </div>
                  <!-- end of image cropping -->
                </div>
              </div>
           </div>
            </div>       
            </div>  

            <div class="form-group clearfix">
           
            </div> 
      <div class="form-group clearfix">
      <div class="col-sm-12"> 
        <div class="col-sm-6"> </div>
        <div class="col-sm-6"> 
      <span class="save-btn-area">
      <input class="common-btn" type="submit" value="Save"/>  
      </span>
     <!--  <span class="save-btn-area">
      <input class="gray-btn" type="submit" value="Cancel"/>  
      </span> -->
      </div>  
      </div> </div>  
            <!-- <div class="form-group clearfix">
            <div class="col-sm-12"> 
            <h4>Mobile Number  <span> <a href=""> Edit </a> </span> </h4>
            </div>
            <div class="col-sm-12 col-md-4">
            <input type="text" class="form-control" placeholder="0123456789">
            </div>
            </div> -->

        </div>
    </div>
     
  </div>
    </div>  
</section>
<script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image_upload_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0])
        }
    }

    $("#profile_img").change(function () {
        readURL(this);
    });
</script>
@endpush