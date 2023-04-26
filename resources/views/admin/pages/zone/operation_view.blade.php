@extends('admin.layouts.app')

@section('title', ' Zone Operation |')
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
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Zone Operation</h2>

                            <div class="clearfix"></div>
                           
                        </div>
                     
                        <!-- <div class="x_content"> -->

                        <table class="table table-striped table-bordered table-responsive" id="users-table">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Id</th>
                                    <th>Zone</th>
                                    <th>Vendor</th>
                                    <th>Shopper</th>
                                    <th>Driver</th>
                                    <th>Delivery Charges</th>
                                    <th>Delivery Times</th>
                                </tr>
                                </thead>
                            </table>
                       <!--  </div> -->
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

<script>

$(function() {

    window.table=$('#users-table').DataTable({

        scrollX: true,
        scrollCollapse: true,
        fixedColumns: true,
        dom: 'lBfrtip',
        buttons: [
            
            {
              extend: 'excel',
              text: 'Excel',
              className: 'exportExcel',
              filename: 'Export excel',
               exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] //Your Colume value those you want
                           }
            },
            {
              extend: 'pdf',
              text: 'PDF',
              className: 'exportExcel',
              filename: 'Export excel',
               exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] //Your Colume value those you want
                           }
            }, 
            {
              extend: 'print',
              text: 'Print',
              className: 'exportExcel',
              filename: 'Export excel',
               exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] //Your Colume value those you want
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
        ajax: '{!! route('operation_view.datatable') !!}',
        fnDrawCallback :function() {
            $('.data-toggle-coustom').bootstrapToggle({
            });
            $('.data-toggle-coustom').change(function() {
                var user_id =$(this).attr('user-id');
                changeStatus(user_id,$(this).val());
            })
        },
        columns: [
            { data: 'Slno', name: 'Slno' },
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'vendor', name: 'vendor',orderable: false, searchable: false },
            { data: 'shopper', name: 'shopper',orderable: false, searchable: false },
            { data: 'driver', name: 'driver' ,orderable: false, searchable: false},
            { data: 'delivery_charges', name: 'delivery_charges' },
            { data: 'delivery_times', name: 'delivery_times' },
        ]
    });
});

function updateUserZone(obj,zone_id,user_id) {

    $.ajax({
        data: {
            zone_id:zone_id,
            old_user_id:$(obj).siblings().val(),
            _method:'PATCH'
        },
        type: "PATCH",
        url: "{!! url('admin/operation') !!}/"+(user_id=='' ? 0 : user_id),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
            new PNotify({
                title: 'Success',
                text: data.message,
                type: 'success',
                styling: 'bootstrap3'
            });
            $(obj).siblings().val(user_id);
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

function updateDelivaryCharges(deliveryCharge,zone_id) {
    if(isNaN(deliveryCharge)){
        alert("please enter valid number");
        return false
    }
    $.ajax({
        data: {
            zone_id:zone_id,
            delivery_charges:parseFloat(deliveryCharge),
            _method:'PATCH'
        },
        type: "POST",
        url: "{!! url('admin/zone') !!}/"+zone_id,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
            console.log(data);
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

function updateDeliveryTimes(deliveryTimes,zone_id) {
    $.ajax({
        data: {
            zone_id:zone_id,
            package_id:deliveryTimes,
            _method:'PATCH'
        },
        type: "POST",
        url: "{!! url('admin/zone') !!}/"+zone_id,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
            console.log(data);
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