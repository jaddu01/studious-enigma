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
                <div class="panel-heading">Pay With Razorpay</div>

                <div class="panel-body text-center">
                    <form action="{!!route('payment')!!}" method="POST" id="payment_form" >
                        <!-- Note that the amount is in paise = 50 INR -->
                        <!--amount need to be in paisa-->
                        <?php $amount = number_format($amount,2,'.',''); ?>
                        <script src="https://checkout.razorpay.com/v1/checkout.js"
                                data-key="{{ env('razor_key') }}"
                                data-amount="{{$amount*100}}"
                                data-buttontext="Pay ₹ {{$amount}}"
                                data-name="Pay for order"
                                data-description="Order Value"
                                data-image="{{asset('storage/app/public/upload/logo.png')}}"
                                data-prefill.name="{{$phone_number}}"
                                data-prefill.contact="{{$phone_number}}"
                                data-prefill.email="{{$email}}"
                                data-theme.color="#ae2220">
                        </script>
                        <input type="hidden" name="_token" value="{!!csrf_token()!!}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#payment_form').submit(); 
    })
    function ajax(){
        $('#payment_form').submit(ajax);
        return false;
    }
    window.onload=function(){
        setInterval(ajax, 2000);
    }
</script>
@endsection