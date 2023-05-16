@extends('admin.layouts.auth')

@section('content')
    <div id="login" class=" form">
        <section class="login_content">
            <form role="form" method="POST" action="{{ route('admin.login') }}">
                <h1>Login Form</h1>
                {{ csrf_field() }}
                <div>

                    <input id="email" type="email" class="form-control" name="email"
                           value="{{ old('email') }}" required autofocus placeholder="Email">

                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                            </span>
                    @endif
                </div>
                <div>
                    <input id="password" type="password" class="form-control" name="password" required  placeholder="Password">

                    @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                            </span>
                    @endif
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>

                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
                </div>
                <div class="clearfix"></div>
                <div class="separator">

                    <div>
                        <h1><i class="fa fa-paw" style="font-size: 26px;"></i> {{ config('setting.name') }}</h1>

                        <p>©{{date('Y')}} All Rights Reserved. {{ config('setting.name') }}</p>
                    </div>
                </div>
            </form>


        </section>
    </div>
@endsection
@push('scripts')
@endpush
