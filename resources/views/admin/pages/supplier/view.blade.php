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
                            <h2>{{$Supplier->company_name}}</h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="container">
                            <ul class="nav nav-tabs" id="supplier_tabs">
                              <li class="supplier-tab active" value="general_details"><a href="#" class="blue-text">General Details</a></li>
                              <li class="supplier-tab" value="address_details"><a href="#">Address Details</a></li>
                              <li class="supplier-tab" value="supplier_bill_details"><a href="#">Supplier Bill</a></li>
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
                                                    <td><span>{{$Supplier->company_name}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Contact Number
                                                    </td>
                                                    <td><span>{{$Supplier->contact_number}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Email
                                                    </td>
                                                    <td><span>{{$Supplier->email??'N/A'}}</span></td>
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
                                                    <td><span>{{$Supplier->bank_name??'N/A'}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Account Number
                                                    </td>
                                                    <td><span>{{$Supplier->bank_account_number??'N/A'}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        Bank IFSC Code
                                                    </td>
                                                    <td><span>{{$Supplier->bank_ifsc_code??'N/A'}}</span></td>
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
                                                    <td><p>{{$Supplier->address??'N/A'}}</p></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        State
                                                    </td>
                                                    <td><span>{{$Supplier->state??'N/A'}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        City
                                                    </td>
                                                    <td><span>{{$Supplier->city??'N/A'}}</span></td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="row tabs-details display-hide "  id="address_details">
                                    <p>address Details</p>

                                </div>
                                <div class="row tabs-details display-hide"  id="supplier_bill_details">
                                    <p>Bill Details</p>

                                </div>
                                <div class="row tabs-details display-hide"  id="payment_details">
                                    <p>Payment details</p>
                                </div>
                                
                            </div>
                          </div>
                          
                        {{-- @component('components.datatable.tablestructure')
                            @slot('tableID')
                                dttbl
                            @endslot

                            @slot('tableHeading')
                                <th>Sr No.</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Created On</th>
                                <th>Updated On</th>
                                <th>Actions</th>
                            @endslot
                        @endcomponent --}}

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
        const uRL = "{{ route('admin.opening.stock.list') }}";
    </script>
 
    <!-- Datatables -->
    <script src="{{ asset('public/assets/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('public/js/dt/stock-opening-dt.js') }}"></script>
    <script src="{{ asset('public/js/select2.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <!-- Datatables -->
    <script src="{{asset('public/assets/suppliers/supplier-tabs.js')}}"></script>
  
@endpush
