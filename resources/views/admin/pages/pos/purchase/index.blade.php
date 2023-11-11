@extends('admin.layouts.app')

@section('title', $title)
@push('css')
    <link href="{{ asset('public/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
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
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>{{ $title }}</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    @component('components.datatable.tablestructure')
                                        @slot('tableID')
                                            dttbl
                                        @endslot
                                        @slot('tableHeading')
                                            <th>Invoice No.</th>
                                            <th>Supplier</th>
                                            <th>Bill Date</th>
                                            <th>Due Date</th>
                                            <th>Paid Amount</th>
                                            <th>Due Amount</th>
                                            <th>Total Additional Charges</th>
                                            <th>Net Amount</th>

                                            <th>Action</th>
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
    <!-- /page content -->
@endsection
@push('scripts')
    <!-- FastClick -->
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
    <script src="{{ asset('public/assets/datatables.net-scroller/js/datatables.scroller.min.js') }}"></script>

    <script>
        const supplier_purchase_list_url = "{{ route('purchase.supplier.list') }}"
    </script>
    <script src="{{asset('public/js/dt/supplier-purchase-dt.js')}}"></script>

@endpush
