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
                            <h2>Order</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="panel-body">
                                <form role="form" class="form-inline" id="search-form" method="POST" autocomplete="off">
                                    <div class="form-group">
                                        {{--<button class="btn btn-success" type="button">Today Orders</button>--}}
                                        {!!  Form::select('order_type',['all'=>'All order','today'=>'Today Order'],$order_type, array('class' => 'form-control ')) !!}
                                        </div><br />
                                        <div class="form-group">
                                        <span>Delivery Date</span>
                                      {{--  {!!  Form::select('user_id', $users,$user_id, array('class' => 'form-control select2-multiple','placeholder'=>'Store')) !!}
                                        {!!  Form::select('category_id',$categories,null, array('class' => 'form-control ','placeholder'=>'category')) !!}
                                        {!!  Form::select('is_offer', Helper::$is_status,null, array('class' => 'form-control ','placeholder'=>'is offer')) !!}--}}
                                        <label for="from_date">From </label>
                                        <input type="text" id="from_date" name="delivery_from_date" class="form-control datepicker">
                                        </div>
                                        <div class="form-group">
                                        <label for="to_date">To </label>
                                        <input type="text" id="to_date" name="delivery_to_date" class="form-control datepicker">
                                        </div>
                                        <!--<div class="form-group">
                                        <span>Created At</span>
                                        <label for="from_date">From </label>
                                        <input type="text" id="from_date" name="created_from_date" class="form-control datepicker"></div>
                                        <div class="form-group">
                                        <label for="to_date">To </label>
                                        <input type="text" id="to_date" name="created_to_date" class="form-control datepicker">
                                        </div>-->
                                        <br />
                                        
                                      <div class="form-group">  <span>Total Amount</span>
                                        <label for="from_date">From: </label>
                                        <input type="text" name="total_amount_from" class="form-control "></div>
                                        <div class="form-group">
                                        <label for="to_date">To: </label>
                                        <input type="text" id="to_date" name="total_amount_to" class="form-control ">
                                       </div>
                                        <br />
                                        <div class="form-group">                                        
                                        {!!  Form::select('order_status', Helper::$order_status,null, array('class' => 'form-control select2-multiple','placeholder'=>'Order Status','id'=>'order_status')) !!}
                                        </div>
                                         <div class="form-group">
                                        {!!  Form::select('zone_id', $zones,null, array('class' => 'form-control select2-multiple','placeholder'=>'Zone','id'=>'zone')) !!}
                                        </div>
                                         <div class="form-group">
                                        {!!  Form::select('vendor_id', $vandors,null, array('class' => 'form-control select2-multiple','placeholder'=>'Store','id'=>'vendor')) !!}
                                        </div>
                                         <div class="form-group">
                                        {!!  Form::select('shopper_id', $shoper,30, array('class' => 'form-control select2-multiple','placeholder'=>'Shopper','id'=>'shopper')) !!}
                                        </div>
                                         <div class="form-group">
                                        {!!  Form::select('driver_id',$driver,30, array('class' => 'form-control select2-multiple','placeholder'=>'Driver','id'=>'driver')) !!}
                                        </div> <br />
						<div class="form-group">
                                    <button class="btn btn-primary" type="submit">Apply filter</button>
                                    </div>
                                </form>

                            </div>
                            
                            <table class="table table-striped table-bordered table-responsive" id="users-table">
                                <thead>
                                <tr>

                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Phone Number</th>
                                    <!-- <th>Order stauts</th> -->
                                    <th>Billing Date</th>
                                    <!-- <th>Time Slot</th> -->
                                    <th>Total amount</th>
                                    <th>Zone</th>
                                    <!-- <th>Shopper</th>
                                    <th>Driver</th> -->
                                    <th>Address</th>
                                    <!-- <th>transaction id</th> -->
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
    
    <div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content" style="width:20%">
    <span class="close">&times;</span>
    
    
    <?php 
    
   // print_r(Helper::$order_status);
    
    
    ?>
    <input type="hidden" name="order_id" value="" id="order_id">
    
    @foreach(Helper::$order_status as $key=>$data)
    <p><input name="order_status" class="order_status" type="checkbox" value="{{$key}}"> <label for="{{$data}}">{{$data}}</label></p>
   
    @endforeach
 
  </div>

</div>
    <div id="makeacall" class="modal">

  <!-- Modal content -->
  <div class="modal-content model_call">
    
    <div><span class="close">&times;</span><br><br></div>
 
    <div><a href="'.route('order.show',$orders->id).'"  class="btn btn-success width-50">Call Customer</a><br>
    <a href="'.route('order.show',$orders->id).'"  class="btn btn-success width-50">Call Shopper</a><br>
    <a href="'.route('order.show',$orders->id).'"  class="btn btn-success width-50">Call Driver</a><br>
    <a href="{{url('admin/order')}}" class="btn btn-success width-50">Cancel</a></div>
  <br>
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

