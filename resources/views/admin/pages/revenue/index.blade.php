@extends('admin.layouts.app')

@section('title', ' Revenues |')
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
        .dt-buttons .btn {border-radius: 0px;padding: 4px 12px;}
        .sum_table{display: block; overflow-x: auto; white-space: nowrap;}
        #sum-table .dataTables_empty{display:none;}
        #sum-table_length ,#sum-table_info, #sum-table_paginate, #sum-table_filter{display: none;}
        #sum-table_wrapper .dt-buttons{margin-bottom:10px;}

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
                            <h2>Revenues</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content ">
                            <div class="panel-body">
                               <form role="form" class="form-inline" id="search-form" method="POST">

                                    <div class="form-group">
                                   
                                        <label for="delivery_date">Delivery Date</label>
                                        <label for="delivery_from_date">From</label>
                                        <input type="text" id="delivery_from_date" name="delivery_from_date" class="form-control datepicker">
                                        <label for="delivery_to_date">To</label>
                                        <input type="text" id="delivery_to_date" name="delivery_to_date" class="form-control datepicker">
                                         <label for="to_date">Created At</label>
                                        <label for="to_date">From</label>
                                        <input type="text" id="from_date" name="from_date" class="form-control datepicker">
                                        <label for="from_date">To</label>
                                        <input type="text" id="to_date" name="to_date" class="form-control datepicker">
                                            {!!  Form::select('vendor_id', $vendor,null, array('class' => 'form-control','placeholder'=>'Vendor','id'=>'vendor')) !!}
                                            {!!  Form::select('zone_id', $zone,null, array('class' => 'form-control','placeholder'=>'Zone','id'=>'zone')) !!}
                                        <br><br>
                                       
                                        <br><br>
                                        
                                        {!!  Form::select('verience',['positive'=>'Positive','negative'=>'Negative','zero'=>'Zero'],null, array('class' => 'form-control select-verience','id'=>'verience','placeholder'=>'Verience')) !!}
                                   
                                       
                                        {!!  Form::select('verience_revenue',['positive'=>'Positive','negative'=>'Negative','zero'=>'Zero'],null, array('class' => 'form-control ','id'=>'verience_revenue','placeholder'=>'Verience Revenue')) !!}
                                   
                                                                        
                                        {!!  Form::select('delivery_charge', ['yes'=>'Yes','no'=>'No'],null, array('class' => 'form-control','placeholder'=>'Delivery Charge','id'=>'delivery_charge')) !!}
                                      
                                        
                                        {!!  Form::select('admin_discount',['yes'=>'Yes','no'=>'No'],null, array('class' => 'form-control ','id'=>'admin_discount','placeholder'=>'Admin Discount')) !!}
                              
                                       <!-- 
                                        {!!  Form::select('promo_code',['yes'=>'Yes','no'=>'No'],null, array('class' => 'form-control ','id'=>'promo_code','placeholder'=>'Promo Code Discount')) !!} -->
                                    
                                        <?php 
                                            /*transuction status except disputed*/
                                        $Tstatus = Helper::$transaction_status;
                                        $transStatusArray = array_except(Helper::$transaction_status, array('2')); ?>                     
                                        {!!  Form::select('transaction_status', $transStatusArray,null, array('class' => 'form-control','placeholder'=>'Transaction Status','id'=>'transaction_status')) !!}
                                 
                                        <label for="to_date">Revenue </label>
                                        <label for="to_date">From </label>
                                        <input type="text" style="width: 5%;" name="rev_from" class="form-control">
                                        <label for="from_date"> To</label>
                                        <input type="text" style="width: 5%;"  name="rev_to" class="form-control"> 
                                        <label for="rev_perc_from">Revenue Percentage </label>
                                        <label for="rev_perc_from">From </label>
                                        <input type="text" style="width: 5%;"  name="rev_perc_from" class="form-control">
                                        <label for="rev_perc_to"> To</label>
                                        <input type="text" style="width: 5%;" name="rev_perc_to" class="form-control"> 
                                   
                                       <button class="btn btn-primary" type="submit">Search</button>
                                        </div>
                                         
                                   
                                </form> 
                            
                            </div>
                            <div class="dt-buttons btn-group"><a class="btn sum-table-excel btn-default buttons-excel buttons-html5 exportExcel" tabindex="0" aria-controls="sum-table"><span>Excel</span></a></div>
                             <table class="table-responsive sum-table table-striped table-bordered" id="sum-table" style="width:100%;margin-bottom: 10px;">
                                <thead>
                                    <tr>
                                        <th>No of order</th>
                                        <th>Vendor</th>
                                        <th>Commission</th>
                                        <th>Total-amount</th>
                                        <th>Sub-total</th>
                                        <th>Vendor Invoice </th>
                                        <th>Verience </th>
                                        <th>Verience Revenue</th>
                                        <th>Vendor Revenue</th>
                                        <th>Delivery Charge</th>
                                        <th>Admin Discount</th>
                                        <!-- <th>Promo Code Discount</th> -->
                                        <th>Total Revenue</th>
                                        <th>Revenue Percentage</th>
                                        <th>Average Revenue Percentage</th>
                                        <th>Delivery Revenue Percentage</th>
                                        <th>Product Revenue Percentage</th>
                                    </tr>
                                   </thead>
                                   <tbody>
                                   
                                     <tr role="row" class="odd">
                                        <td id="no_of_order"> </td>
                                        <td id="sum_vendor"></td>
                                        <td id="sum_vendor_commission"></td>
                                        <td id="sum_total_amount"></td>
                                        <td id="sum_sub_total"></td>
                                        <td id="sum_vendor_invoice"></td>
                                        <td id="sum_varience"></td>
                                        <td id="sum_varience_revenue"></td>
                                        <td id="sum_vendor_revenue"></td>
                                        <td id="sum_delivery_charge"></td>
                                        <td id="sum_admin_discount"></td>
                                       
                                        <td id="sum_total_revenue"></td>
                                        <td id="sum_revenue_percentage"></td>
                                        <td id="avg_revenue_percentage"></td>
                                        <td id="delivery_revenue_percentage"></td>
                                        <td id="product_revenue_percentage"></td>
                                    </tr>
                                    </tbody>
                                   
                                
                               
                            </table>
                            <table class="table table-striped table-bordered display nowrap" id="users-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <!-- <th>Id</th> -->
                                        <th>Order Code</th>
                                        <th>Vendor</th>
                                        <th>Commission</th>
                                        <th>Total-amount</th>
                                        <th>Sub-total</th>
                                        <th>Vendor Invoice </th>
                                        <th>Verience </th>
                                        <th>Verience Revenue</th>
                                        <th>Vendor Revenue</th>
                                        <th>Delivery Charge</th>
                                        <th>Admin Discount</th>
                                        <!--   <th>Promo Code Discount</th> -->
                                        <th>Total Revenue</th>
                                        <th>Revenue Percentage</th>
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
  <div class="modal-content">
    <span class="close">&times;</span>

     <table class="table table-striped table-bordered" id="">
                                <thead>
                                    <tr>
                        
                                    <th>Sub-total</th>
                                    <th>Vendor Invoice </th>
                                    <th>Verience </th>
                                    <th>Verience Revenue</th>
                                    <th>Vendor Revenue</th>
                                       
                                    </tr>
                                    <tr>
                                        
                                        <th><input type="number" value="" name="subtotal" readonly="readonly"></th>
                                       
                                        <th><input type="number" value="" name="vendor_invoice" required="required" > <span class="required">*</span> </th>
                                         <th><input type="text" value="" name="verience" readonly="readonly" > </th>
                                        <th><input type="text" value="" name="verience_revenue" required="required"> <span class="required">*</span> </th>
                                        <th><input type="number" value="" name="vendor_revenue" readonly="readonly"></th>
										<input type="hidden" value="" name="order_id" >
                                    </tr>
                                   
                                </thead>
                            </table>
					<button  class="btn btn-success" onclick="OrderDetailSave()" >Save</button>
  </div>

