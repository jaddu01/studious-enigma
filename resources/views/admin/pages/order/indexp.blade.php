@extends('admin.layouts.app')
@section('title', 'Orders |')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/model.css')}}" rel="stylesheet">
    <style type="text/css">
        .dataTables_length {width: 20%;}
        .dt-buttons .btn {border-radius: 0px; padding: 4px 12px;}
        .model_call{width:20%;}
        .width-50{width:60%;}
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
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Orders</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="panel-body"> </div>
                            <table class="table table-striped table-bordered table-responsive" id="users-table">
                                <thead>
                                <tr>
                                    <th>Order code</th>
                                    <th>Product</th>
                                    <th>Sku</th>
                                    <th>qty</th>
                                    <th>Unit Price</th>
                                   <!--  <th>Currency</th> -->
                                    <th>Price</th>
                                    <th>Service Type</th>
                                    <th>Delivery Date</th>                               
                                    <th>Zone</th>
                                    <th>Customer</th>
                                    <th>Notes</th>
                                    <th>Order Time</th>
                                    <th>Line Level Order Status</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                                </thead>
                            </table>
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
<script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>
<!-- Datatables -->
<script src="{{asset('public/assets/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
{{--<script src="{{asset('public/assets/datatables.net-scroller/js/datatables.scroller.min.js')}}"></script>--}}
<script src="{{asset('public/assets/jszip/dist/jszip.min.js')}}"></script>
<script src="{{asset('public/assets/pdfmake/build/pdfmake.min.js')}}"></script>
<script src="{{asset('public/assets/pdfmake/build/vfs_fonts.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
<script src="{{asset('public/js/bootstrap-toggle.min.js')}}"></script>
<script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('public/js/select2.min.js')}}"></script>

<script>
$(function() {
    window.table=$('#users-table').DataTable({
        dom: 'lBfrtip',
        buttons: [
            
            {
              extend: 'excel',
              text: 'Excel',
              className: 'exportExcel',
              filename: 'Orders list product level',
               exportOptions: {
                     columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 , 11 ,12 ] //Your Colume value those you want
                             }
            },
            {
              extend: 'pdf',
              text: 'PDF',
              className: 'exportExcel',
            filename: 'Orders list product level',
               exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 , 11 ,12 ] //Your Colume value those you want             
                    }
            }, 
            {
              extend: 'print',
              text: 'Print',
              className: 'exportExcel',
               filename: 'Orders list product level',
               exportOptions: {
                  columns: [ 0, 1, 2, 3, 4 , 5, 6, 7, 8, 9, 10 , 11 ,12  ] //Your Colume value those you want              
                   }
            },  
        ],
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        responsive: false,
        processing: true,
        oLanguage: {
        sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
        },
        serverSide: true,

        ajax: {
            url: '{!! route('order.datatablep') !!}',
        },
        fnDrawCallback :function() {
            $('.data-toggle-coustom').bootstrapToggle();
            $('.data-toggle-coustom').change(function() {
                var orders_id =$(this).attr('order-id');
                changeStatus(orders_id,$(this).val());
            })
        },
      columns: [
            { data: 'order_code', name: 'order_code' },
            { data: 'product', name: 'product'   },
            { data: 'sku', name: 'sku'   },
            { data: 'qty', name: 'qty' , searchable: false  },
            { data: 'unit_price', name: 'unit_price' , searchable: false  },
            { data: 'price', name: 'price', searchable: false   },
            { data: 'order_type', name: 'order_type' },
            { data: 'delivery_date', name: 'delivery_date', searchable: false   },
            { data: 'zone_name', name: 'zone_name',orderable: false },
            { data: 'user_name', name: 'user_name'  },
            { data: 'notes', name: 'notes', searchable: false   },
            { data: 'created_at', name: 'created_at', searchable: false   },
            { data: 'order_status', name: 'order_status' },
        ]
    });
    $('#search-form').on('submit', function(e) {
        window.table.draw();
        e.preventDefault();
    });
    $('.datepicker' ).datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
    });
});
</script>
@endpush

