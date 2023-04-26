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
<h2>Send password on mobile</h2>  
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                
                <div class="card-body">
                    <form method="POST" action="{{route('sendpassword')  }}"  >
                        @csrf
                        <div class="form-group row">
                            <label for="mobile" class="col-sm-4 col-form-label text-md-right"> Mobile Number</label>
                             <div class="col-md-6">
                                 <!--     <input id="email" type="email" placeholder="Enter Address" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="current-email">-->     
                                <input id="mobile" type="text" class="form-control" name="mobile" required autofocus>
                                    @if ($errors->has('mobile'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                          <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ "Send Password" }}
                                </button>
                               
                            </div>
                             
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>  
</section>
@endsection