</div>   
 <div id="commentModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content" style="width: 50%;">
                    <span class="close" id="cclose">&times;</span>
                   <!--  {!! Form::open(['route' => 'revenue.comment.add','method'=>'post','class'=>'form-horizontal form-label-left validation']) !!} -->
                    <div class="form-group">
                        <input type="hidden" value="" name="comment_order_id" >
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Comment
                                    <span class="required">*</span>
                                </label>
                        {!! Form::textarea('comment',null,['class'=>'form-control', 'id'=>'comment','rows' => 2, 'cols' => 40]) !!}
                    </div>
                     <div class="form-group">
                    <button type="submit"  class="btn btn-success" onclick="CommentSave()" >Save</button>
                    </div>
                    <!-- {{ Form::close() }} -->
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

<script src="{{asset('public/js/jquery.table2excel.js')}}"></script>

<script>
$(".sum-table-excel").click(function(){
  $("#sum-table").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "SumTable.xlsx", //do not include extension
    fileext: ".xlsx" // file extension
  }); 
});

$(function() {
     
    window.table=$('#users-table').DataTable({

        autoWidth :false,
        scrollX:    true,
        scrollCollapse: true,
        fixedColumns: true,
        dom: 'lBfrtip',
        order: [[ 13, "desc" ]],
        buttons: [
           
            {
              extend: 'excel',
              text: 'Excel',
              className: 'exportExcel',
              filename: 'Export excel',
              exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10.12,13] //Your Colume value those you want
                           }
              
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text: 'PDF',
                className: 'exportExcel',
                filename: 'Export excel',
                 exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10.12,13] //Your Colume value those you want
                           }
              
            }, 
            {
              extend: 'print',
              text: 'Print',
              className: 'exportExcel',
              filename: 'Export excel',
              exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10.12,13] //Your Colume value those you want
                           }
               
            },  
        ],
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        scrollX: true,
        responsive: false,
        processing: true,
        oLanguage: {
                sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
                },
        serverSide: true,
        ajax: {
            url: '{!! route('revenue.datatable') !!}?user_id={{ $user_id or '' }}',
            data: function (d) {
                d.vendor_id = $('[name=vendor_id]').val();
                d.zone_id = $('[name=zone_id]').val();
                d.from_date = $('input[name=from_date]').val();
                d.to_date = $('input[name=to_date]').val();
                d.delivery_from_date = $('input[name=delivery_from_date]').val();
                d.delivery_to_date = $('input[name=delivery_to_date]').val();
                d.rev_from = $('input[name=rev_from]').val();
                d.rev_to = $('input[name=rev_to]').val();
                d.rev_perc_from = $('input[name=rev_perc_from]').val();
                d.rev_perc_to = $('input[name=rev_perc_to]').val();
                d.verience = $('[name=verience]').val();
                d.verience_revenue = $('[name=verience_revenue]').val();
                d.transaction_status = $('[name=transaction_status]').val();
               /* d.promo_code = $('[name=promo_code]').val();*/
                d.delivery_charge = $('[name=delivery_charge]').val();
                d.delivery_date = $('input[name=delivery_date]').val();
                d.admin_discount = $('[name=admin_discount]').val();
            },


         
        },
        fnDrawCallback :function(settings) {
            var api = this.api();
            //console.log('data',settings.json);
            $("#no_of_order").text(settings.json.no_of_order);
            $("#sum_sub_total").text(settings.json.sum_sub_total);
            $("#sum_total_amount").text(settings.json.sum_total_amount);
            $("#sum_admin_discount").text(settings.json.sum_admin_discount);
            $("#sum_vendor_invoice").text(settings.json.sum_vendor_invoice);
            $("#sum_varience").text(settings.json.sum_varience);
            $("#sum_varience_revenue").text(settings.json.sum_varience_revenue);
            $("#sum_delivery_charge").text(settings.json.sum_delivery_charge);
            $("#sum_vendor_revenue").text(settings.json.sum_vendor_revenue);
           /* $("#sum_promo_code").text(settings.json.sum_promo_code);*/
            $("#sum_vendor").text(settings.json.sum_vendor);
            $("#sum_total_revenue").text(settings.json.sum_total_revenue);
            $("#sum_revenue_percentage").text(settings.json.sum_revenue_percentage);
            $("#sum_vendor_commission").text(settings.json.sum_vendor_commission);
            $("#avg_revenue_percentage").text(settings.json.avg_revenue_percentage);
            $("#delivery_revenue_percentage").text(settings.json.delivery_revenue_percentage);
            $("#product_revenue_percentage").text(settings.json.product_revenue_percentage);
            /*ffffffffff*/
            
            /*gggggggggggg*/
            
            
            $('.data-toggle-coustom').bootstrapToggle();
            $('.data-toggle-coustom').change(function() {
                var product_id =$(this).attr('product-id');
                changeStatus(product_id,$(this).val());
            })
        },
       
        columns: [
            /*{ data: 'id', name: 'id' },*/
            { data: 'order_code', name: 'order_code' }, 
            { data: 'vendor', name: 'vendor' },
            { data: 'vendor_commission', name: 'vendor_commission' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'sub_total', name: 'sub_total'},
            { data: 'vendor_invoice', name: 'vendor_invoice' },
            { data: 'varience', name: 'varience' },
            { data: 'varience_revenue', name: 'varience_revenue' },
            { data: 'vendor_revenue', name: 'vendor_revenue' },
            { data: 'delivery_charge', name: 'delivery_charge' },
            { data: 'admin_discount', name: 'admin_discount' },
            /* { data: 'promo_code', name: 'promo_code' },*/
            { data: 'total_revenue', name: 'total_revenue' },
            { data: 'revenue_percentage', name: 'revenue_percentage'},
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
    $('.select2-multiple').select2({
        placeholder: "vendor",
        allowClear: true
    });
});

