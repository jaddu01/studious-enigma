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
    <link href="{{ asset('public/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
@endpush
@section('content')


    <!-- page content -->
    <div class="right_col" role="main">
        {!! Form::open([
            'route' => 'purchase.store',
            'method' => 'post',
            'class' => 'form-horizontal form-label-left validation',
            'enctype' => 'multipart/form-data',
        ]) !!}

        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel ">
                    <span class="section">Add Purchase</span>
                    @foreach (config('translatable.locales') as $locale)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Select Supplier<small class="startTxt">*</small></label>
                                        {!! Form::select('supplier_id', $suppliers, null, [
                                            'placeholder' => 'Supplier',
                                            'class' => 'form-control select2-supplier',
                                            'id' => 'supplier_id',
                                        ]) !!}
                                        @if ($errors->has('supplier_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('supplier_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label>Place of Supply: -</label><br>
                                        <label>GSTIN: -</label><br>
                                        <label>Billing Address</label><br>
                                        <h5 class="mb-0"><small class="text-muted d-block mb-2"
                                                data-address-message="">Billing Address is Not Provided</small></h5>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="bill_date">Supplier Bill Date<small class="startTxt">*</small></label>
                                        {!! Form::text('bill_date', null, [
                                            'placeholder' => 'Billing Date',
                                            'id' => 'bill_date',
                                            'class' => 'form-control',
                                            'autocomplete' => 'off',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('bill_date'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('bill_date') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                    <div class="col-md-4">
                                        <label for="shipping_date">Shipping Date<small class="startTxt">*</small></label>
                                        {!! Form::text('shipping_date', null, [
                                            'placeholder' => 'Shipping Date',
                                            'id' => 'shipping_date',
                                            'class' => 'form-control',
                                            'autocomplete' => 'off',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('shipping_date'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('shipping_date') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                    <div class="col-md-4">
                                        <label for="due_date">Due Date<small class="startTxt">*</small></label>
                                        {!! Form::text('due_date', null, [
                                            'placeholder' => 'Due Date',
                                            'id' => 'due_date',
                                            'class' => 'form-control',
                                            'autocomplete' => 'off',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('due_date'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('due_date') }}</strong>
                                            </span>
                                        @endif

                                    </div>


                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label for="bill_amount">Bill Amount<small class="startTxt">*</small></label>
                                        {!! Form::number('bill_amount', null, [
                                            'step' => '0.01',
                                            'placeholder' => 'Bill Amount',
                                            'class' => 'form-control col-md-7 col-xs-12',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'id' => 'bill_amount',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('bill_amount'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('bill_amount') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Invoice No.<small class="startTxt">*</small></label>
                                        {!! Form::text('invoice_no', null, [
                                            'placeholder' => 'Invoice no',
                                            'id' => 'invoice_no',
                                            'class' => 'form-control',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('invoice_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('invoice_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <label for="reference_bill_no">Reference Bill No..<small
                                                class="startTxt">*</small></label>
                                        {!! Form::text('reference_bill_no', null, [
                                            'placeholder' => 'BILL2',
                                            'class' => 'form-control',
                                            'id' => 'reference_bill_no',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('reference_bill_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('reference_bill_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                {{-- <div class="row mt-3">
                                    <div class="col-md-4">
        
                                        <label for="brand_id">Brand<small class="startTxt">*</small></label>
                                        {!! Form::select('brand_id', $brands, null, [
                                            'placeholder' => 'Brand',
                                            'class' => 'form-control col-md-7 col-xs-12 select2-brand',
                                            'id' => 'brand_id',
                                        ]) !!}
                                        @if ($errors->has('brand_id'))
                                            <span class="text-danger">
                                                <strong class="text-danger">{{ $errors->first('brand_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="product_id">Product<small class="startTxt">*</small></label>
                                        {!! Form::select('product_id', [], null, [
                                            'placeholder' => 'Product',
                                            'class' => 'form-control col-md-7 col-xs-12 select2-product',
                                            'id' => 'product_id',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
        
                                    </div>
                                    <div class="col-md-4">
                                        <label for="quantity">Quantity<small class="startTxt">*</small></label>
                                        {!! Form::number('quantity', null, [
                                            'step' => '1',
                                            'placeholder' => 'Quantity',
                                            'class' => 'form-control col-md-7 col-xs-12',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('quantity'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('quantity') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                  
                                </div> --}}

                                <div class="row mt-3">

                                    {{-- <div class="col-md-4">
                                        <label for="">Select Material Inward No<small class="startTxt">*</small></label>
                                        {!! Form::text('material_inward_no', null, [
                                            'placeholder' => 'Invoice no',
                                            'class' => 'form-control',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('material_inward_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('material_inward_no') }}</strong>
                                            </span>
                                        @endif
                                    </div> --}}
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="payment_term">Payment Term<small class="startTxt">*</small></label>
                                        {!! Form::select(
                                            'payment_term',
                                            ['90 Days' => '90 Days', '60 Days' => '60 Days', '30 Days'=>'30 Days', '15 Days'=>'15 Days', '7 Days'=>'7 Days'],
                                            null,
                                            [
                                                'placeholder' => 'Product',
                                                'class' => 'form-control col-md-7 col-xs-12 select2-product',
                                                'id' => 'payment_term',
                                                'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                                'lang' => $locale,
                                            ],
                                        ) !!}
                                        @if ($errors->has('payment_term'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('payment_term') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <label for="tax_type">Tax Type<small class="startTxt">*</small></label>
                                        {!! Form::select('tax_type', ['Default'=>'Default', 'Tax Inclusive'=>'Tax Inclusive', 'Tax Exclusive'=>'Tax Exclusive', 'Out Of Score'=>'Out Of Score'], null, [
                                            'placeholder' => 'Product',
                                            'class' => 'form-control col-md-7 col-xs-12 select2-product',
                                            'id' => 'tax_type',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('tax_type'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tax_type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-">
                                    {{-- <div class="col-md-4">
                                        <label for="">Tax Type<small class="startTxt">*</small></label>
                                        {!! Form::text('tax_type', null, [
                                            'placeholder' => 'Invoice no',
                                            'class' => 'form-control',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('tax_type'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tax_type') }}</strong>
                                            </span>
                                        @endif
                                    </div> --}}
                                </div>

                            </div>
                        </div>
                    @endforeach
                   
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 mt-3">
                <div class="x_panel ">
                    <span class="section">Product Details</span>
                    <div class="row">
                        <table class="table table-bordered ">
                            <thead class="thead-dark">
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Item Code/Barcode<small class="startTxt">*</small></th>
                                <th scope="col" style="width:200px">Product Name<small class="startTxt">*</small></th>
                                <th scope="col" style="width:80px">Qty<small class="startTxt">*</small></th>
                                <th scope="col" style="width:80px">Free Qty<small class="startTxt">*</small></th>
                                <th scope="col" style="width:80px">Unit Cost<small class="startTxt">*</small></th>
                                <th scope="col" style="width:80px">MRP<small class="startTxt">*</small></th>
                                <th scope="col" style="width:80px">Selling Price<small class="startTxt">*</small></th>
                                <th scope="col">Taxable<small class="startTxt">*</small></th>
                                <th scope="col">Tax<small class="startTxt">*</small></th>
                                <th scope="col" style="width:80px">Landing Cost<small class="startTxt">*</small></th>
                                <th scope="col" style="width:80px">Margin (%)<small class="startTxt">*</small></th>
                                <th scope="col" style="width:80px">Total<small class="startTxt">*</small></th>

                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>1</td>
                                <td><input type="text" class="form-control w-50"></td>
                                <td><input type="text" class="form-control w-50"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td>4</td>
                                <td>4</td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>









                              </tr>
                           
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-right">
                    {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}

                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{ asset('public/js/select2.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
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

            //for visible clendar Date
            $("#bill_date,#due_date,#shipping_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });

            $("#vendor_id").change(function() {

                $.ajax({
                    data: {
                        vendor_id: $(this).val()
                    },
                    method: 'get',
                    url: "{!! route('purchase.get-brands') !!}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        var html = '';
                        html += '<option value="">Select Brand</option>';
                        $.each(response, function(key, valueObj) {
                            html += '<option value="' + key + '">' + valueObj +
                                '</option>';
                        });
                        html = html.replace('undefined', '');
                        $('#brand_id').html(html);
                        $('#product_id').html('<option value="">Select Product</option>');


                    },
                    error: function(response) {
                        var html = '';
                        html += '<option value="">Select Brand</option>';
                        html = html.replace('undefined', '');
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

            $("#brand_id").on('change', function() {
                const brandID = $(this).val();
                getProducts(brandID);

            });

            function getProducts(brandID) {
                $.ajax({
                    data: {
                        brand_id: brandID
                    },
                    method: 'get',
                    url: "{!! route('purchase.get-products') !!}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        var html = '';
                        html += '<option value="">Select Product</option>';
                        $.each(response, function(key, valueObj) {
                            html += '<option value="' + key + '">' + valueObj + '</option>';
                        });
                        html = html.replace('undefined', '');
                        $('#product_id').html(html);


                    },
                    error: function(response) {
                        var html = '';
                        html += '<option value="">Select Product</option>';
                        html = html.replace('undefined', '');
                        $('#product_id').html(html);
                        /* new PNotify({
                             title: 'Error',
                             text: 'something is wrong',
                             type: "error",
                             styling: 'bootstrap3'
                         });*/
                    }
                });
            }
        });
    </script>
    <!-- FastClick -->
    <script src="{{ asset('public/assets/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('public/assets/nprogress/nprogress.js') }}"></script>
    {{-- <!-- validator -->
<script src="{{asset('public/assets/validator/validator.min.js')}}"></ --}}script>
@endpush
