@extends('admin.layouts.app')

@section('title', 'Edit Purchase')

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

                            {!! Form::model($purchase,['route' => ['purchase.update',$purchase->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <span class="section">Edit Purchase</span>

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

                                            {!!  Form::select('brand_id', $brands,null, array('placeholder' => 'Brand','class' => 'form-control col-md-7 col-xs-12 select2-brand','id'=>'brand_id' )) !!}
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

                                            {!!  Form::select('product_id', $products,null, array('placeholder' => 'Product','class' => 'form-control col-md-7 col-xs-12 select2-product','id'=>'product_id' )) !!}
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
                                @endforeach

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                       <button type="reset" class="btn btn-primary">Reset</button>
                                        <button id="send" type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection
@push('scripts')
<!-- FastClick -->
<script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('public/assets/nprogress/nprogress.js')}}"></script>
<!-- validator -->
<script src="{{asset('public/assets/validator/validator.min.js')}}"></script>
<script src="{{asset('public/js/select2.min.js')}}"></script>
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



<!-- validator -->
<script>
    // initialize the validator function
    validator.message.date = 'not a real date';

    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

    $('.multi.required').on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
    });

    $('form').submit(function(e) {
        e.preventDefault();
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
            submit = false;
        }

        if (submit)
            this.submit();

        return false;
    });
@endpush