<script>
 $("#to_date").change(function (selected){
        var startDate = new Date($("#from_date").val());
        var endDate =  new Date($("#to_date").val());
        if (endDate < startDate){
            alert('End date can not be less than start date');
            $("#to_date").val('');
        }                 
    });
    $("#from_date").change(function (selected){
        var startDate = new Date($("#from_date").val());
        var endDate =  new Date($("#to_date").val());
        if (endDate < startDate){
            alert('End date can not be less than start date');
            $("#to_date").val('');
        }                 
    });
$(function() {
 $('.order_status').click(function() {
        $('input:checkbox').not(this).prop('checked', false);
      // alert($(this).val())
       
    $.ajax({
		url: "{!! route('admin.order.status') !!}",
		type: 'PATCH',
		// dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
		data: {
		_method: 'PATCH',
		status : $(this).val(),
		id : $('body #order_id').val(),      
		_token: '{{ csrf_token() }}'
		},
		success: function( data ) {
		
        var modal = document.getElementById('myModal');
		modal.style.display = "none";
		alertify.success("Success "+data.message);
		window.table.draw();

		},
		error: function( data ) {
      //  console.log(data);
		alertify.error("some thinng is wrong");

		}
    });
           
        
    });
    window.table=$('#users-table').DataTable({
        /*autoWidth :false,*/
        
        scrollX:        true,
        scrollCollapse: true,
        fixedColumns: true,
        dom: 'lBfrtip',
        order:[[8,"desc"]],
        buttons: [
            {
              extend: 'excel',
              text: 'Excel',
              className: 'exportExcel',
              filename: 'Export order list',
              exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] //Your Colume value those you want
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
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] //Your Colume value those you want
                           }
              
            }, 
            {
              extend: 'print',
              text: 'Print',
              className: 'exportExcel',
              filename: 'Export order list',
              exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] //Your Colume value those you want
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
            url: '{!! route('pos.orders.datatable') !!}?user_id={{ $user_id or '' }}',
            data: function (d) {
                d.order_status = $('[name=order_status]').val();
                d.zone_id = $('[name=zone_id]').val();
                d.order_type = $('[name=order_type]').val();
                d.vendor_id = $('[name=vendor_id]').val();
                d.shopper_id = $('[name=shopper_id]').val();
                d.driver_id = $('[name=driver_id]').val();
                d.delivery_from_date = $('input[name=delivery_from_date]').val();
                d.delivery_to_date = $('input[name=delivery_to_date]').val();
                d.created_from_date = $('input[name=created_from_date]').val();
                d.created_to_date = $('input[name=created_to_date]').val();
                d.total_amount_from = $('input[name=total_amount_from]').val();
                d.total_amount_to = $('input[name=total_amount_to]').val();
                d.cust_type = $('[name=cust_type]').val();
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
            { data: 'user.full_name', name: 'users.name',orderable: false},
            { data: 'user.phone', name: 'user_phone',orderable: false },
            //{ data: 'order_status', name: 'order_status',orderable: false },
            { data: 'delivery_date', name: 'delivery_date' },
            //{ data: 'time_slot', name: 'time_slot' ,orderable: false },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'zone.name', name: 'zone.name',orderable: false },
            //{ data: 'shopper', name: 'shopper',orderable: false },
            //{ data: 'driver', name: 'driver',orderable: false },
            { data: 'address', name: 'address' },
            //{ data: 'transaction_id', name: 'transaction_id' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action',orderable: false, searchable: false }
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
        placeholder: "Store",
        allowClear: true
    });
    $('#zone').select2({
        placeholder: "Zone",
        allowClear: true
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

var modal = document.getElementById('myModal');
function popupchangestatus(id,status){
    modal.style.display = "block";
    $('input:checkbox').prop('checked', false);
    $(":checkbox[value='"+status+"']").prop("checked","true");
	//$('input:checkbox').prop('checked', false);
	$('body #order_id').val(id);
}
var span = document.getElementsByClassName("close")[0];
span.onclick = function() {
	modal.style.display = "none";
}
function makeAcall(id){
 $.ajax({
        data: {
            id:id
        },
        type: "POST",
        url: "{{ url('admin/order/showDetail') }}/"+id,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
           $("#makeacall a:first").attr("href", "tel:"+data.data.user)
           $("#makeacall a:nth-of-type(2)").attr("href", "tel:"+data.data.shopper)
           $("#makeacall a:nth-of-type(3)").attr("href", "tel:"+data.data.driver)
           
           $("#makeacall").css("display","block")
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
	
	
//$("#makeacall").css("display","block")

}	


		$(".close").on("click",function(){
		$("#makeacall").css("display","none")
		})
</script>
@endpush

