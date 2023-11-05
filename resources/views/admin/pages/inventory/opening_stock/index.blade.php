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
                                <th>MRP</th>
                                <th>Qty</th>
                                <th>Created On</th>
                                <th>Actions</th>
                            @endslot
                        @endcomponent

                    </div>
                </div>

            </div>
        </div>
        {{-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> --}}

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
                    <div class="row">
                        <div class="col-md-12">
                            <select class="prodcut-select2 form-control">
                                <option value="1">Abc</option>
                                <option value="2">xyz</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="barcode">Barcode No.</label>
                            <input type="text" name="barcode" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="purchase_price">Purchase Price</label>
                            <input type="text" name="purchase_price" class="form-control">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="mrp">MRP</label>
                            <input type="text" name="mrp" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="qty">QTY</label>
                            <input type="text" name="qty" class="form-control">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="butotn" class="btn btn-success">Save</button> <button type="button"
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
    </script>
    <script src="{{ asset('public/assets/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('public/assets/nprogress/nprogress.js') }}"></script>
    <!-- Datatables -->
    <script src="{{ asset('public/assets/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('public/assets/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('public/assets/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('public/assets/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('public/assets/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('public/assets/pnotify/dist/pnotify.js') }}"></script>
    <script src="{{ asset('public/assets/pnotify/dist/pnotify.buttons.js') }}"></script>
    <script src="{{ asset('public/assets/pnotify/dist/pnotify.nonblock.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap-toggle.min.js') }}"></script>
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
