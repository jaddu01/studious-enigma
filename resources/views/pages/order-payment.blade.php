@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            @if($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Error!</strong> {{ $message }}
                </div>
            @endif
            {!! Session::forget('error') !!}
            @if($message = Session::get('success'))
                <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Success!</strong> {{ $message }}
                </div>
            @endif
            {!! Session::forget('success') !!}
            <div class="panel panel-default">
                <div class="panel-heading">Choose Payment Gateway</div>
                <div class="panel-body text-center">                    
                    <h4>Pay ₹ <?php echo $amount = number_format($amount,2,'.',''); ?></h4>
                    <hr>
                    <a href={{'/paywithrazorpay/'.encrypt($order_id)}}>
                        <button type="button" class="common-btn">Pay with Razorpay</button>
                    </a>
                    <hr>
                    <a href={{'/paywitheasebuzz/'.encrypt($order_id)}}>
                        <button type="button" class="common-btn">Pay with Easebuzz</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection