@extends('layouts.app')
@section('content')
<section class="topnave-bar">
  <div class="container">
  <ul>
  <li><a href="{{url('/')}}">Home</a> </li>
  <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
  <li><a href="{{url('/profile')}}">My Account</a></li> 
  <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
  <li>Wallet History</li>  
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
                   <!-- <li>
                   <a href="{{url('/change-password')}}"> Change Password </a>
                  </li> -->
                </ul>
              </div>

              <div class="sdbr_oc_sngl">
                <h4>Payments</h4>
                <ul>
                  <li>
                    <a href="javascript:void(0);">My Wallet <span class="lbl_sdbr_wslst"> ₹ {{ number_format($wallet_amount->wallet_amount,2,'.',',') }}</span></a>
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

              <div class="sdbr_oc_sngl">
                <!-- <h4>Language</h4>
                <ul>
                  <li>
                    <a href="#">English</a>
                  </li>
                  <li>
                    <a href="#">Arabic</a>
                  </li>
                </ul> -->
              </div>
           </div>

        </div>
    			
    	</div>
	
  		<div class="col-sm-8 col-md-9">
  			 <div class="wshlst_rt_mn text-center clearfix">
            <h3 class="text-left">My Coins</h3>
      			<div class="availabel-balance-box">
      				<img src="public/images/availabel-balance-box-img.png" alt="img">
      			</div>
            <p><strong>Balance: <img src="{{url('/public/images/daarbar-coin.webp')}}" style="width: 3%" /> {{ number_format($coin_amount->coin_amount,2,'.',',') }}</strong></p>
      			<div class="row">
              <table class="table table-striped table-bordered" >
                <thead  class="success">
                <tr>
                    <th>S. No</th>
                    <th>Transaction ID </th>
                    <th>Transaction Type </th>
                    <th>Type </th>
                    <th>Amount </th>
                    <th>Description </th>
                    <th>Status</th>
                    <th>Date Added</th>
                </tr>
                </thead>
                <tbody>
                  @if(!empty($wallet_histories))
                    @foreach($wallet_histories as $key=>$wallet_history)
                    <tr>
                      <th>{{$key+1}}</th>
                      <th>{{$wallet_history->transaction_id}}</th>
                      <th>{{$wallet_history->transaction_type}}</th>
                      <th>{{$wallet_history->type}}</th>
                      <th><img src="{{url('/public/images/daarbar-coin.webp')}}" style="width: 7%" /> {{$wallet_history->amount}}</th>
                      <th>{{$wallet_history->description}}</th>
                      <th>{{$wallet_history->status ? "success" : "failed" }}</th>
                      <th>{{ date('d/m/Y', strtotime($wallet_history->created_at))}}</th>
                    </tr>
                    @endforeach
                  @else
                    <tr>
                      <th colspan="8">No Record Found!</th>
                    </tr>
                  @endif  
                </tbody>
              </table>   
            </div>
				 
         </div>
  		</div>
		</div>
	</div>	
</section>
@endsection