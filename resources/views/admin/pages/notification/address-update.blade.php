@extends('admin.layouts.app')
@section('title', 'Notification |')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <style type="text/css">
        .toggle-off {
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
        }
        .toggle.off {
            border-color: rgba(0, 0, 0, .25);
        }

        .toggle-handle {
            background-color: white;
            border: thin rgba(0, 0, 0, .25) solid;
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
                            <h2>Driver Notifications</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Notification</th>
                                    <th>Customer</th>
                                    <th>Address</th>
                                    <th>Description</th>
                                    <th>Driver</th>
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
     <!--address model -->
    <div id="address" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Address Details</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered" id="address-table">
                                <thead> </thead>
                                <tbody>
                                <tr>
                                    <td>Customer</td>
                                    <td><p id="customer"></p></td>
                                   
                                </tr>
                                 <tr>
                                    <td>Address Name</td>
                                    <td><p id="name"></p></td>
                                   
                                </tr>
                                 <tr>
                                    <td>Latitude</td>
                                    <td><p id="lat"></p></td>
                                   
                                </tr>
                                 <tr>
                                    <td>Longitude</td>
                                    <td><p id="lng"></p></td>
                                   
                                </tr>
                                 <tr>
                                    <td >Address Location</td>
                                    <td><p id="address_detail"></p></td>
                                   
                                </tr>
                                 <tr>
                                    <td>Address Description</td>
                                    <td><p id="description"></p></td>
                                   
                                </tr>
                            </tbody>
                               
                            </table>
                </div>
                <div class="modal-footer">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <a href="" id="address_url"><button type="button" class="btn btn-success">View Customer</button></a>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
      
    </div>
@endsection
@push('scripts')
<!--  <script src="{{asset('public/js/jquery.min.js')}}"></script>
 -->
   
 
 
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
   <script src="{{asset('public/js/bootstrap.min.js')}}"></script>

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
                order: [[ 5, "desc" ]],
                processing: true,
                oLanguage: {
                sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
                },
                serverSide: true,
                ajax: '{!! route('notification.addressStatusData') !!}',
                columns: [
                    { data: 'Slno', name: 'Slno' },
                    { data: 'notification', name: 'notification' },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'address', name: 'address' },
                    { data: 'description', name: 'description' },
                    { data: 'driver_name', name: 'driver_name' },
                    { data: 'created_at', name: 'created_at' },
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
                url: "{{ url('admin/notification') }}/"+id,
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

    
        function openOrderAddressModel(id) {
            $.ajax({
                url: "{!! route('admin.notification.addressDetails') !!}",
                type: 'GET',
                data: {
                    id : id,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                success: function( data ) {
                    //console.log(data)
                    var address = data.data;
                    console.log(address.address);
                    
                    $("#customer").text(address.user_name);
                    $("#name").text(address.name);
                    $("#lat").text(address.lat);
                    $("#lng").text(address.lng);
                    $("#address_detail").text(data.data.address);
                    $("#description").text(address.description);
                    $("#address_url").attr("href","{{url('/admin/customer')}}/"+address.customer_id);
                    $("#address").modal('show');

                },
                error: function( data, status, error ) {

                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: "error",
                        styling: 'bootstrap3'
                    });
                }
            });
                
            
             
        }


    </script>
@endpush