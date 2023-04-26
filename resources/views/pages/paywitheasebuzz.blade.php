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
                <div class="panel-heading">Pay With Easebuzz</div>

                <div class="panel-body text-center">
                    <form action="{!!route('easebuzz-payment')!!}" method="POST" id="payment_form" >
                        <!-- Note that the amount is in paise = 50 INR -->
                        <!--amount need to be in paisa-->
                        <?php $amount = number_format($amount,2,'.',''); ?>
                        <input type="hidden" name="_token" value="{!!csrf_token()!!}">
                        <button type="submit" class="common-btn"><?php echo "Pay ₹ ".$amount; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        //$('#payment_form').submit(); 
    })
    function ajax(){
        //$('#payment_form').submit(ajax);
        return false;
    }
    window.onload=function(){
        //setInterval(ajax, 2000);
    }
</script>
@endsection