@extends('admin.layouts.app')

@section('title',$title)
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
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
                            <div class="panel-body">
                                <form role="form" class="form-inline" id="search-form" method="POST" autocomplete="off">
                                    <div class="form-group">
                                        <label for="from_date">Date </label>
                                        <input type="text" id="sale_date" name="sale_date" class="form-control datepicker">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="button" id="getData">Apply filter</button>
                                    </div>
                                </form>

                            </div>
                            <div id="data-table">
                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody"></tbody>
                                <tfoot id="tfoot">
                                    <tr>
                                        <th>Total Amount</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="button" id="printReport">Print Report</button>
                                </div>
                            </div>
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
    <!-- Datatables -->
    <script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
    <script>

        $(function() {
            $('#sale_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm',
                viewMode: "months",
                minViewMode: "months"
            });
            getSalesReportData();
            $('#getData').click(function(){
                getSalesReportData($('#sale_date').val());
            })

            $('#printReport').click(function(){
                var divName = 'data-table';
                var printContents = document.getElementById(divName).innerHTML;
                 var originalContents = document.body.innerHTML;

                 document.body.innerHTML = printContents;

                 window.print();

                 document.body.innerHTML = originalContents;
            })
        });

        function getSalesReportData(date=null) {
            var html='';
            $('#tbody').html("<tr><td colspan='2' class='text-center'><img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'></td></tr>");
            $('#tfoot').html('<tr><th>Total Amount</th>]<th>0</th></tr>');
            $.ajax({
                data:{date:date},
                type:"POST",
                url: "{!! route('reports.sales.data') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( data ) {
                    if(data.total.total_amount>0){
                        data.data.forEach((value)=>{
                            html+='<tr>';
                            html+='<td>'+value.date+'</td>';
                            html+='<td>'+value.total_amount+'</td>';
                            html+='</tr>';
                        });
                        html.replace('undefined','');
                        $('#tfoot').html('<tr><th>Total Amount</th>]<th>'+data.total.total_amount+'</th></tr>');

                    }
                    $('#tbody').html(html);
                }
            });
        }
    </script>
@endpush