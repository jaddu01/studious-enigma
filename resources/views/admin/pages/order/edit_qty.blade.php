@extends('admin.layouts.app')

@section('title', 'Add offer |')

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

                        <div class="x_content editpage-part">

                            {!! Form::open(['url' => ['admin/order/edit-qty',$ProductOrderItem->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                            {{csrf_field()}}
                            <div class="col-sm-12">
                            
                           <h3 class="pname"> Load Product :  <span>{{isset($ProductOrderItem->newVendorProduct->product->name)? $ProductOrderItem->newVendorProduct->product->name : ''}}</span></h3>
                            </div>
                            
                           <div class="col-sm-4"> <div class="col-sm-3"><label>Quantity:</label></div>
                            <div class="col-sm-5">
                            <input type="number" name="qty" class="form-control" value="{{$ProductOrderItem->qty}}" min="1"></div>
                             <div class="col-sm-3">
                       <input type="submit" value="Update" class="btn btn-primary"> 
                       </div>
                       </div>
                         <h4 class="heading"> Load Product Destails</h4>
                             
                            {!! Form::close() !!}
                       
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
                                    <tr>
                                        <th>{{$ProductOrderItem->id}}</th>
                                        <th>{{$ProductOrderItem->price}}</th>
                                        <th>{{$ProductOrderItem->price/$ProductOrderItem->qty}}</th>
                                        <th>{{$ProductOrderItem->is_offer}}</th>
                                        <th>{{$ProductOrderItem->offer_value}}</th>
                                        <th>{{$ProductOrderItem->qty}}</th>
                                        <th><img src="{{isset($ProductOrderItem->newVendorProduct->product->image->name)? $ProductOrderItem->newVendorProduct->product->image->name : ''}}" height='70' /></th>
                                        <th>{{ $ProductOrderItem->newVendorProduct->product->name}}</th>

                                    </tr>
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
    <script>
        $(function () {
            $("[name=qty]").on('keyup change',function () {
                $("#users-table >tbody").find("th").eq(1).text($(this).val()*$("#users-table >tbody").find("th").eq(2).text());
                $("#users-table >tbody").find("th").eq(5).text($(this).val());
            })
        })
    </script>
@endpush

