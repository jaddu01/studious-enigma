@extends('admin.layouts.app')
@section('title', 'Analytic Report/Product |')
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
                            <h2>Analytic Report/Product</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="panel-body">
                                <form role="form" class="form-inline" id="search-form" method="POST" autocomplete="off">
                                        <div class="form-group">
                                       
                                        <label for="from_date">Date Start </label>
                                        <input type="text" id="from_date" name="from_date" class="form-control datepicker"></div>
                                        <div class="form-group">
                                        <label for="to_date">Date End </label>
                                        <input type="text" id="to_date" name="to_date" class="form-control datepicker">
                                        </div>
                                        
                                         <div class="form-group">
                                        {!!  Form::select('zone_id', $zones,null, array('class' => 'form-control select2-multiple','placeholder'=>'Zone','id'=>'zone')) !!}
                                        </div>
                                         <div class="form-group">
                                        {!!  Form::select('vendor_id', $vandors,null, array('class' => 'form-control select2-multiple','placeholder'=>'Store','id'=>'vendor')) !!}
                                        </div>
                                         <div class="form-group">
                                        {!!  Form::select('customer_id', $customer,null, array('class' => 'form-control select2-multiple','placeholder'=>'Store','id'=>'customer')) !!}
                                        </div>
                                        
                                        
						                <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Apply filter</button>
                                        </div>
                                </form>

                            </div>
                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Total Quantity</th>
                                    <th>Orders</th>
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
       /* scrollX: true,
        responsive: true,*/
        processing: true,
        oLanguage: {
                sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
                },
        serverSide: true,
        ajax: {
            url: '{!! route('anaylitics.datatable.product') !!}?user_id={{ $user_id or '' }}',
            data: function (d) {
                d.zone_id = $('[name=zone_id]').val();
                d.vendor_id = $('[name=vendor_id]').val();
                d.customer_id = $('[name=customer_id]').val();
                d.from_date = $('input[name=from_date]').val();
                d.to_date = $('input[name=to_date]').val();
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
            { data: 'product', name: 'product'},
            { data: 'total_qty', name: 'total_qty' },
            { data: 'orders', name: 'orders'},
            
           
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
    $('#customer').select2({
        placeholder: "Customer",
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

