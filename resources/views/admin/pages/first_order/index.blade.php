@extends('admin.layouts.app')
@section('title', ' First Orders |')
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
                            <h2>First Order</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table class="table table-striped table-bordered table-responsive" id="users-table" style="width:100%">
                                <thead>
                                <tr>
                                    <th> id</th>
                                    <th>Products</th>
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
<script src="{{asset('public/assets/datataproductbles.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
<script src="{{asset('public/assets/jszip/dist/jszip.min.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
<script src="{{asset('public/js/bootstrap-toggle.min.js')}}"></script>
<script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script>
$(function() {

window.table=$('#users-table').DataTable({
        /*autoWidth :false,*/
        
        scrollX:        true,
        scrollCollapse: true,
        fixedColumns: true,
        dom: 'lBfrtip',
        order:[[2,"desc"]],
        buttons: [
            {
              extend: 'excel',
              text: 'Excel',
              className: 'exportExcel',
              filename: 'Export order list',
              exportOptions: {
                       columns: [ 0, 1, 2] //Your Colume value those you want
                           }
            },
            {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            text: 'PDF',
            className: 'exportExcel',
            filename: 'Export order list',
            exportOptions: {
                       columns: [ 0, 1, 2] //Your Colume value those you want
                           }
              
            }, 
            {
              extend: 'print',
              text: 'Print',
              className: 'exportExcel',
              filename: 'Export order list',
              exportOptions: {
                       columns: [ 0, 1, 2] //Your Colume value those you want
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
            url: '{!! route('first_order.datatable') !!}?user_id={{ $user_id or '' }}',
 
        },

        fnDrawCallback :function() {
            $('.data-toggle-coustom').bootstrapToggle();
            $('.data-toggle-coustom').change(function() {
                var id =$(this).attr('first-order-id');
                changeStatus(id,$(this).val());
            })
        },
      columns: [
            { data: 'id', name: 'id' },
            { data: 'free_product', name: 'free_product' },
            { data: 'action', name: 'action',orderable: false, searchable: false }
        ]
    });

});

function deleteRow(id){
     if(!confirm("Are You Sure to delete this")){
     return false;
    }
    $.ajax({
        data: {
            id:id
        },
        type: "DELETE",
        url: "{{ url('admin/first_order') }}/"+id,
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
        url: "{!! route('admin.first-order.status') !!}",
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

