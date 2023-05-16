@extends('admin.layouts.app')

@section('title', 'Add Discount')
@push('css')
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
 <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
@endpush
@section('sidebar')
    @parent
@endsection
@section('header')
    @parent
@endsection
@section('footer')
    @parent
@endsection


@section('content')

    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                            {!! Form::model($order,['url' => ['admin/order/add-discount',$order->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','id'=>'add_discount','autocomplete'=>'off']) !!}
                            {{csrf_field()}}
                            <span class="section">Add Discount</span>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load Total Amount<span class="required">:</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="pname">
                                        <span id="total-amount">{{$order->total_amount + $order->delivery_charge -$order->admin_discount - $order->promo_discount}}</span>
                                    </p>

                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load Sub-Total Amount<span class="required">:</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="pname">
                                    <span id="sub_total">{{$order->total_amount}}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load Delivery Charge <span class="required">:</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="pname">
                                    <span>{{$order->delivery_charge}}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load Promo Code <span class="required">:</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="pname">
                                    <span>
                                         {{$order->promo_discount}}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Add Discount<span class="required">:</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!!  Form::text('admin_discount', null, array('placeholder' => 'admin discount','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Update Total Amount <span class="required">:</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="pname">
                                    <span id="update-total-amount">{{$order->total_amount + $order->delivery_charge -$order->admin_discount - $order->promo_discount}}</span>
                                    </p>
                                </div>
                            </div>



                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <a href="{{ url()->previous()}}" type="button" class="btn btn-primary">Cancel</a>
                                    <button id="send" onclick="checkValue()" type="button" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /page content -->

@endsection
@push('scripts')
 <script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
      <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script>
 $(function () {
       $("[name=admin_discount]").on('keyup',function () {
           $("#update-total-amount").text(parseInt($("#total-amount").text())+{{$order->admin_discount}}-$(this).val());
       }) ;
    });

    function checkValue(){
        var adminVal = parseInt($("[name=admin_discount]").val());
        var subTotalVal = parseInt($("#sub_total").text());
            //alert(adminVal);
             //alert(subTotalVal);
             if(adminVal > subTotalVal){
                $("[name=admin_discount]").val(0);
                $("#update-total-amount").val('');
                $("#update-total-amount").text(parseInt($("#total-amount").text()));
                
                //alert('hi');
                    new PNotify({
                        title: 'Error',
                        text: 'Discount can not be greater than sub total',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                 return false;

                }else{
                    $("#add_discount").submit();
                }
    }

      /*allow only digits*/
       
        $('[name=admin_discount]').bind('keyup paste', function(){
            this.value = this.value.replace(/[^0-9]/g, '');
        });
   
      /*$("#send").click(function () {
        alert('hi');
        var adminVal = parseInt($("[name=admin_discount]").val());
        var subTotalVal = (parseInt($("#sub_total").text());
            alert(adminVal);
             alert(subTotalVal);
             if(adminVal > subTotalVal){
                alert('hi');
                    new PNotify({
                        title: 'Error',
                        text: 'Discount can not be greater than sub total',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                 return false;

                }

      });*/
 
</script>
@endpush
