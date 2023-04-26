@extends('admin.layouts.app')

@section('title', ' View Cart |')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
     <style type="text/css">
        .dataTables_length {width: 20%;}
        .dt-buttons .btn {border-radius: 0px;padding: 4px 12px;}
        .select-box{width:13%!important;border-radius: 4px;}
        .form-control{border-radius: 4px;}
        .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple {
            min-height: 34px;
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
                            <h2>View Cart</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th> 
                                        <th>Price</th> 
                                        <th>Qty</th> 
                                        <th>Image</th> 
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $id = 1; ?>
                                    @foreach($data as $datas)
                                    
                                        <tr>
                                            <td>{{$id}}</td>
                                            <?php $product = Helper::getproductDtaa($datas['vendorProduct']->product_id);?>
                                            <td>{{$product->name}}</td>
                                            <td>{{$datas['vendorProduct']->price}}</td>
                                            <td>{{$datas->qty}}</td>
                                            <td><img src="{{$datas['vendorProduct']['Product']['image']->name}}" style="width:100px;"></td>
                                            <td>{{date('d-m-Y',strtotime($datas->created_at))}}</td>
                                           
                                        </tr>
                                        <?php $id++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$data->links()}}
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
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text: 'PDF',
                className: 'exportExcel',
                filename: 'Export excel',
                exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6 ] //Your Colume value those you want
                           }
            }, 
            {
              extend: 'print',
              text: 'Print',
              className: 'exportExcel',
              filename: 'Export excel',
               exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6 ] //Your Colume value those you want
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
            url: '{!! route('customer.wallethistory.datatable') !!}?user_id={{ $user_id or '' }}',
        },
        columns: [
            { data: 'id', name: 'id'},
            { data: 'customer_id', name: 'customer_id' },
            { data: 'transaction_type', name: 'transaction_type' },
            { data: 'transaction_id', name: 'transaction_id' },
            { data: 'type', name: 'type' },
            { data: 'amount', name: 'amount' },
            { data: 'created_at', name: 'created_at' },
            { data: 'description', name: 'description' },
        ]
    });
    
});


</script>

@endpush
