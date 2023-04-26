@extends('admin.layouts.app')

@section('title', 'Show Recipe')

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

                            <span class="section">Show Recipe</span>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Dish Name: </label>
                                {{$recipe->name or ''}}
                                <hr style="clear: both;">
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Recipe: </label>
                                {{$recipe->description or ''}}
                                <hr style="clear: both;">
                            </div>

                              <div class="item form-group" style="clear: both;">
                                <label class="control-label col-md-2 " >Ingredients: </label>
                                @foreach($related_products as $key=>$related_product)
                                {{ ++$key.')' }}
                                    <a href="{{route('product.show',$related_product->id)}}">{{ $related_product->name or ''}} </a>
                                         @if(count($related_products)==$key) {{''}} @else{{','}} 
                                        @endif
                                @endforeach

                                <hr style="clear: both;">
                            </div>

                           
                           <!--  <div class="item form-group">
                                <label class="control-label col-md-2 " >Unit of Measure: </label>
                                {{$recipe->MeasurementClass->name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Qty: </label>
                                {{$recipe->measurement_value or ''}}
                                <hr style="clear: both;">
                            </div>-->

                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Image: </label>
                                @foreach($recipe->images as $recipeImage)
                                    <img src="{{$recipeImage->name}}" height="75" width="75">
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
