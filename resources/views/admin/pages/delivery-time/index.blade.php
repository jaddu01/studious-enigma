@extends('admin.layouts.app')

@section('title', ' View Delivery Time |')
@push('css')

    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
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
                            <h2>Delivery Times</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Id</th>
                                    <th>day</th>
                                    <!-- <th>details_url</th> -->
                                    <th>Created At</th>
                                </tr>
                                </thead>
                            </table>
                            <script id="details-template" type="text/x-handlebars-template">

                                <table class="table details-table" id="posts-@{{id}}">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Form Time</th>
                                        <th>To Time</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </script>
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
<script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
<script src="{{asset('public/js/bootstrap-toggle.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/handlebarsjs/4.0.5/handlebars.min.js"></script>

<script>

$(function() {
    var template = Handlebars.compile($("#details-template").html());
    window.table=$('#users-table').DataTable({
       /* dom: "Bfrtip",
        buttons: [
            {
                extend: "copy",
                className: "btn-sm"
            },
            {
                extend: "csv",
                className: "btn-sm"
            },
            {
                extend: "excel",
                className: "btn-sm"
            },
            {
                extend: "pdfHtml5",
                className: "btn-sm"
            },
            {
                extend: "print",
                className: "btn-sm"
            },
        ],
        responsive: true,
        processing: true,*/
        oLanguage: {
                sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
                },
        serverSide: true,
        ajax: '{!! route('delivery-time.datatable') !!}',
        fnDrawCallback :function() {
            $('.data-toggle-coustom').bootstrapToggle();
            $('.data-toggle-coustom').change(function() {
                var region_id =$(this).attr('region-id');
                changeStatus(region_id,$(this).val());
            })
        },
        columns: [
            {
                "className":      'details-control',
                "orderable":      false,
                "searchable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            /* { data: 'details_url', name: 'details_url' },*/
            { data: 'created_at', name: 'created_at' },
        ]
    });

    // Add event listener for opening and closing details
    $('#users-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var tableId = 'posts-' + row.data().id;
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(template(row.data())).show();
            initTable(tableId, row.data());
            tr.addClass('shown');
            tr.next().find('td').addClass('no-padding bg-gray');
        }
    });

    function initTable(tableId, data) {
        //console.log(data);
        //alert(data.details_url);

        $('#' + tableId).DataTable({
            processing: true,
            serverSide: true,
            ajax: data.details_url,
            fnDrawCallback :function() {
                console.log(data);
                $('.data-toggle-coustom').bootstrapToggle();
                $('.data-toggle-coustom').change(function() {
                    var region_id =$(this).attr('region-id');
                    changeStatus(region_id,$(this).val());
                })
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'from_time', name: 'from_time' },
                { data: 'to_time', name: 'to_time' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action',orderable: false, searchable: false }
            ]
        })
    }
});

function deleteRow(id){
    $.ajax({
        data: {
            id:id
        },
        type: "DELETE",
        url: "{{ url('admin/delivery-time') }}/"+id,
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
        url: "{!! route('admin.delivery-time.status') !!}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
           // window.table.draw();
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