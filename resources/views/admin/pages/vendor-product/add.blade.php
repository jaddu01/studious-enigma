@extends('admin.layouts.app')

@section('title', 'Add product |')

@section('sidebar')
    @parent
@endsection
@section('header')
    @parent
@endsection
@section('footer')
    @parent
@endsection

@push('css')
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
@endpush

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                            {!! Form::open(['route' => 'vendor-product.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','id'=>'product_form']) !!}

                            {{csrf_field()}}
                            <span class="section">Add Product</span>

                            <div class="item form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Store
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('user_id', $users,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple_vendor','placeholder'=>'Store','empty' => false,'value'=>0,'id'=>'user_id')) !!}
                                    {{ Form::filedError('user_id') }}
                                </div>
                            </div>

                            <div class="item form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> products
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('product_id', $products,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple_product','placeholder'=>'Product','empty' => false,'value'=>0,'id'=>'product_id')) !!}
                                    {{ Form::filedError('product_id') }}
                                </div>
                            </div>


                            <div class="item form-group {{ $errors->has('offer_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Offer
                                    <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                     <input type="hidden" name="offer_type" id="offer_type" value="">
                                     <input type="hidden" name="offer_value" id="offer_value" value="">
                                    {!!  Form::select('offer_id', $offres,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple','placeholder'=>'Offer','empty' => false,'value'=>0,'id'=>'offer_id')) !!}
                                    {{ Form::filedError('offer_id') }}
                                </div>
                            </div>

                             <div class="item form-group {{ $errors->has('best_price') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">MRP<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('best_price',null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'priceAmt')) !!}
                                    {{ Form::filedError('best_price') }}
                                </div>
                            </div>


                            <div class="item form-group {{ $errors->has('price') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Best Price <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('price',null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'priceAmt')) !!}
                                    {{ Form::filedError('price') }}
                                </div>
                            </div>

                            <div class="item form-group{{ $errors->has('qty') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">qty
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('qty', null, array('placeholder' => 'qty','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('qty'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('qty') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('per_order') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Max. per order
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('per_order', null, array('placeholder' => 'max. per order','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('per_order'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('per_order') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>


                            <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('status') }}
                                </div>
                            </div>


                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    {{-- <button type="submit" class="btn btn-primary">Cancel</button>--}}
                                    {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.select2-multiple').select2({
                placeholder: "No Offer",
                allowClear: true
            });
            $('.select2-multiple_vendor').select2({
                placeholder: "Select Store",
                allowClear: true
            });
            $('.select2-multiple_product').select2({
                placeholder: "Select product",
                allowClear: true
            });
        });
        $("#offer_id").change(function(){
          
            if($(this).val() == ''){
                $('#offer_type').val('');
                $('#offer_value').val('');
            }
            $.ajax({
                data: {
                    id:$(this).val()
                },
                method:'post',
                url: "{!! route('vendor-product.get-offer') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( response ) {
                console.log(response);
                    if(response.status == 'true'){
                        $('#offer_type').val(response.data.offer_type);
                        $('#offer_value').val(response.data.offer_value);
                    }
 
                },
                error: function( response ) {
                   /* new PNotify({
                        title: 'Error',
                        text: 'something is wrong',
                        type: "error",
                        styling: 'bootstrap3'
                    });*/
                }
            });
        });
         $("#priceAmt").change(function(){
            var offerType = $("#offer_type").val();
            var offerAmount =  $("#offer_value").val();
            if(offerType == 'amount'){
                if(parseFloat(offerAmount) >  parseFloat($(this).val())) {
                    alert('Price should be greater than offer amount');
                    $(this).val('');
                }
            }
        });
        $("#product_form").submit(function(){
            var offerType = $("#offer_type").val();
            var offerAmount =  $("#offer_value").val();
            if(parseFloat(offerAmount) > parseFloat($("#priceAmt").val())) {
                alert('Price should be greater than offer amount');
                $(this).val('');
                return false;
            }
        });

    </script>
@endpush
