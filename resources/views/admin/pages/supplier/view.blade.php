@extends('admin.layouts.app')

@section('title', 'Customers |')

@push('css')
    <link href="{{ asset('public/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <style type="text/css">
        .dataTables_length {
            width: 20%;
        }

        .dt-buttons .btn {
            border-radius: 0px;
            padding: 4px 12px;
        }
    </style>
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
            <div class="page-title">
            </div>
            <div class="clearfix"></div>
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h2>Supplier Details</h2>

                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{ $Supplier->company_name }}</h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="container">
                            <ul class="nav nav-tabs" id="supplier_tabs">
                                <li class="supplier-tab active" value="general_details"><a href="#"
                                        class="blue-text">General Details</a></li>
                                <li class="supplier-tab" value="address_details"><a href="#">Address Details</a></li>
                                <li class="supplier-tab" value="supplier_bill_details"><a href="#">Supplier Bill</a>
                                </li>
                                <li class="supplier-tab" value="payment_details"><a href="#">Payment </a></li>
                            </ul>
                            <br>
                            <div id="tabs-details">
                                <div class="row tabs-details" id="general_details">
                                    <div class="col-md-4">
                                        <table class="table table-sm table-bordered table-hover table-checkable">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Company Name
                                                    </td>
                                                    <td><span>{{ $Supplier->company_name }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Contact Number
                                                    </td>
                                                    <td><span>{{ $Supplier->contact_number }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Email
                                                    </td>
                                                    <td><span>{{ $Supplier->email ?? 'N/A' }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Due Amount
                                                    </td>
                                                    <td><span>0.00</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Payment Mode
                                                    </td>
                                                    <td><span>N/A</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Payment Term
                                                    </td>
                                                    <td><span>N/A</span></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>

                                    <div class="col-md-4">
                                        <table class="table table-sm table-bordered table-hover table-checkable">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Bank Name
                                                    </td>
                                                    <td><span>{{ $Supplier->bank_name ?? 'N/A' }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Account Number
                                                    </td>
                                                    <td><span>{{ $Supplier->bank_account_number ?? 'N/A' }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Bank IFSC Code
                                                    </td>
                                                    <td><span>{{ $Supplier->bank_ifsc_code ?? 'N/A' }}</span></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>

                                    <div class="col-md-4">
                                        <table class="table table-sm table-bordered table-hover table-checkable">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Address
                                                    </td>
                                                    <td>
                                                        <p>{{ $Supplier->address ?? 'N/A' }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        State
                                                    </td>
                                                    <td><span>{{ $states[$Supplier->state] ?? 'N/A' }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        City
                                                    </td>
                                                    <td><span>{{ $Supplier->city ?? 'N/A' }}</span></td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="row tabs-details display-hide " id="address_details">
                                    <div class="col-md-4">
                                        <table class="table table-sm table-bordered table-hover table-checkable">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold">Company Name</td>
                                                    <td>{{ $Supplier->company_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">PAN NO.</td>
                                                    <td>{{ $Supplier->pan_number ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">GSTIN</td>
                                                    <td>{{ $Supplier->gstin_number ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Remark</td>
                                                    <td>{{ $Supplier->remark ?? 'N/A' }}</td>
                                                </tr>
                                            <tbody>
                                        </table>
                                    </div>



                                </div>
                                <div class="tabs-details display-hide" id="supplier_bill_details">
                                   
                                        @component('components.datatable.tablestructure')
                                            @slot('tableID')
                                                billDttbl
                                            @endslot

                                            @slot('otherStyle')
                                    width:100% !important;
                                            @endslot

                                            @slot('tableHeading')
                                                <th>#</th>
                                                <th>Invoice No</th>
                                                <th>Date </th>
                                                <th>Paid Amount</th>
                                                <th>Due Amount</th>
                                                <th>Total Amount</th>
                                                <th>Actions</th>
                                            @endslot
                                        @endcomponent
                                   
                                </div>
                                <div class="tabs-details display-hide" id="payment_details">
                                   
                                        @component('components.datatable.tablestructure')
                                            @slot('tableID')
                                                paymentTbl
                                            @endslot
                                            @slot('otherStyle')
                                            width:100% !important;
                                                    @endslot
                                            @slot('tableHeading')
                                                <th>#</th>
                                                <th>Payment No</th>
                                                <th>Date </th>
                                                <th>Payment Mode</th>
                                                <th>Amount</th>
                                                <th>Actions</th>
                                            @endslot
                                        @endcomponent
                                  
                                </div>

                            </div>
                        </div>



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
    <script>
        // const uRL = "{{ route('admin.opening.stock.list') }}";
        const supplier_bill_id = "{{$Supplier->id}}";
        const supplier_bill_details_dt_list = "{{route('admin.supplier.bill.dt.list')}}";
        const supplier_payment_dt_list = "{{route('admin.supplier.payment.dt.list')}}";

    </script>

    <!-- Datatables -->
    <script src="{{ asset('public/assets/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/js/select2.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <!-- Datatables -->
    <script src="{{asset('public/js/dt/supplier-payment-dt-list.js')}}"></script>

    <script src="{{ asset('public/assets/suppliers/supplier-tabs.js') }}"></script>
    <script src="{{asset('public/js/dt/supplier-bill-details-dt.js')}}"></script>

@endpush
