@extends('admin.layouts.app')

@section('title', ' Products |')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <style type="text/css">
        .dataTables_length {width: 20%;}
        .dt-buttons .btn {border-radius: 0px;padding: 4px 12px;}
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
<?php //echo '<pre>'; print_r($products); echo '</pre>'; ?>
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Products</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
<a href ="<?php echo route("admin.product.import"); ?>" class="btn btn-xs btn-success">import</a>
                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                     <th>Categories / Sub Categories</th>
                                    <th>Measurement Details</th>
                                    <th>Measurement Value</th>
                                    <th>Brand</th>
                                    <th>GST</th>
                                    <th>Barcode</th>
                                    <!-- <th>Created At</th> -->
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <!-- <tbody>
                                    <?php if(isset($products) && !empty($products)) {
                                        foreach ($products as $key => $value) {?>
                                    <tr>
                                    <td><?php echo $value['id']; ?></td>
                                    <td><?php echo $value['name']; ?></td>
                                     <td><?php echo $value['category_name']; ?></td>
                                    <td><?php echo $value['measurement_class']; ?></td>
                                    <td><?php echo $value['measurement_value']; ?></td>
                                    <td><?php echo $value['brand_name']; ?></td>
                                    <td><?php echo $value['gst']; ?></td>
                                    <td><?php echo $value['barcode']; ?></td>
                                    <td><?php echo $value['created_at']; ?></td>
                                    <td><?php echo '<a href="'.route("product.show",$value['id']).'" class="btn btn-success">Show</a><a href="'.route("product.edit",$value['id']).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$value['id'].')" class="btn btn-danger">Delete</button>'; ?></td>
                                <?php }} ?>
                                </tr>
                                </tbody> -->
                            </table>
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
<script src="https://www.jqueryscript.net/demo/Creating-A-Live-Editable-Table-with-jQuery-Tabledit/jquery.tabledit.js"></script>
<script>

$(function() {

    window.table=$('#users-table').DataTable({
        autoWidth:false,
        scrollX:        true,
        scrollCollapse: true,
        fixedColumns: true,
        dom: 'lBfrtip',
        order: [[ 5, "desc" ]],
        buttons: [
           
            {
              extend: 'excel',
              text: 'Excel',
              className: 'exportExcel',
              filename: 'Export excel',
              
            },
            {
              extend: 'pdf',
              text: 'PDF',
              className: 'exportExcel',
              filename: 'Export excel',
              
            }, 
            {
              extend: 'print',
              text: 'Print',
              className: 'exportExcel',
              filename: 'Export excel',
              
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
        ajax: '{!! route('product.datatable') !!}',
        fnDrawCallback :function() {
            $('.data-toggle-coustom').bootstrapToggle();
            $('.data-toggle-coustom').change(function() {
                var product_id =$(this).attr('product-id');
                changeStatus(product_id,$(this).val());
            });
            editTable();
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'category_name', name: 'category_id' },
            { data: 'measurement_class', name: 'measurement_class' },
            { data: 'measurement_value', name: 'measurement_value' },
            { data: 'brand', name: 'brand' },
            { data: 'gst', name: 'gst' },
            { data: 'barcode', name: 'barcode' },
            /*{ data: 'created_at', name: 'created_at' },*/
            { data: 'action', name: 'action',orderable: false, searchable: false }
        ]
    });
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
});

function editTable() {
    $('#users-table').Tabledit({
        eventType: 'dblclick',
        editButton: true,
        deleteButton: false,
        saveButton: true,
        hideIdentifier: false,
        url: "{!! route('admin.product.edit-product-data') !!}",
        type: "GET",
        dataType : 'json',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        columns: {
            identifier: [0, 'id'],
            editable: [[1, 'name:en'], [4, 'measurement_value'], [5, 'brand_id','<?php echo $brands;?>'],[6, 'gst'], [7, 'barcode']]
        }
    }).find('select[name="category_id[]"]').attr('multiple', 'multiple');
}

function deleteRow(id){
    $.ajax({
        data: {
            id:id
        },
        type: "DELETE",
        url: "{{ url('admin/product') }}/"+id,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
            window.table.draw();
            new PNotify({
                title: 'Success',
                text: data.message,
                type: 'success',
                styling: 'bootstrap3'
            });

        },
        error: function( data ) {
            new PNotify({
                title: 'Error',
                text: 'something is wrong',
                type: "error",
                styling: 'bootstrap3'
            });
        }
    });
}
function changeStatus(id,status){
    $.ajax({
        data: {
            id:id,
            status:status,
            _method:'PATCH'
        },
        type: "PATCH",
        url: "{!! route('admin.product.status') !!}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
            window.table.draw();
            new PNotify({
                title: 'Success',
                text: data.message,
                type: 'success',
                styling: 'bootstrap3'
            });

        },
        error: function( data ) {
            new PNotify({
                title: 'Error',
                text: 'something is wrong',
                type: "error",
                styling: 'bootstrap3'
            });

        }
    });
}

</script>
@endpush
