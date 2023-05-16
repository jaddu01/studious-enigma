@extends('admin.layouts.app')

@section('title', 'Add Purchase')

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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
@endpush
@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                                {!! Form::open(['route' => 'purchase.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                                {{csrf_field()}}
                                <span class="section">Add Purchase</span>

                                @foreach(config('translatable.locales') as $locale)
                                    <div class="item form-group {{ $errors->has('supplier_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="brand">Supplier
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('supplier_id', $suppliers,null, array('placeholder' => 'Supplier','class' => 'form-control col-md-7 col-xs-12 select2-supplier','id'=>'supplier_id' )) !!}
                                        @if ($errors->has('supplier_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('supplier_id') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('vendor_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="brand">Store <span
                                                    class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('vendor_id', $vendors,null, array('placeholder' => 'Store','class' => 'form-control col-md-7 col-xs-12 select2-vendor','id'=>'vendor_id' )) !!}
                                        @if ($errors->has('vendor_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('vendor_id') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('brand_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="brand">Brand <span
                                                    class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('brand_id', [],null, array('placeholder' => 'Brand','class' => 'form-control col-md-7 col-xs-12 select2-brand','id'=>'brand_id' )) !!}
                                        @if ($errors->has('brand_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('brand_id') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="brand">Product <span
                                                    class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('product_id', [],null, array('placeholder' => 'Product','class' => 'form-control col-md-7 col-xs-12 select2-product','id'=>'product_id' )) !!}
                                        @if ($errors->has('product_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('product_id') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">Price<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::number('price', null, array('step'=>'0.01','placeholder' => 'Price','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('price'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('price') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="quantity">Quantity<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::number('quantity', null, array('step'=>'1','placeholder' => 'Quantity','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('quantity'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('quantity') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('invoice_no') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="invoice_no">Invoice No.<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('invoice_no', null, array('placeholder' => 'Invoice no','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('invoice_no'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('invoice_no') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">Billing Date<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('date', null, array('placeholder' => 'Billing Date','id'=>'date','class' => 'form-control col-md-7 col-xs-12' , 'autocomplete'=>'off','dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('date'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('date') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('payment_mode') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_mode">Payment Mode<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            @foreach($payment_mode as $key=>$value)
                                                <div class="col-sm-4">
                                                    <input type="radio" name="payment_mode" id="payment_mode_{{$key}}" value="{{$key}}">{{$value}}
                                                </div>
                                            @endforeach
                                            
                                            @if ($errors->has('total_changes'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('total_changes') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('payment_status') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_status">Payment Status<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            @foreach($payment_status as $key=>$value)
                                                <div class="col-sm-4">
                                                    <input type="radio" name="payment_status" id="payment_status_{{$key}}" value="{{$key}}">{{$value}}
                                                </div>
                                            @endforeach
                                            
                                            @if ($errors->has('total_changes'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('total_changes') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
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
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
   {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2-vendor').select2({
                placeholder: "Select Store",
                allowClear: true
            });
            $('.select2-supplier').select2({
                placeholder: "Select Supplier",
                allowClear: true
            });
            $('.select2-brand').select2({
                placeholder: "Select Brand",
                allowClear: true
            });
            $('.select2-product').select2({
                placeholder: "Select Product",
                allowClear: true
            });

            $( "#date" ).datepicker(
                { dateFormat: 'yy-mm-dd' }
            );

            $("#vendor_id").change(function(){
          
            $.ajax({
                data: {
                    vendor_id:$(this).val()
                },
                method:'get',
                url: "{!! route('purchase.get-brands') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( response ) {
                console.log(response);
                    var html = '';
                    html+='<option value="">Select Brand</option>';
                    $.each(response, function(key,valueObj){
                        html+='<option value="'+key+'">'+valueObj+'</option>';
                    });
                    html = html.replace('undefined','');
                    $('#brand_id').html(html);
                    $('#product_id').html('<option value="">Select Product</option>');

 
                },
                error: function( response ) {
                    var html = '';
                    html+='<option value="">Select Brand</option>';
                    html = html.replace('undefined','');
                    $('#brand_id').html(html);
                   /* new PNotify({
                        title: 'Error',
                        text: 'something is wrong',
                        type: "error",
                        styling: 'bootstrap3'
                    });*/
                }
            })
        });
            
            $("#brand_id").change(function(){
            $.ajax({
                data: {
                    brand_id:$(this).val()
                },
                method:'get',
                url: "{!! route('purchase.get-products') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( response ) {
                console.log(response);
                    var html = '';
                    html+='<option value="">Select Product</option>';
                    $.each(response, function(key,valueObj){
                        html+='<option value="'+key+'">'+valueObj+'</option>';
                    });
                    html = html.replace('undefined','');
                    $('#product_id').html(html);

 
                },
                error: function( response ) {
                    var html = '';
                    html+='<option value="">Select Product</option>';
                    html = html.replace('undefined','');
                    $('#product_id').html(html);
                   /* new PNotify({
                        title: 'Error',
                        text: 'something is wrong',
                        type: "error",
                        styling: 'bootstrap3'
                    });*/
                }
            });
        });
        });
    </script>
<!-- FastClick -->
<script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('public/assets/nprogress/nprogress.js')}}"></script>
{{--<!-- validator -->
<script src="{{asset('public/assets/validator/validator.min.js')}}"></--}}script>



@endpush
