@extends('admin.layouts.app')

@section('title', ' Barcode Generator')

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
    <link href="{{ asset('public/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
@endpush
@section('content')

    <!-- page content -->
    <div class="right_col" role="main">
        <form  action="{{route('barcode.print')}}" method="post">
            @csrf
        <div class="">

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">


                                    <span class="section">{{ $title }}</span>


                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Product Search</label>
                                            <select class="form-control select2-product"></select>
                                        </div>

                                        <div class="col-md-3">

                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Print Size</label>
                                                    <select class="form-control custom-form-input" name="printsize">
                                                        <option value="1">1 UPS</option>
                                                        <option value="2">2 UPS</option>
                                                        <option value="3">3 UPS</option>
                                                        <option value="A4">A4</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <label>Barcode Size</label>
                                                    <select class="form-control custom-form-input"
                                                        id="barcode-size-dropdown" name="barcodeSize">
                                                        <option value="50">50 &times;25</option>
                                                        <option value="38">38 &times;25</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-3 barcode-display">
                                            @inject('DNSID', App\Helpers\Milon\Barcode\DNS1D)
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <b>Darbaar</b><br>
                                                    <b>MRP: 2500</b>
                                                    <img src="data:image/png;base64,{{ $DNSID->getBarcodePNG('DAR-0001', 'C128', 2, 50, [0, 0, 0, 0], true) }}"
                                                        alt="barcode" id="barcode-sample-img" /><br>

                                                </div>
                                            </div>


                                        </div>



                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



                </div>
            </div>
            <div>
              
                <div class="row mt-3">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-12 table-responsive" id="productTbl">
                                            <table class="table table-bordered">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th  class="text-center">Product Name</th>
                                                        <th  class="text-center">Barcode</th>
                                                        <th class="text-center">Qty</th>
                                                        <th>Mrp</th>
                                                        <th class="text-center">Selling Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-tbl-body">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>



                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-success" id="barcode-generator-btn" disabled>Genrate Barcode</button>
                    </div>
                </div>
               
            </div>
        </div>
    </form>
    </div>
    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{ asset('public/js/select2.min.js') }}"></script>

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

    <script>
        function ajxHeader() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

        }
        const search_product_url = "{{ route('purchase.get.supply.products') }}";
        const barcodesize_url = "{{ route('barcode.img') }}";
        const barcode_print_url = "{{ route('barcode.print') }}";
        let supplier_product_info_url = "{{ route('purchase.get.supplier.products.info', '') }}";

        // const supplier_purchase_list_url = "{{ route('purchase.supplier.list') }}"
    </script>
    <script src="{{ asset('public/assets/barcode-generator/barcode.js') }}"></script>
    <script src="{{ asset('public/assets/barcode-generator/barcode-product-tbl.js') }}"></script>
    {{-- <script src="{{asset('public/js/dt/supplier-purchase-dt.js')}}"></script> --}}
@endpush
