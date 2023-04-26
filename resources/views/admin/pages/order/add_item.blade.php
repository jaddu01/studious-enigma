@extends('admin.layouts.app')

@section('title', 'Add Product |')
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

                        <div class="x_content">
        {!! Form::open(['url' => ['admin/order/add-product',$order->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}                 
                            <div class="col-sm-3">
                            Load Zone <label>{{$order->zone->name}}</label>
                            
                            
                             
                            
                              
                 
						{{csrf_field()}}
                              {!!  Form::select('vendor_product_id', collect($venderProducts)->pluck('Product.name','id'),null, array('class' => 'form-control select2-multiple','placeholder'=>'product','id'=>'vendor_product_id','required')) !!}

                             </div>
                            <div class="col-sm-3"><label>&nbsp; </label>
                            <input type="number" name="qty" class="form-control" value="1" min="1">  
                          
                            
                            </div>
                            
                           
                      <div class="col-sm-3">
                         <label></label>
                            {!!  Form::submit('Update',array('class'=>'btn btn-primary customcolor','style'=>'margin-top: 24px')) !!}
                        </div>
                            
                            {!! Form::close() !!}       
                           </div>    
                 <div class="padd40" style="margin-top:15px; padding-bottom:0 !important"><h4>Load Product Details</h4>          
                           
                           
                            <table class="table table-striped table-bordered" id="users-table">
                                <thead  class="success">
                                <tr>
                                    <th>Product ID</th>
                                    <th>Total Price </th>
                                    <th>Price </th>
                                    <th>Is Offer</th>
                                    <th>Offer Value</th>
                                    <th>Qty</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

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
            $('#vendor_product_id').select2({
                placeholder: "Select Product",
                allowClear: true,
            });
            
            $("[name=vendor_product_id]").on('change',function () {
                var index = $(this).find(":selected").index();
                if(index > 0){
					//alert(JSON.stringify(<?php echo json_encode($venderProducts);?>))
                    var data  = <?php echo json_encode($venderProducts);?>;
                    console.log(data[index-1]);
                    var total_price =(data[index-1].offer_price)*parseInt($("[name=qty]").val());
                    $("tbody").html('<tr><th>'+data[index-1].id+'</th><th>'+total_price+'</th><th>'+data[index-1].offer_price+'</th><th>'+data[index-1].is_offer+'</th><th>'+((data[index-1].offer !==null)?data[index-1].offer.offer_value:  0)+'</th><th>'+ $("[name=qty]").val()+'</th><th><img src="'+data[index-1].product.image.name+'" height="75" width="75"></th><th>'+data[index-1].product.name+'</th></tr>');
                }else{
                     new PNotify({
                        title: 'Error',
                        text: 'Please select product',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                }
            });

            $("[name=qty]").on('change keyup',function () {
                $("#users-table >tbody").find("th").eq(1).text($(this).val()*$("#users-table >tbody").find("th").eq(2).text());
                $("#users-table >tbody").find("th").eq(5).text($(this).val());
            })
        });
    </script>
@endpush
