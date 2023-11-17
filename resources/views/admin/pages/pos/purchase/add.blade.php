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
            'id' => 'supplier_form',
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
                                    <div class="col-md-12" id="supplier_address">
                                        <label>Place of Supply: -</label><span id="state"></span><br>
                                        <label>GSTIN: -</label><br><span id="gst_no"></span><br>
                                        <label>Billing Address</label><br>
                                        <div id="Billing-address">

                                        </div>
                                        <h5 class="mb-0" id="billing-not-provided"><small class="text-muted d-block mb-2"
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
                                    <div class="col-md-4">
                                        <label for="reference_bill_no">Reference Inovice No..<small
                                                class="startTxt">*</small></label>
                                        {!! Form::text('reference_bill_no', $reference_invoice_no, [
                                            'class' => 'form-control',
                                            'id' => 'reference_bill_no',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                            'readonly'=>'readonly',
                                        ]) !!}
                                        @if ($errors->has('reference_bill_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('reference_bill_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    {{-- <div class="col-md-4">
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
                                    </div> --}}
                                  
                                </div>

                                <div class="row mt-3">
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
                                        <label for="payment_term">Payment Term<small class="startTxt">*</small></label>
                                        {!! Form::select(
                                            'payment_term',
                                            [''=>'Select Payment Term',
                                                '90 Days' => '90 Days',
                                                '60 Days' => '60 Days',
                                                '30 Days' => '30 Days',
                                                '15 Days' => '15 Days',
                                                '10 Days' => '10 Days',
                                                '7 Days' => '7 Days',
                                            ],
                                            null,
                                            [
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
                                        {!! Form::select(
                                            'tax_type',
                                            [
                                                'Default' => 'Default',
                                                'Tax Inclusive' => 'Tax Inclusive',
                                                'Tax Exclusive' => 'Tax Exclusive',
                                                'Out Of Score' => 'Out Of Score',
                                            ],
                                            null,
                                            [
                                                'class' => 'form-control col-md-7 col-xs-12 select2-product',
                                                'id' => 'tax_type',
                                                'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                                'lang' => $locale,
                                            ],
                                        ) !!}
                                        @if ($errors->has('tax_type'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tax_type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
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
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" id="addNewHeader" style="width:70px"></th>
                                        <th scope="col">#</th>
                                        <th scope="col">Item Code/Barcode<small class="startTxt">*</small></th>
                                        <th scope="col" style="width:200px">Product Name<small class="startTxt">*</small>
                                        </th>
                                        <th scope="col" style="width:80px">Qty<small class="startTxt">*</small></th>
                                        <th scope="col" style="width:80px">Free Qty<small class="startTxt">*</small></th>
                                        <th scope="col" style="width:80px">Unit Cost<small class="startTxt">*</small>
                                        </th>

                                        <th scope="col" style="width:80px">Net Rate (With GST)<small
                                                class="startTxt">*</small>
                                        </th>
                                        <th scope="col" style="width:80px">MRP<small class="startTxt">*</small></th>
                                        <th scope="col" style="width:80px">Selling Price<small class="startTxt">*</small>
                                        </th>
                                        <th scope="col">GST Amount<small class="startTxt">*</small></th>
                                        <th scope="col">GST(%)<small class="startTxt">*</small></th>

                                        <th scope="col" style="width:80px">Margin (%)<small class="startTxt">*</small>
                                        </th>
                                        <th scope="col" style="width:80px">Total<small class="startTxt">*</small></th>

                                    </tr>
                                </thead>
                                <tbody id="product_Details_Tbody">


                                    <tr id="totalResult" class="display-hide">

                                        <td colspan="3" style="border:none !important;">
                                            <div class="text-right" style="padding-right: 30px;"><span
                                                    class="dark-txt">Total</span></div>
                                        </td>
                                        <td colspan="4" style="border:none !important;">
                                            <div class="text-center"><span class="dark-txt" id="totalQty">10</span>
                                            </div>
                                        </td>
                                        <td style="border:none !important;"></td>
                                        <td style="border:none !important;"></td>
                                        <td style="border:none !important;"></td>
                                        <td style="border:none !important;"></td>


                                        <td style="border:none !important;">
                                            <div class="text-center"><span class="dark-txt" id="totalGstAmount"></span>
                                            </div>
                                        </td>
                                        <td style="border:none !important;"></td>
                                        <td style="border:none !important;">
                                            <div class="text-center"><span class="dark-txt" id="total"></span></div>
                                        </td>




                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 mt-3">
                <div class="x_panel ">

                    <span class="section"><i class="fa fa-plus-circle btn-pill mt-3" id="additionalChargeBtn"></i>Add
                        Additional Charges</span>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="collapse" id="addionalCharges">
                                <div class="col-md-12 table-responsive">
                                    <table class="table ">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" id="addNewHeader" style="width:70px"></th>
                                                <th scope="col">#</th>
                                                <th scope="col" class="text-center">Additional Charges<small
                                                        class="startTxt">*</small>
                                                </th>

                                                <th scope="col" style="width:100px" class="text-center">Charge<small
                                                        class="startTxt">*</small>
                                                </th>
                                                {{-- <th scope="col" style="width:100px" class="text-center">Total</th> --}}

                                            </tr>
                                        </thead>
                                        <tbody id="additional_charge_body">

                                            <tr id="additionalChargeResult" class="display-hide bg-dark"
                                                style="background-color:#EBEDF3">
                                                <td colspan="3">
                                                    <div class="text-center dark-txt" style="padding-left: 100px;">
                                                        <span>Total</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center"><span id="totalAddionalChargeTbl"
                                                            class="dark-txt">0</span></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-7">

                        </div>
                        <div class="col-md-5">
                            <div class="row" style="padding-right:8px;">
                                <div class="col-md-6" style="background-color:#EBEDF3; padding:3px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>Total Amount</h5>

                                        </div>

                                        <div class="col-md-12">
                                            <h5 class="text-primary">Total Additional Charge</h5>
                                        </div>
                                        <div class="col-md-12" style="padding-bottom: 2px;">
                                            <b style="font-size: 18px;">Net Amount</b>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6" style="padding: 3px;">
                                    <div class="row">
                                        <div class="col-md-12 text-right" style="border:1px solid #EBEDF3;">
                                            <h5 id="totalAmount">0.00</h5>
                                        </div>

                                        <div class="col-md-12 text-right" style="border:1px solid #EBEDF3;">
                                            <h5 id="totalAdditionalCharges" class="text-primary">0.00</h5>
                                        </div>

                                        <div class="col-md-12 text-right" style="border:1px solid #EBEDF3;">

                                            <b id="NetAmount" style="font-size: 18px;">0.00</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 mt-3">

                <div class="row">
                    <div class="col-12 text-right p-3">
                        {{-- {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}  --}}
                        <button class="btn btn-primary" id ="saveOnlyBtn">Save (Only)</button>
                        <button class="btn btn-success" id ="saveAndPaymentBtn">Save & Payment</button>


                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </div>

    <div class="modal fade" id="paymentModal" data-controls-modal="paymentModal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <b class="modal-title" style="font-size: 18px">Payment</b>
                </div>
                <div class="modal-body">
                    <form id="paymentform">
                        <div class="row">
                            <div class="col-md-12 text-center">

                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode" value="cash" id="cashMode" checked>Cash
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode" value="online" id="onlineMode">Online
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode" value="cheque" id="chequeMode">Cheque
                                </label>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="amount">Amount:</label>
                                <div class="input-group input-group-lg m-input-group m-input-group--air">
                                    {{-- <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fa fa-inr currency_style" aria-hidden="true"></i></span></div> --}}
                                    <input type="number" min="0"
                                        class="form-control m-input m-input--air m--font-boldest m--regular-font-size-lg5"
                                        name="amount" id="amount" placeholder="Amount">
                                </div>
                            </div>

                        </div>
                        <div class="row mt-3 display-hide" id="paymentDateSection">
                            <div class="col-md-5">
                                <label for="payment_date">Payment Date</label>
                                <div class="input-group input-group-lg">
                                    <input type="form-contorl" name="payment_date" id="payment_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="transaction_no">Transaction No./Cheque No.</label>
                                <div class="input-group input-group-lg">
                                    <input
                                        type="form-contorl m-input m-input--air m--font-boldest m--regular-font-size-lg5"
                                        name="transaction_no" id="transaction_no" style="width: 100% !important;"
                                        placeholder="Transaction Number">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="description">Description</label>
                                <textarea class="form-control m-input m-input--air m--font-boldest m--regular-font-size-lg5" name="description"
                                    id="description" style="width: 569px; height: 129px; resize:none;" placeholder="Write here..."></textarea>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="butotn" class="btn btn-success" id="saveBtn">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{ asset('public/js/select2.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>


    <script>
        function ajxHeader() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

        }
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





            //for visible clendar Date
            $("#bill_date,#due_date,#payment_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });

            $("#vendor_id").change(function() {
                ajxHeader();
                $.ajax({
                    data: {
                        vendor_id: $(this).val()
                    },
                    method: 'get',
                    url: "{!! route('purchase.get-brands') !!}",

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
    <script>
        let supplier_address_get_url = "{{ route('purchase.get.supplier.address', '') }}";
        let search_product_url = "{{ route('purchase.get.supply.products') }}";
        let supplier_product_info_url = "{{ route('purchase.get.supplier.products.info', '') }}";
        const saveOnlyUrl = "{{ route('purchase.supplier.purchase.save') }}";
        
        const view_purchase_url ="{{url('admin/pos/purchase/')}}";
    </script>
    <script src="{{ asset('public/assets/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('public/assets/nprogress/nprogress.js') }}"></script>
    <script src="{{ asset('public/assets/purchase/add_purchase.js') }}"></script>
    <script src="{{ asset('public/assets/purchase/field-calculation.js') }}"></script>
    <script src="{{ asset('public/assets/purchase/btn-action.js') }}"></script>
    <script src="{{ asset('public/assets/purchase/save-only-data.js') }}"></script>

    <!-- <script src="{{ asset('public/assets/validator/validator.min.js') }}"><script> -->
@endpush
