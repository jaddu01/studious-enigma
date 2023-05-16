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
                            <h2>Analytic Report/Order</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="panel-body">
                                <form role="form" class="form-inline" id="search-form" method="POST" autocomplete="off">
                                        <div class="form-group">
                                        <span></span>
                                      {{--  {!!  Form::select('user_id', $users,$user_id, array('class' => 'form-control select2-multiple','placeholder'=>'Store')) !!}
                                        {!!  Form::select('category_id',$categories,null, array('class' => 'form-control ','placeholder'=>'category')) !!}
                                        {!!  Form::select('is_offer', Helper::$is_status,null, array('class' => 'form-control ','placeholder'=>'is offer')) !!}--}}
                                        <label for="from_date">Date Start </label>
                                        <input type="text" id="from_date" name="from_date" class="form-control datepicker"></div>
                                        <div class="form-group">
                                        <label for="to_date">Date End </label>
                                        <input type="text" id="to_date" name="to_date" class="form-control datepicker">
                                        </div>
                                         <div class="form-group">
                                        {!!  Form::select('order_type',['DATE'=>'DAYs','WEEK'=>'WEEKs','MONTH'=>'MONTHs','YEAR'=>'YEARs'],null, array('class' => 'form-control ','placeholder'=>'Group By')) !!}
                                        </div>
                                        
                                         <div class="form-group">
                                        {!!  Form::select('zone_id', $zones,null, array('class' => 'form-control select2-multiple','placeholder'=>'Zone','id'=>'zone')) !!}
                                        </div>
                                         <div class="form-group">
                                        {!!  Form::select('vendor_id', $vandors,null, array('class' => 'form-control select2-multiple','placeholder'=>'Store','id'=>'vendor')) !!}
                                        </div>
                                         <div class="form-group">    <?php 
                                            /*transuction status except disputed*/
                                        $Tstatus = Helper::$transaction_status;
                                        $transStatusArray = array_except(Helper::$transaction_status, array('2')); ?>                                      
                                        {!!  Form::select('transaction_status', $transStatusArray,null, array('class' => 'form-control select2-multiple','placeholder'=>'Transaction Status','id'=>'transaction_status')) !!}
                                        </div>
                                         <div class="form-group">
                                        {!!  Form::select('varience_revenue',['positive'=>'Positive','negative'=>'Negative'],null, array('class' => 'form-control ','id'=>'varience_revenue','placeholder'=>'Varience Revenue')) !!}
                                        </div>
                                         <div class="form-group">                                        
                                        {!!  Form::select('delivery_charge', Helper::$is_status,null, array('class' => 'form-control select2-multiple','placeholder'=>'Delivery Charge','id'=>'delivery_charge')) !!}
                                        </div>
                                        
						                <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Apply filter</button>
                                        </div>
                                </form>

                            </div>
                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                <tr>

                                    <th>Date Start</th>
                                    <th>Date End</th>
                                    <th>No of orders</th>
                                    <th>Sub Total</th>
                                    <th>Total Amount</th>
                                    <th>Delivery Charges</th>
                                    <th>Product Revenue</th>
                                    <th>Total Revenue</th>
                                    <th>Revenue percentage</th>
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

$(function() {

    window.table=$('#users-table').DataTable({
      dom: 'lBfrtip',
        buttons: [
           
            {
              extend: 'excel',
              text: 'Excel',
              className: 'exportExcel',
              filename: 'Export excel',
              
            },
            {
              extend: 'pdf',
              text: 'PDF',
              className: 'exportExcel',
              filename: 'Export excel',
              
            }, 
            {
              extend: 'print',
              text: 'Print',
              className: 'exportExcel',
              filename: 'Export excel',
               
            },  
        ],
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
       /* scrollX: true,*/
        responsive: false,
        processing: true,
        oLanguage: {
                sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
                },
        serverSide: true,
        ajax: {
            url: '{!! route('anaylitics.datatable') !!}?user_id={{ $user_id or '' }}',
            data: function (d) {
                d.transaction_status = $('[name=transaction_status]').val();
                d.zone_id = $('[name=zone_id]').val();
                d.order_type = $('[name=order_type]').val();
                d.vendor_id = $('[name=vendor_id]').val();
                d.shopper_id = $('[name=shopper_id]').val();
                d.driver_id = $('[name=driver_id]').val();
                d.from_date = $('input[name=from_date]').val();
                d.to_date = $('input[name=to_date]').val();
                d.delivery_charge = $('[name=delivery_charge]').val();
                d.varience_revenue = $('[name=varience_revenue]').val();
                

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
            { data: 'start_date', name: 'start_date'},
            { data: 'end_date', name: 'end_date' },
            { data: 'no_of_orders', name: 'no_of_orders'},
            { data: 'sub_total', name: 'sub_total'  },
            { data: 'total_amount', name: 'total_amount'  },
            { data: 'delivery_charge', name: 'delivery_charge' },
            { data: 'product_revenue', name: 'product_revenue' },
            { data: 'total_revenue', name: 'total_revenue' },
            { data: 'revenue_percentage', name: 'revenue_percentage' },
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
    $('#transaction_status').select2({
        placeholder: "Transaction Status",
        allowClear: true
    });
    $('#delivery_charge').select2({
        placeholder: "Delivery Charge",
        allowClear: true
    });
    $('#varience_revenue').select2({
        placeholder: "Varience Revenue",
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

    $("#zone").on("change" , function (){
        if($(this).val()=="" || $(this).val()== null){
            return false
        }
        $.ajax({
            url: "{!! route('zone-details') !!}",
            type: 'GET',
            data: {
                id : $(this).val(),
                _token: '{{ csrf_token() }}'
            },
            success: function( data ) {
                //console.log(data);
                $('#vendor').find('option').remove();
                        $('#vendor')
                            .append($("<option></option>")
                            .attr("value",data.data.vendor.id)
                            .text(data.data.vendor.name));
                $("#vendor").val(data.data.vendor.id);
                $('#vendor').select2({
                    placeholder: "Store",
                    allowClear: true
                });
              

            },
            error: function( data, status, error ) {

                new PNotify({
                    title: 'Error',
                    text: data.responseJSON.message,
                    type: "error",
                    styling: 'bootstrap3'
                });
            }
        });
     });
</script>
@endpush

