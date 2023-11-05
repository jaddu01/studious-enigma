@extends('admin.layouts.app')

@section('title', 'Customers |')

@push('css')
    <link href="{{ asset('public/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <style type="text/css">
        .dataTables_length {
            width: 20%;
        }

        .dt-buttons .btn {
            border-radius: 0px;
            padding: 4px 12px;
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
            <div class="page-title">
            </div>
            <div class="clearfix"></div>
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Stock Verification </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="{{route('admin.stock.verification.create')}}"><button class="btn btn-success">Create New</button></a>
                            </div>
                        </div>
                        @component('components.datatable.tablestructure')
                            @slot('tableID')
                                abc
                            @endslot

                            @slot('tableHeading')
                                <th>Stock Verification No.</th>
                                <th>Stock Verification Date</th>
                                <th>Total Products</th>
                                <th>Total Physical Qty</th>
                                <th>Difference Amount</th>
                                <th>Approved Qty</th>
                                <th>Status</th>
                                <th>Actions</th>
                            
                            @endslot
                        @endcomponent

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
    <script src="{{ asset('public/assets/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('public/assets/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('public/assets/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('public/assets/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('public/assets/pnotify/dist/pnotify.js') }}"></script>
    <script src="{{ asset('public/assets/pnotify/dist/pnotify.buttons.js') }}"></script>
    <script src="{{ asset('public/assets/pnotify/dist/pnotify.nonblock.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap-toggle.min.js') }}"></script>


    <!-- Datatables -->
    <script>
        // $(function() {

        //     window.table=$('#users-table').DataTable({
        //         dom: 'lBfrtip',
        //         buttons: [

        //             {
        //               extend: 'excel',
        //               text: 'Excel',
        //               className: 'exportExcel',
        //               filename: 'Customers list',
        //                exportOptions: {
        //                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] //Your Colume value those you want
        //                            }
        //             },
        //             {
        //               extend: 'pdf',
        //               text: 'PDF',
        //               className: 'exportExcel',
        //               filename: 'Customers list',
        //                exportOptions: {
        //                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] //Your Colume value those you want
        //                            }
        //             }, 
        //             {
        //               extend: 'print',
        //               text: 'Print',
        //               className: 'exportExcel',
        //               filename: 'Customers list',
        //                exportOptions: {
        //                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] //Your Colume value those you want
        //                            }
        //             },  
        //         ],
        //         lengthMenu: [
        //             [ 10, 25, 50, -1 ],
        //             [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        //         ],
        //         responsive: false,
        //         processing: true,
        //         oLanguage: {
        //         sProcessing: "<img style='width:50%;height:auto' src='{{ asset('public/upload/loader.gif') }}'>"
        //         },
        //         serverSide: true,

        //         ajax: {
        //             url: '{!! route('customer.datatable') !!}',
        //          data: function (d) {
        //                 d.cust_type = $('[name=cust_type]').val();
        //             }
        //         },
        //         fnDrawCallback :function() {
        //             $('.data-toggle-coustom').bootstrapToggle({
        //             });
        //             $('.data-toggle-coustom').change(function() {
        //                 var user_id =$(this).attr('user-id');
        //                 changeStatus(user_id,$(this).val());
        //             })
        //         },
        //         columns: [
        //             { data: 'id', name: 'id' ,orderable: false, searchable: false},
        //             { data: 'name', name: 'name' },
        //             { data: 'phone_number', name: 'phone_number' },
        //             { data: 'email', name: 'email' },
        //             { data: 'membership_name', name: 'membership_name',orderable: false, searchable: false},
        //             { data: 'membership_to', name: 'membership_to' },
        //             { data: 'wallet_amount', name: 'wallet_amount' },
        //             { data: 'coin_amount', name: 'coin_amount' },
        //             { data: 'referred_by', name: 'referred_by' },
        //             { data: 'no_of_order', name: 'no_of_order' },
        //             { data: 'delivered_order', name: 'delivered_order' },
        //             { data: 'total_amount', name: 'total_amount' },
        //             { data: 'created_at', name: 'created_at' },
        //             { data: 'action', name: 'action',orderable: false, searchable: false }
        //         ]
        //     });
        // });


        // function deleteRow(id){
        //     if(!confirm("Are You Sure to delete this")){
        //      return false;
        //     }
        //     $.ajax({
        //         data: {
        //             id:id
        //         },
        //         type: "DELETE",
        //         url: "{{ url('admin/customer') }}/"+id,
        //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //         success: function( data ) {
        //             window.table.draw();

        //             new PNotify({
        //                 title: 'Success',
        //                 text: data.message,
        //                 type: 'success',
        //                 styling: 'bootstrap3'
        //             });

        //         },
        //         error: function( data ) {
        //             new PNotify({
        //                 title: 'Error',
        //                 text: 'something is wrong',
        //                 type: "error",
        //                 styling: 'bootstrap3'
        //             });
        //         }
        //     });
        // }

        // function changeStatus(id,status){
        //     $.ajax({
        //         data: {
        //             id:id,
        //             status:status,
        //             _method:'PATCH'
        //         },
        //         type: "PATCH",
        //         url: "{!! route('admin.customer.status') !!}",
        //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //         success: function( data ) {
        //             window.table.draw();
        //             new PNotify({
        //                 title: 'Success',
        //                 text: data.message,
        //                 type: 'success',
        //                 styling: 'bootstrap3'
        //             });

        //         },
        //         error: function( data ) {
        //             new PNotify({
        //                 title: 'Error',
        //                 text: 'something is wrong',
        //                 type: "error",
        //                 styling: 'bootstrap3'
        //             });

        //         }
        //     });
        // }
    </script>
@endpush
