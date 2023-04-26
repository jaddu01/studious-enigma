@extends('admin.layouts.app')

@section('title', ' Store Products |')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
     <style type="text/css">
        .dataTables_length {width: 20%;}
        .dt-buttons .btn {border-radius: 0px;padding: 4px 12px;}
        .select-box{width:13%!important;border-radius: 4px;}
        .form-control{border-radius: 4px;}
        .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple {
            min-height: 34px;
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

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Store Products</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="panel-body">
                                <form role="form" class="form-inline" id="search-form" method="POST">

                                    <div class="form-group">
                                        {!!  Form::select('user_id', $users,$user_id, array('class' => 'form-control select2-multiple','placeholder'=>'Select Store')) !!}
                                        {!!  Form::select('category_id',$categories,null, array('class' => 'form-control select-box','placeholder'=>'category')) !!}
                                        {!!  Form::select('is_offer', Helper::$is_status,null, array('class' => 'form-control ','placeholder'=>'is offer')) !!}
                                        {!!  Form::label('UnavalableProducts',"Unavailable Products") !!}
                                        {!!  Form::checkbox('unavailable','0') !!}
                                         <label for="from_date">From Date</label>
                                        <input type="text" id="from_date" name="from_date" class="form-control datepicker" style="width:11%">
                                        
                                        <label for="to_date">To Date</label>
                                        <input type="text" id="to_date" name="to_date" class="form-control datepicker" style="width:11%">
                                       
                                         <button  style="margin-top:5px;"class="btn btn-primary" type="submit">Search</button>
                                    </div>

                                   
                                </form>
                            </div>
                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Store</th>
                                        <th>Categories</th>
                                        <th>Measurement Class</th>
                                        <th>Measurement Value</th>
                                        <th>Product Name</th>
                                        <th>Final Value</th>
                                        <th>MRP</th>
                                        <th>Qty Value</th>
                                        <th>Offer</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
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
         order: [[ 10, "desc" ]],
        buttons: [
            
            {
              extend: 'excel',
              text: 'Excel',
              className: 'exportExcel',
              filename: 'Export excel',
               exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ] //Your Colume value those you want
                           }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text: 'PDF',
                className: 'exportExcel',
                filename: 'Export excel',
                exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ] //Your Colume value those you want
                           }
            }, 
            {
              extend: 'print',
              text: 'Print',
              className: 'exportExcel',
              filename: 'Export excel',
               exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ] //Your Colume value those you want
                           }
            },  
        ],
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        processing: true,
        oLanguage: {
        sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
        },
        serverSide: true,
        ajax: {
            url: '{!! route('vendor-product.datatable') !!}?user_id={{ $user_id or '' }}',
            data: function (d) {

                d.user_id = $('[name=user_id]').val() ;
                d.is_offer = $('[name=is_offer]').val() ;
                d.category_id = $('[name=category_id]').val() ;
                d.to_date = $('input[name=to_date]').val();
                d.from_date = $('input[name=from_date]').val();
                d.unavailable = $('input[name=unavailable]').val();
            }
        },
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
            { data: 'user.full_name', name: 'user.full_name' },
            { data: 'category_id', name: 'category_id' },
            { data: 'product.measurement_class.name', name: 'product.measurement_class.name',orderable: false, searchable: false  },          
            { data: 'product.measurement_value', name: 'Product.measurement_value' },
            { data: 'product.name', name: 'product_id'},            
            { data: 'price', name: 'price' },
            { data: 'best_price', name: 'best_price',orderable: false, searchable: false  },
            { data: 'qty', name: 'qty' },
            { data: 'offer_name', name: 'offer.name',orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action',orderable: false, searchable: false }
        ]
    });
    $('#search-form').on('submit', function(e) {
		
		if($('[name=unavailable]').prop('checked')==true){

			$('[name=unavailable]').val(1)

		}else{

			$('[name=unavailable]').val(0)
		}
		
        window.table.draw();
        e.preventDefault();
    });
    $('.datepicker' ).datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
    });
    $('.select2-multiple').select2({
        placeholder: "Select Store",
        allowClear: true,
        width:"15%"
    });
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
});

function deleteRow(id){
    $.ajax({
        data: {
            id:id
        },
        type: "DELETE",
        url: "{{ url('admin/vendor-product') }}/"+id,
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
        url: "{!! route('admin.vendor-product.status') !!}",
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
function editTable() {
    $('#users-table').Tabledit({
        eventType: 'dblclick',
        editButton: true,
        deleteButton: false,
        saveButton: true,
        hideIdentifier: false,
        url: "{!! route('admin.vendor-product.edit-product-data') !!}",
        columns: {
            identifier: [0, 'id'],
            editable: [[6, 'price'],[7, 'best_price'],[8, 'qty'],[9, 'offer_id','<?php echo $offers;?>']]
        }
    });
}

</script>

@endpush
