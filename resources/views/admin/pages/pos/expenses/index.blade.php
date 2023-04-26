@extends('admin.layouts.app')

@section('title',$title)
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
                            <h2>{{$title}}</h2>
                           <!--  <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">Settings 1</a>
                                        </li>
                                        <li><a href="#">Settings 2</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul> -->
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Date</th>
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
    <script src="{{asset('public/assets/datatables.net-scroller/js/datatables.scroller.min.js')}}"></script>
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
               autoWidth:false,
                scrollX:        true,
                scrollCollapse: true,
                fixedColumns: true,
                dom: 'lBfrtip',
                buttons: [

                ],
                order: [[ 4, "desc" ]],
                responsive: false,
                processing: true,
                oLanguage: {
                sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
                },
                serverSide: false,
                ajax: '{!! route('expenses.datatable') !!}',
                fnDrawCallback :function() {
                    $('.data-toggle-coustom').bootstrapToggle();
                    
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    { data: 'description', name: 'description',orderable: false, searchable: false },
                    { data: 'price', name: 'price' },
                    { data: 'date', name: 'date' },
                    { data: 'action', name: 'action',orderable: false, searchable: false }
                ]
            });
        });

        function deleteRow(id){
            $.ajax({
                data: {
                    id:id
                },
                type: "DELETE",
                url: "{{ url('admin/pos/expenses/') }}/"+id,
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
                url: "{!! route('admin.brand.status') !!}",
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