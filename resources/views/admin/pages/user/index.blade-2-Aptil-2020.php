@extends('admin.layouts.app')

@section('title', 'users |')

@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <style type="text/css">
        .dataTables_length {width: 20%;}
        .dt-buttons .btn {border-radius: 0px;padding: 4px 12px;}
        .btn{margin:1px;}
        
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
                            <h2>Users List </h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content table-responsive">
                            <div class="panel-body">
                                <form role="form" class="form-inline" id="search-form" method="POST">

                                    <div class="form-group">
                                       
                                        {!!  Form::select('user_type', Helper::$user_type,null, array('class' => 'form-control ','placeholder'=>'User Type')) !!}                                        
                                        {!!  Form::select('access_user_id',$accessLevels,null, array('class' => 'form-control ','placeholder'=>'Access User')) !!}
                                         <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                    
                                   
                                </form>
                            </div>
                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                <tr>
                                    <th align="left">Id</th>
                                    <th align="left">Name</th>
                                    <th align="left">Email</th>
                                    <th align="left">User type</th>                                    
                                    <th align="left">mobile</th>
                                    <th align="left">Access Level</th>
                                    <th align="left">Created At</th>
                                    <th align="left">Action</th>
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
<!-- NProgress -->
<script src="{{asset('public/assets/nprogress/nprogress.js')}}"></script>
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
<script src="{{asset('public/assets/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
<script src="{{asset('public/assets/jszip/dist/jszip.min.js')}}"></script>
<script src="{{asset('public/assets/pdfmake/build/pdfmake.min.js')}}"></script>
<script src="{{asset('public/assets/pdfmake/build/vfs_fonts.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
<script src="{{asset('public/js/bootstrap-toggle.min.js')}}"></script>


<!-- Datatables -->
<script>

$(function() {

    window.table=$('#users-table').DataTable({
     dom: 'lBfrtip',
     order: [[ 6, "desc" ]],
        buttons: [

            {
              extend: 'excel',
              text: 'Excel',
              className: 'exportExcel',
              filename: 'Export excel',
               exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6 ] //Your Colume value those you want
                           }
            },
            {
              extend: 'pdf',
              text: 'PDF',
              className: 'exportExcel',
              filename: 'User list',
               exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6 ] //Your Colume value those you want
                           }
            }, 
            {
              extend: 'print',
              text: 'Print',
              className: 'exportExcel',
              filename: 'User list',
                exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6 ] //Your Colume value those you want
                           }
            },  
        ],
       lengthMenu: [

            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        /*exportOptions: {
            modifer: {
                page: 'all',
                search: 'none',
                columns: [ 0, ':visible' ]
            }
        },*/

        responsive: false,
        processing: true,
        oLanguage: {
        sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
        },
        serverSide: true,
        ajax: {
            url:'{!! route('user.datatable') !!}',
            data: function (d) {

                d.user_type = $('[name=user_type]').val() ;
                d.access_user_id = $('[name=access_user_id]').val() ;
              //  d.unavailable = $('[name=unavailable]').val() ;
                // d.category_id = $('[name=category_id]').val() ;
                // d.to_date = $('input[name=to_date]').val();
                // d.from_date = $('input[name=from_date]').val();
            }
        },
        // ajax: '{!! route('user.datatable') !!}',
        fnDrawCallback :function() {
            $('.data-toggle-coustom').bootstrapToggle({
            });
            $('.data-toggle-coustom').change(function() {
                var user_id =$(this).attr('user-id');
                changeStatus(user_id,$(this).val());
            })
        },
        oSearch: {"sSearch": '<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>' },
        columns: [
            { data: 'id', name: 'id' },

            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'user_type', name: 'user_type' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'accesslevel', name: 'accesslevel' },            
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action',orderable: false, searchable: false }
        ]
    });
    $('#search-form').on('submit', function(e) {

        window.table.draw();
        e.preventDefault();
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
        url: "{{ url('admin/user') }}/"+id,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
            console.log(data);
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
        url: "{!! route('admin.user.status') !!}",
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
