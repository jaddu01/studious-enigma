@extends('admin.layouts.app')

@section('title', 'Add product')

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

                            <span class="section">Show Post</span>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Name: </label>
                                {{$product->product->name or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Category Name: </label>
                                {{$product->product->category->name or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Sku Code: </label>
                                {{$product->product->sku_code or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >description: </label>
                                {{$product->product->description or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >keywords: </label>
                                {{$product->product->keywords or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Measurement class: </label>
                                {{$product->product->measurement_class or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Price: </label>
                                {{$product->price or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Qty: </label>
                                {{$product->qty or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Offer: </label>
                                {{$product->offer_id or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Measurement value: </label>
                                {{$product->product->measurement_value or ''}}
                                <hr>
                            </div>


                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Image: </label>
                                @foreach($product->product->images as $productImage)
                                    <img src="{{$productImage->name}}" height="75" width="75">
                                @endforeach
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Related Product: </label>
                                @foreach($related_products as $related_product)
                                    <a href="{{route('product.show',$related_product->id)}}">{{$related_product->name or ''}}</a>

                                @endforeach

                                <hr>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')


@endpush