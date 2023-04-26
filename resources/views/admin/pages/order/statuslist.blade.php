@extends('admin.layouts.app')

@section('title', ' Orders |')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
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
                            <h2>Order</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="panel-body">
                                

                            </div>
                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                <tr>
                                    <th>Order code</th>
                                    <th>Message</th>
                                    <th>Name</th>
                                    
                                    <th>Created At</th>
                                    
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    
    
    <?php 
    
   // print_r(Helper::$order_status);
    
    
    ?>
    <input type="hidden" name="order_id" value="" id="order_id">
    @foreach(Helper::$order_status as $key=>$data)
    
    <p>{!!  Form::checkbox('order_status',$key,array('class' => 'statuscheckbox') ) !!}
    {!!  Form::label($data) !!}
     
    </p>
    @endforeach
 
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
<script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('public/js/select2.min.js')}}"></script>

<script>

$(function() {

    window.table=$('#users-table').DataTable({
       /* dom: "Bfrtip",*/
        buttons: [

        ],
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '{!! route('order.datatablenew') !!}?user_id={{ $user_id or '' }}',
            data: function (d) {

                d.order_id = '<?php echo $orderID;?>';
       
            }
        },
        fnDrawCallback :function() {



            $('.data-toggle-coustom').bootstrapToggle();
            $('.data-toggle-coustom').change(function() {
                var orders_id =$(this).attr('order-id');
                changeStatus(orders_id,$(this).val());
            })
        },
      oSearch: {"sSearch": '<?php echo isset($_GET['phone_number']) ? $_GET['phone_number'] : '' ?>' },
      columns: [
            { data: 'order_code', name: 'order_code' },
            { data: 'message', name: 'message' },
            { data: 'name', name: 'name' },


            { data: 'created_at', name: 'created_at' },
          
        ]
    });

    $('#search-form').on('submit', function(e) {
        window.table.draw();
        e.preventDefault();
    });
    $('.datepicker' ).datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
    });
    $('#order_status').select2({
        placeholder: "Order Status",
        allowClear: true
    });
    $('#driver').select2({
        placeholder: "Driver",
        allowClear: true
    });
    $('#shopper').select2({
        placeholder: "Shopper",
        allowClear: true
    });
    $('#vendor').select2({
        placeholder: "Vender",
        allowClear: true
    });
   
});

function deleteRow(id){
    $.ajax({
        data: {
            id:id
        },
        type: "DELETE",
        url: "{{ url('admin/offer') }}/"+id,
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

