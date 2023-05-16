<div id="login_popup"  class="modal custom_popup fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body clearfix">
        <div class="popuplogo"> <img src="{{url('storage/app/public/upload/logo.png')}}" alt="img"></div> 
        <div class="modal-body-rightcol">  
         <h2  class="modal-title" id="login_popup">{{ __('Login') }}</h2>
         <p>Please provide your Mobile Number or Email to Login/sign up on Darbaar Mart</p>
     <form role="form" method="POST" action="{{ route('login') }}">
      @csrf
        <div class="form-group clearfix">
          <!--     <input id="email" type="email" placeholder="Enter Address" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="current-email">-->     
          <input id="email" type="text" class="form-control" name="email" placeholder="Enter Email/Mobile Number" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                           @endif  
        </div>  
        <div class="form-group clearfix">
            <input id="password" type="password"  placeholder="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            @if ($errors->has('password'))
            <span class="invalid-feedback">
            <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif  
        </div>          
        <div class="form-group clearfix">
        <input class="common-btn" type="submit" value="Login" name="Login" />   
        </div>
         </form>            
        <div class="form-group clearfix">
        <div class="popuptext"> <a <a href="{{url('/password/reset')}}"> Forgot Password ? </a></div>
        <div class="popuptext"> <a data-toggle="modal" data-dismiss="modal"  data-target="#login_with_mobile"> Login With Mobile Number Otp </a></div>
        </div>          
        <div class="popuptextbottom">
        <div class="popuptext"> Don`t Have An Acconut? <a href="{{url('/register')}}">Register Now </a></div>
        </div>  
            </div>
    </div>
</div>
</div>
</div>
<div id="forgot_password" class="modal custom_popup fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body clearfix">
         <div class="popuplogo"> <img src="{{url('public/images/logo.png')}}" alt="img"></div> 
        <div class="modal-body-rightcol">  
         <h2>Enter your email below</h2>
         <p>To retrieve your account</p>
         <form role="form" method="POST" action="{{ route('password.email') }}">
         @csrf  
         <div class="form-group clearfix">
        <input class="form-control" type="text" placeholder="Enter Email Address" />    
        </div>  
         <div class="form-group clearfix">
        <input class="common-btn" type="submin" value="Submit" />   
        </div> </form>  
     </div>
    </div>
  </div>
</div>
</div>
<?php //$countryPhoneCode = Helper::getcountryPhoneCode();  ?>
<?php $countryPhoneCode = ['91'=>'91'];  ?>
<div id="login_with_mobile" class="modal custom_popup fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body clearfix">
         <div class="popuplogo"> <img src="{{url('storage/app/public/upload/logo.png')}}" alt="img"></div> 
        <div class="modal-body-rightcol">  
          <h2  style="margin-top: 20%;" class="modal-title" id="login_popup">{{ __('Login') }}</h2>
         <p>Enter your Mobile Number below to login with Mobile Number and OTP</p>
         <form role="form" method="POST" action="{{ route('login.mobile') }}">
         @csrf  
         <div class="form-group clearfix">
         <div class="form-group col-md-4 ">
          <select name="phone_code" class="form-control" style="    padding: 6px 8px !important;" >
            @foreach($countryPhoneCode as $key=>$value)
            <option <?php if($key=='91'){ echo "Selected"; } ?> value="{{$key}}">{{$value}}</option>
            @endforeach
          </select>
           </div>
          <div class="form-group col-md-8 ">
          <input class="form-control login_with_mobile" type="text" name="phone_number" required="required" placeholder="Enter Mobile Number" max="10" /> 
          </div>
        </div>  
         <div class="form-group clearfix">
        <button class="common-btn" type="submin" value="Submit" />  Submit </button>
        </div> </form>  
     </div>
    </div>
  </div>
</div>
</div>

<div id="login_with_mobile" class="modal custom_popup fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body clearfix">
         <div class="popuplogo"> <img src="{{url('storage/app/public/upload/logo.png')}}" alt="img"></div> 
        <div class="modal-body-rightcol">  
         <h2>Enter your Mobile Number below</h2>
         <p>For login with Mobile Number</p>
         <form role="form" method="POST" action="{{ route('mobile.login') }}">
         @csrf  
         <div class="form-group clearfix">
           <div class="form-group col-md-10 ">
             <input class="form-control" type="hidden" name="phone_number" /> 
          <input class="form-control" type="text" name="otp" placeholder="Enter OTP here" /> 
          </div>
        </div>  
         <div class="form-group clearfix">
        <button class="common-btn" type="submin" value="Submit" />  Submit </button>
        </div> </form>  
     </div>
    </div>
  </div>
</div>
</div>
@section('scripts')
@parent

@if($errors->has('email') || $errors->has('password'))
    <script>
    $(function() {
        $('#login_popup').modal({
            show: true
        });
    });
    </script>
@endif
@endsection