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
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Opening Stock</h2>
                            <div class="clearfix"></div>
                        </div>

                        @component('components.datatable.tablestructure')
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
                        @endcomponent

                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- modal --}}
    <div class="modal fade" id="openingStockModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <b class="modal-title" style="font-size: 18px">Opening Stock</b>
                </div>
                <div class="modal-body">
                    <form id="openingStockForm">
                        <input type="hidden" name="product_id" id="product_id">
                    <div class="row">
                        <div class="col-md-12">
                           <input type="text" disabled name="product"  class="form-control">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="barcode">Barcode No.</label>
                            <input type="number" min="0" id="barcode" name="barcode" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="qty">QTY</label>
                            <input type="number" min="0" name="qty"  id="qty" class="form-control">
                        </div>
                       
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="purchase_price">Purchase Price</label>
                            <input type="number" min="0" id="purchase_price" name="purchase_price" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="best_price">Selling Price</label>
                            <input type="number" min="0" id="best_price" name="best_price" class="form-control">
                        </div>
                       
                       
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="price">Price</label>
                            <input type="number" min="0" id="price" name="price" class="form-control">
                        </div>
                    </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="butotn" class="btn btn-success" id="saveBtn">Save</button> <button type="button"
                        class="btn btn-default" data-dismiss="modal">Cancel</button>
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
        const openingStockUpdateUrl = "{{route('admin.opening.stock.update')}}";
    </script>
 
    <!-- Datatables -->
    <script src="{{ asset('public/assets/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('public/js/dt/stock-opening-dt.js') }}"></script>
    <script src="{{ asset('public/js/select2.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <!-- Datatables -->
    <script>
        $('.prodcut-select2').select2({
            width: '60%',
            placeholder: "Select Product",
            allowClear: true,
        });
    </script>
@endpush
