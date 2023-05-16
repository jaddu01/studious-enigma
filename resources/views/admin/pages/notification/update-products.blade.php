@extends('admin.layouts.app')
@section('title', ' Shopper Notifications |')
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
                            <h2>Shopper Notifications</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Notification</th>
                                    <th>Vendor</th>
                                    <th>Product</th>
                                    <th>price</th>
                                    <th>Offer price</th>
                                    <th>Type</th>
                                    <th>Shopper</th>
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
                    <h4 class="modal-title">Product Details </h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered" id="address-table">
                                <thead> </thead>
                                <tbody>
                                <tr>
                                    <td>Vendor</td>
                                    <td><p id="vendor-text"></p></td>
                                   
                                </tr>
                                 <tr>
                                    <td>Product</td>
                                    <td><p id="product-text"></p></td>
                                   
                                </tr>
                                <!--  <tr>
                                    <td>Measurement</td>
                                    <td><p id="measurement-text"></p></td>
                                   
                                </tr> -->
                                 <tr>
                                    <td>Price</td>
                                    <td><p id="price-text"></p></td>
                                   
                                </tr>
                                 <tr>
                                    <td >Offer Price</td>
                                    <td><p id="offer-price-text"></p></td>
                                   
                                </tr>
                                 <tr>
                                    <td>Status</td>
                                    <td><p id="status-text"></p></td>
                                   
                                </tr>
                            </tbody>
                               
                            </table>
                </div>
                <div class="modal-footer">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <a href="" id="address_url"><button type="button" class="btn btn-success">View Product</button></a>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
      
    </div>
@endsection
@push('scripts')
<!--  <script src="{{asset('public/js/jquery.min.js')}}"></script>
<script src="{{asset('public/js/bootstrap-toggle.min.js')}}"></script> -->
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
    
    <script src="{{asset('public/js/bootstrap.min.js')}}"></script>

    <script>

        $(function() {

            window.table=$('#users-table').DataTable({
                scrollX:        true,
                scrollCollapse: true,
                fixedColumns: true,
                dom: 'lBfrtip',
                buttons: [

                ],
                order: [[8, "desc" ]],
                processing: true,
                oLanguage: {
                sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
                },
                serverSide: true,
                ajax: '{!! route('notification.updateproductData') !!}',
                columns: [
                    { data: 'Slno', name: 'Slno' },
                    { data: 'heading', name: 'heading' },
                    { data: 'vendor', name: 'vendor' },
                    { data: 'product_name', name: 'product_name' },
                    { data: 'price', name: 'price' },
                    { data: 'offer_price', name: 'offer_price' },
                    { data: 'type', name: 'type' },
                    { data: 'shopper', name: 'shopper' },
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

        function changeStatus(){
            $.ajax({
                data: {
                    id: '48',
                    _method:'PATCH'
                },
                type: "PATCH",
                url: "{!! route('admin.notification.status') !!}",
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

 function openOrderAddressModel(id) {
            $.ajax({
                url: "{!! route('admin.notification.addressDetails') !!}",
                type: 'GET',
                data: {
                    id : id,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                success: function( data ) {
                    console.log(data)
                    var address = data.data;
                    console.log(address.product_id);
                    $("#vendor-text").text(address.vendor);
                    $("#product-text").text(address.product_name);
                    if(address.type != 'out of stock'){
                        $("#price-text").text(address.requested_price);
                        $("#offer-price-text").text(address.requested_offer_price);
                    }else{
                        $("#price-text").text(' ');
                        $("#offer-price-text").text(' ');
                    }
                    
                    $("#status-text").text(address.message);
                   
                    
                    if(address.type == "out of stock" || address.type == "update"){
                         if(address.type == "update"){
                             $("#address_url").attr("href","{{url('admin/vendor-product/')}}/"+address.product_id+"/edit");
                         }else{
                            $("#address_url").attr("href","{{url('admin/vendor-product/')}}/"+address.vendor_product_id+"/edit");
                         }

                        
                       
                    }else{
                         $("#address_url").attr("href","{{url('admin/vendor-product/')}}/"+address.vendor_id+"/edit");
                    }
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