function deleteRow(id){
    $.ajax({
        data: {
            id:id
        },
        type: "DELETE",
        url: "{{ url('admin/vendor-commission') }}/"+id,
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
var commentModal = document.getElementById('commentModal');

// Get the button that opens the modal


// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
var cspan = document.getElementsByClassName("close")[1];

// When the user clicks on the button, open the comment modal
function editComment(order_id) {
    $("input[name=comment_order_id]").val(order_id);
     $.ajax({
        data: { order_id:order_id},
        method:'post',
        url: "{!! route('revenue.getcomment') !!}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( response ) {
                console.log(response);
                    if(response.status == 'true'){
                        $("[name=comment]").val('');
                       commentModal.style.display = "block";
                        $("input[name=comment_order_id]").val(order_id);
                        $("[name=comment]").val(response.data.comment);
                    }
                    if(response.status == 'false'){
                        commentModal.style.display = "block";
                         $("[name=comment]").val('');
                    }
                
            },
      });
}

// When the user clicks on the button, open the modal
function editOrder(order_id,sub_total,varience,vendor_revenue) {
     $.ajax({
        data: { order_id:order_id},
        method:'post',
        url: "{!! route('revenue.getdata') !!}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( response ) {
                console.log(response);
                    if(response.status == 'true'){
                        modal.style.display = "block";
                        $("input[name=order_id]").val(order_id);
                        $("input[name=subtotal]").val(sub_total);
                        $("input[name=verience]").val(varience);
                        $("input[name=vendor_revenue]").val(vendor_revenue);
                        $("input[name=vendor_invoice]").val(response.data.vendor_invoice);
                        $("input[name=verience_revenue]").val(response.data.verience_revenue);
                    }
                     if(response.status == 'false'){
                        modal.style.display = "block";
                        $("input[name=order_id]").val(order_id);
                        $("input[name=subtotal]").val(sub_total);
                        $("input[name=verience]").val(varience);
                        $("input[name=vendor_revenue]").val(vendor_revenue);
                    }
                
            },
      });
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}
cspan.onclick = function() {
  commentModal.style.display = "none";
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
  if (event.target == commentModal) {
    commentModal.style.display = "none";
  }
} 

function CommentSave(){

    var orderId =$("input[name=comment_order_id]").val();
     $.ajax({
        data: {
            comment:$("[name=comment]").val(),
            order_id:orderId
        },
        method:'post',
        url: "{!! route('revenue.comment.add') !!}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
             commentModal.style.display = "none";
            window.table.draw();
            new PNotify({
                title: 'Success',
                text: data.message,
                type: 'success',
                styling: 'bootstrap3'
            });

        },
        error: function( data ) {
             console.log(data);
            new PNotify({
                title: 'Error',
                text: data.responseJSON.message,
                type: "error",
                styling: 'bootstrap3'
            });
        }
    });
    
    
}

function OrderDetailSave(){
	var orderId =$("input[name=order_id]").val();
	 $.ajax({
        data: {
            subtotal:$("input[name=subtotal]").val(),
            vendor_invoice:$("input[name=vendor_invoice]").val(),
            verience:$("input[name=verience]").val(),
            vendor_revenue:$("input[name=vendor_revenue]").val(),
            verience_revenue:$("input[name=verience_revenue]").val(),
            order_id:orderId
        },
        method:'post',
        url: "{!! route('revenue.add') !!}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
             modal.style.display = "none";
            window.table.draw();
            new PNotify({
                title: 'Success',
                text: data.message,
                type: 'success',
                styling: 'bootstrap3'
            });

        },
        error: function( data ) {
             console.log(data);
            new PNotify({
                title: 'Error',
                text: data.responseJSON.message,
                type: "error",
                styling: 'bootstrap3'
            });
        }
    });
	
	
}









</script>

@endpush




