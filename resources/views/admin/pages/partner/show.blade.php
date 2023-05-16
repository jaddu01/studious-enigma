@extends('admin.layouts.app')

@section('title', 'Show Product')

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

                            <span class="section">Show Product</span>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Name: </label>
                                {{$product->name or ''}}
                                <hr style="clear: both;">
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Category Name: </label>
                                @if(isset($product->category))
                                @foreach($product->category->whereIn('id',$product->category_id)->get() as $category)
                                {{$category->name or ''}},
                                @endforeach
                                @else
                                {{ ''}}
                                @endif
                                <hr style="clear: both;">
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Sku Code: </label>
                                {{$product->sku_code or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >description: </label>
                                {{$product->description or ''}}
                                <hr style="clear: both;">
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >keywords: </label>
                                {{$product->keywords or ''}}
                                <hr style="clear: both;">
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Measurement class: </label>
                                {{$product->MeasurementClass->name or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Measurement value: </label>
                                {{$product->measurement_value or ''}}
                                <hr style="clear: both;">
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Price </label>
                                {{$product->price or ''}}
                                <hr style="clear: both;">
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Max. per order </label>
                                {{$product->per_order or '0'}}
                                <hr style="clear: both;">
                            </div>

                            <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Currency: </label>
                                {{$currency}}
                                  <hr style="clear: both;">
                            </div>


                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Image: </label>
                                @foreach($product->images as $productImage)
                                    <img src="{{$productImage->name}}" height="75" width="75">
                                @endforeach
                                <hr style="clear: both;">
                            </div>

                            <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Related Product: </label>
                                @foreach($related_products as $related_product)
                                    <a href="{{route('product.show',$related_product->id)}}">{{$related_product->name or ''}}</a>

                                @endforeach

                                <hr style="clear: both;">
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
