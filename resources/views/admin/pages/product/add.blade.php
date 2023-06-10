@extends('admin.layouts.app')

@section('title', 'Add product |')

@section('sidebar')
    @parent
@endsection
@section('header')
    @parent
@endsection
@section('footer')
    @parent
@endsection

@push('css')
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet" />
@endpush
<style type="text/css">
    .category-checkbox-list {
        list-style: none;
    }
    .sub-category-checkbox-list {
        list-style: none;
        display: none;
    }
    .category-checkbox-list li{
        cursor: pointer;
    }
    .sub-category-checkbox-list li{
        cursor: pointer;
    }
</style>
@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">
                            {!! Form::open(['route' => 'product.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">Add Product</span>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="item form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Category <span
                                                    class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            <!-- <select name="category_id[]" multiple class="form-control col-md-7 col-xs-12">
                                                <option value="">Select</option>
                                                {{Helper::cat_list($categories,'','',array())}}
                                            </select> -->
                                            @php
                                            $cat_array = Helper::categoryArray($categories);
                                            if(isset($cat_array) && !empty($cat_array)){
                                                echo '<ul class="category-checkbox-list">';
                                                    foreach($cat_array as $key=>$value) {
                                                        echo '<li class="'.$value['slug'].' main-'.$value['slug'].'"><input name="category_id[]" type="checkbox" value="'.$value['id'].'" class=""><span class="'.$value['slug'].' main-'.$value['slug'].'" onclick="toggleCat(\''.$value['slug'].'\')"> '.$value['name'].'</span></li>';
                                                        if(isset($value['sub_category']) && !empty($value['sub_category'])){
                                                            echo '<ul class="sub-category-checkbox-list '.$value['slug'].' sub-'.$value['slug'].'">';
                                                                foreach($value['sub_category'] as $key1=>$value1) {
                                                                    echo '<li class="'.$value1['slug'].' main-'.$value1['slug'].'"><input name="category_id[]" type="checkbox" value="'.$value1['id'].'" class=""><span class="'.$value1['slug'].' main-'.$value1['slug'].'" onclick="toggleCat(\''.$value1['slug'].'\')"> '.$value1['name'].'</span></li>';
                                                                    if(isset($value1['sub_category']) && !empty($value1['sub_category'])){
                                                                        echo '<ul class="sub-category-checkbox-list '.$value1['slug'].' sub-'.$value1['slug'].'">';
                                                                            foreach($value1['sub_category'] as $key2=>$value2) {
                                                                                echo '<li class="'.$value2['slug'].' main-'.$value2['slug'].'"><input name="category_id[]" type="checkbox" value="'.$value2['id'].'" class=""><span class="'.$value2['slug'].' main-'.$value2['slug'].'"> '.$value2['name'].'</span></li>';
                                                                            }
                                                                        echo '</ul>';
                                                                    }
                                                                }
                                                            echo '</ul>';
                                                        }
                                                    }
                                                echo '</ul>';
                                            }
                                            @endphp
                                            {{ Form::filedError('category_id') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="item form-group {{ $errors->has('brand_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Brand 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::select('brand_id', $brands,null, array('placeholder' => 'Brand','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                        @if ($errors->has('brand_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('brand_id') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('gst') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">GST <span
                                                    class="required">*</span>
                                        </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::select('gst', $gst,null, array('placeholder' => 'GST','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                        @if ($errors->has('gst'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('gst') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
        
                                    <div class="item form-group {{ $errors->has('hsn_code') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hsn_code">HSN Code 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::text('hsn_code',null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                            {{ Form::filedError('hsn_code') }}
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('measurement_class') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Measurement class<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::select('measurement_class', $measurementClass,null, array('placeholder' => 'Measurement Class','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                            @if ($errors->has('measurement_class'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('measurement_class') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('measurement_value') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Measurement value<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::text('measurement_value', null, array('placeholder' => 'keywords','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                            @if ($errors->has('measurement_value'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('measurement_value') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="item form-group {{ $errors->has('returnable') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="returnable">Returnable
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            
                                            {!!  Form::select('returnable', ['0'=>'No','1'=>'Yes'],null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                            {{ Form::filedError('returnable') }}
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status
                                            
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                            {{ Form::filedError('status') }}
                                        </div>
                                    </div>
        
                                    <div class="item form-group {{ $errors->has('barcode') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barcode">Bar Code 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::text('barcode',null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                            {{ Form::filedError('barcode') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="item form-group {{ $errors->has('offer_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Offer
                                            <span class="required"></span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                             <input type="hidden" name="offer_type" id="offer_type" value="">
                                             <input type="hidden" name="offer_value" id="offer_value" value="">
                                            {!!  Form::select('offer_id', $offers,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple','placeholder'=>'Offer','empty' => false,'value'=>0,'id'=>'offer_id')) !!}
                                            {{ Form::filedError('offer_id') }}
                                        </div>
                                    </div>
        
                                     <div class="item form-group {{ $errors->has('best_price') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">MRP<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::text('best_price',null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'priceAmt')) !!}
                                            {{ Form::filedError('best_price') }}
                                        </div>
                                    </div>
        
        
                                    <div class="item form-group {{ $errors->has('price') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Best Price <span
                                                    class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::text('price',null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'priceAmt')) !!}
                                            {{ Form::filedError('price') }}
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('purchase_price') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="purchase_price">Purchase Price <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            {!!  Form::text('purchase_price',null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'priceAmt')) !!}
                                            {{ Form::filedError('purchase_price') }}
                                        </div>
                                    </div>
        
                                    <div class="item form-group{{ $errors->has('qty') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">qty
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::text('qty', null, array('placeholder' => 'qty','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                            @if ($errors->has('qty'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('qty') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('per_order') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Max. per order
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::text('per_order', null, array('placeholder' => 'max. per order','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                            @if ($errors->has('per_order'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('per_order') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="item form-group {{ $errors->has('expire_date') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expire_date">Expire Date 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        
                                            {!!  Form::date('expire_date',null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                            {{ Form::filedError('expire_date') }}
                                        </div>
                                    </div>
        
        
                                    <div class="item form-group {{ $errors->has('show_in_cart_page') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="show_in_cart_page">Show In Cart Page
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            {!!  Form::select('show_in_cart_page', ['0'=>'No','1'=>'Yes'],null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                            {{ Form::filedError('show_in_cart_page') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">

                                    @foreach(config('translatable.locales') as $locale)
                                        <div class="item form-group{{ $errors->has('name:'.$locale) ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name
                                                In {{$locale}}<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
        
                                                {!!  Form::text('name:'.$locale, null, array('placeholder' => 'Name','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                                @if ($errors->has('name:'.$locale))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('name:'.$locale) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="item form-group{{ $errors->has('print_name:'.$locale) ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="print_name">Print Name
                                                In {{$locale}}
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
        
                                                {!!  Form::text('print_name:'.$locale, null, array('placeholder' => 'Print Name','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                                @if ($errors->has('print_name:'.$locale))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('print_name:'.$locale) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="item form-group{{ $errors->has('description:'.$locale) ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Description
                                                In {{$locale}}<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
        
                                                {!!  Form::textarea('description:'.$locale, null, array('placeholder' => 'description','class' => 'form-control col-md-7 col-xs-12','rows'=>'3' )) !!}
                                                @if ($errors->has('description:'.$locale))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('description:'.$locale) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
        
                                     <div class="item form-group{{ $errors->has('disclaimer:'.$locale) ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Disclaimer
                                                In {{$locale}}<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
        
                                                {!!  Form::textarea('disclaimer:'.$locale, null, array('placeholder' => 'detail','class' => 'form-control col-md-7 col-xs-12','rows'=>'3' )) !!}
                                                @if ($errors->has('disclaimer:'.$locale))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('disclaimer:'.$locale) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
        
                                        <div class="item form-group{{ $errors->has('self_life:'.$locale) ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Shelf Life
                                                In {{$locale}}<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
        
                                                {!!  Form::textarea('self_life:'.$locale, null, array('placeholder' => 'Shelf Life detail','class' => 'form-control col-md-7 col-xs-12','rows'=>'3' )) !!}
                                                @if ($errors->has('self_life:'.$locale))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('self_life:'.$locale) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
        
                                        <div class="item form-group{{ $errors->has('manufacture_details:'.$locale) ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Manufacture Details
                                                In {{$locale}}<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
        
                                                {!!  Form::textarea('manufacture_details:'.$locale, null, array('placeholder' => 'Manufacture detail','class' => 'form-control col-md-7 col-xs-12','rows'=>'3' )) !!}
                                                @if ($errors->has('manufacture_details:'.$locale))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('manufacture_details:'.$locale) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
        
                                        <div class="item form-group{{ $errors->has('marketed_by:'.$locale) ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Marketed By
                                                In {{$locale}}<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
        
                                                {!!  Form::textarea('marketed_by:'.$locale, null, array('placeholder' => 'detail','class' => 'form-control col-md-7 col-xs-12','rows'=>'3' )) !!}
                                                @if ($errors->has('marketed_by:'.$locale))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('marketed_by:'.$locale) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
        
        
                                        <div class="item form-group{{ $errors->has('keywords:'.$locale) ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Keywords
                                                In {{$locale}}<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
        
                                                {!!  Form::text('keywords:'.$locale, null, array('placeholder' => 'keywords','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                                @if ($errors->has('keywords:'.$locale))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('keywords:'.$locale) }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
        
                                            @endforeach
                                            <div class="item form-group {{ $errors->has('related_products') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Related products
                                                  
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
        
                                                    {!!  Form::select('related_products[]', $related_products,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple','multiple'=>'true')) !!}
                                                    {{ Form::filedError('related_products') }}
                                                </div>
                                            </div>
        
                                            <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
        
                                                    <input type="file" id="image" name="image[]"
                                                           class="form-control col-md-7 col-xs-12" multiple>
                                                    @if ($errors->has('image'))
                                                        <span class="help-block">
                                                        <strong>{{ $errors->first('image') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            {{-- <button type="submit" class="btn btn-primary">Cancel</button>--}}
                                            {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{asset('public/js/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.select2-multiple').select2();
    });
    function toggleCat(className) {
        $('.sub-'+className).toggle();
    }
</script>
@endpush
