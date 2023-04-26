@extends('layouts.app')

@section('content')
<section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li>Login</li>      
    </ul>
    </div>  
</section>
<section class="section-area">
<div class="container"> 
<div class="delivery-time-box">
<h2>{{ __('Login') }}</h2>  
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="display: none;">
                <div class="card-header">Please provide your Mobile Number or Email to Login/sign up on Darbaar Mart</div>
                <br/>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }} / Mobile Number</label>
                             <div class="col-md-6">
                                 <input id="email" type="email" placeholder="Enter Address" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="current-email">   
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                       @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                             
                        </div>
                        <a class="btn btn-link" href="{{ url('/sendpasswordform') }}">
                                {{ "Send Password on mobile" }}
                        </a>

                        
                       
                    </form>
                </div>
            </div>
            <a class="btn btn-link btn btn-info" data-toggle="modal" data-dismiss="modal" data-target="#login_with_mobile">
                {{ " Login With Mobile Number Otp" }}
            </a>
        </div>
    </div>
</div>

</div>  
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('.btn-link').trigger('click');
    })
</script>
@endsection
