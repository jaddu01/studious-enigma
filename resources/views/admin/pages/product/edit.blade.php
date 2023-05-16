@extends('admin.layouts.app')

@section('title', 'Edit Product')

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
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <style type="text/css">
        .img-wrap{
          position: relative;
          float: left;
          width: 20%;
          margin-right: 10px;
        }
        .close{
          font-size: 21px;
          font-weight: 700;
          line-height: 1;
          color: #f00;
          text-shadow: 0 1px 0 #fff;
          filter: alpha (opacity=20) ;
          opacity: .8;
          position: absolute;
          right: 0;
        }
    </style>
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
                                {!! Form::model($product,['route' => ['product.update',$product->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <span class="section">Edit Product</span>

                            <div class="item form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Category <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    <!-- <select name="category_id[]" multiple class="form-control col-md-7 col-xs-12">
                                        <option value="">Select</option>
                                        {{Helper::cat_list($categories,'','',$product->category_id)}}
                                    </select> -->
                                    @php
                                    $cat_array = Helper::categoryArray($categories);
                                    if(isset($cat_array) && !empty($cat_array)){
    echo '<ul class="category-checkbox-list">';
        foreach($cat_array as $key=>$value) {
            echo '<li class="'.$value['slug'].' main-'.$value['slug'].'">';
                if(in_array($value['id'],$product->category_id)) {
                    $is_checked = 'checked';
                } else {
                    $is_checked = '';
                }
               echo  '<input name="category_id[]" type="checkbox" value="'.$value['id'].'" class="check-'.$value['slug'].'"><span class="'.$value['slug'].' main-'.$value['slug'].'" onclick="toggleCat(\''.$value['slug'].'\')" '.$is_checked.' > '.$value['name'].'</span></li>';
            if(isset($value['sub_category']) && !empty($value['sub_category'])){
                echo '<ul class="sub-category-checkbox-list '.$value['slug'].' sub-'.$value['slug'].'">';
                    foreach($value['sub_category'] as $key1=>$value1) {
                        echo '<li class="'.$value1['slug'].'">';
                            if(in_array($value1['id'],$product->category_id)) {
                                $is_checked = 'checked';
                            } else {
                                $is_checked = '';
                            }
                            echo '<input name="category_id[]" type="checkbox" value="'.$value1['id'].'" class="check-'.$value['slug'].' check-'.$value1['slug'].'" '.$is_checked.'><span class="'.$value1['slug'].' main-'.$value1['slug'].'" onclick="toggleCat(\''.$value1['slug'].'\')"> '.$value1['name'].'</span></li>';
                        if(isset($value1['sub_category']) && !empty($value1['sub_category'])){
                            echo '<ul class="sub-category-checkbox-list main-'.$value['slug'].' sub-'.$value1['slug'].'">';
                                foreach($value1['sub_category'] as $key2=>$value2) {
                                    echo '<li class="'.$value2['slug'].'">';
                                        if(in_array($value2['id'],$product->category_id)) {
                                            $is_checked = 'checked';
                                        } else {
                                            $is_checked = '';
                                        }
                                        echo '<input name="category_id[]" type="checkbox" value="'.$value2['id'].'" class="check-'.$value['slug'].' check-'.$value1['slug'].' check-'.$value2['slug'].'" '.$is_checked.'><span class="'.$value2['slug'].' main-'.$value2['slug'].'"> '.$value2['name'].'</span></li>';
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


                            <div class="item form-group {{ $errors->has('sku_code') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Sku Code <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {{ $product->sku_code}}
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

                                        {!!  Form::textarea('description:'.$locale, null, array('placeholder' => 'detail','class' => 'form-control col-md-7 col-xs-12','rows'=>'3' )) !!}
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

                                        {!!  Form::textarea('disclaimer:'.$locale, null, array('placeholder' => 'Disclaimer','class' => 'form-control col-md-7 col-xs-12','rows'=>'3' )) !!}
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

                                        {!!  Form::textarea('marketed_by:'.$locale, null, array('placeholder' => 'Marketed By detail','class' => 'form-control col-md-7 col-xs-12','rows'=>'3' )) !!}
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

                                    @foreach($product->images as $image)
                                        <div class="img-wrap">
                                            <span class="close" data-id="{{$image->id}}" onclick="deleteImage({{$image->id}},{{$product->id}})">&times;</span>
                                            <img src="{{$image->name}}" height="100" width="100"/>
                                        </div>                                        
                                    @endforeach

                                    <input type="file" id="image" name="image[]"
                                           class="form-control col-md-7 col-xs-12" multiple>
                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong> 
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('returnable') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="returnable">Returnable
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!!  Form::select('returnable', ['1'=>'Yes','0'=>'No'],$product->returnable, array('class' => 'form-control col-md-7 col-xs-12')) !!}
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
                            <div class="item form-group {{ $errors->has('expire_date') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expire_date">Bar Code 
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
                                    {!!  Form::select('show_in_cart_page', ['1'=>'Yes','0'=>'No'],$product->show_in_cart_page, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('show_in_cart_table') }}
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    {{-- <button type="submit" class="btn btn-primary">Cancel</button>--}}
                                    {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
@endsection
@push('scripts')
    <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2();
        });
        function deleteImage(imageId,productId){
            var r = confirm("Are you want to delete this image?");
            if (r == true) {
                //var object=$(data);
                //var id=object.data('id');  
                var id = imageId;  
                var productId = productId;           
                $.ajax({
                    data: {
                        id:id,
                        product_id : productId,                     
                        _method:'PATCH'
                    },
                    type: "PATCH",
                    url: "{!! route('admin.product.image') !!}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( data ) {   
                    console.log(data);              
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        $("span[data-id='" + id + "']").parent().remove();
                        //object.parent().remove();
                    },
                    error: function( data ) {
                        console.log(data);  
                        new PNotify({
                            title: 'Error',
                            text: data.responseJSON.message,
                            type: "error",
                            styling: 'bootstrap3'
                        });

                    }
                });
            }             
        }
        function toggleCat(className) {
        $('.sub-'+className).toggle();
    }
    </script>
    <script type="text/javascript">
        setTimeout(()=>{
            $('input[type="checkbox"][name="category_id[]"]:checked').map(function()
            {
                var cat_class = this.className;
                cat_class_array = cat_class.split(' ');
                cat_class_array.map((value)=>{
                    console.log(value);
                    $('.sub-'+value.replace('check-','')).css('display','block');
                    console.log(value.replace('check-',''));
                })
                console.log(cat_class_array);
                console.log($(this).attr('class'));
            });
        },200);
    </script>
@endpush