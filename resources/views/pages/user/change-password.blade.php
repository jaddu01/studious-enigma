@extends('layouts.app')
@section('content')
    <section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li><a href="{{url('/profile')}}">Profile</a></li>  
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
  <li><a href="{{url('/change-password')}}">Change Password</a></li>   
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
                <?php if(($user->image!='')){?>
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
                    <a href="{{url('/mywallet')}}">My Wallet <span class="lbl_sdbr_wslst">₹ {{ number_format($user->wallet_amount,2,'.',',') }}</span></a>
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
           </div>
        </div>
        </div>
        <div class="col-sm-8 col-md-9">
          <div class="wshlst_rt_mn clearfix">
            <h3>My Profile</h3>  
            <div class="my-profile-detail-area" id ="tab_content3">
            <div class="row">
  @foreach (['danger', 'warning', 'success', 'info'] as $key)
  @if(Session::has($key))
  <div class="alert alert-{{ $key }} alert-dismissable">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  {{ Session::get($key) }}
  </div>
  @endif
  @endforeach
            <div class="form-group clearfix">
            <div class="col-sm-12"> 
            <h4>Change Password  </h4>
            </div>
                 {!! Form::model($user,['route' => 'update-password','method'=>'put','class'=>'form-horizontal form-label-left ','id'=>'form-user-password']) !!}
                      {{csrf_field()}}
                      {{method_field('put')}}

                       <div class="form-group col-md-10 {{ $errors->has('old_password') ? ' has-error' : '' }}">
                              {!!  Form::password('old_password',  array('class' => 'form-control custom_input','placeholder'=>'Old Password')) !!}
                              @if( $errors->has('old_password'))
                                {{ Form::filedError('old_password') }}
                              @endif
                            </div>

                            <div class="form-group col-md-10 {{ $errors->has('user_password') ? ' has-error' : '' }}">
                              {!!  Form::password('user_password',  array('class' => 'form-control custom_input','placeholder'=>'Password')) !!}
                              @if( $errors->has('user_password'))
                                {{ Form::filedError('user_password') }}
                              @endif
                            </div>

                          <div class="form-group col-md-10 {{ $errors->has('user_password_confirmation') ? ' has-error' : '' }}">
                            {!!  Form::password('user_password_confirmation',  array('class' => 'form-control custom_input','placeholder'=>'Password Confirm')) !!}
                            @if( $errors->has('user_password_confirmation'))
                              {{ Form::filedError('user_password_confirmation') }}
                            @endif
                          </div>

                        <div class="col-sm-12 text-center">
                          {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!}
                        </div>

                      {!! Form::close() !!}
           
                
        </div>
    </div>
     
  </div>
    </div>  
</section>
<script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
    <!-- /page content -->
@endsection
