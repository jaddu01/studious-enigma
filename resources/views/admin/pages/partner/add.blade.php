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
                            <div class="item form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Category <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    <select name="category_id[]" multiple class="form-control col-md-7 col-xs-12">
                                        <option value="">Select</option>
                                        {{Helper::cat_list($categories,'','',array())}}
                                    </select>
                                    {{ Form::filedError('category_id') }}
                                </div>
                            </div>


                            <div class="item form-group {{ $errors->has('sku_code') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Sku Code <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('sku_code',null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('sku_code') }}
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
                         <div class="item form-group {{ $errors->has('price') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Price </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('price',null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'priceAmt')) !!}
                                    {{ Form::filedError('price') }}
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
                                    <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status
                                            
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12"> {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                            {{ Form::filedError('status') }}
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

    {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{asset('public/js/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.select2-multiple').select2();
    });
</script>
@endpush
