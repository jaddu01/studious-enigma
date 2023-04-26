@extends('admin.layouts.app')

@section('title', 'Add Product Variant |')

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
                            {!! Form::open(['route' => 'admin.product.savevariant','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">Add Product Variant</span>
                            <div class="item form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Product 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!!  Form::select('product_id', $products,null, array('placeholder' => 'Product Id','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                        @if ($errors->has('product_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('product_id') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>

                            <div class="item form-group {{ $errors->has('color') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="color">Color
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('color',null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('color') }}
                                </div>
                            </div>

                            <div class="item form-group {{ $errors->has('size') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="size">Size
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('size',null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('size') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('measurement') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="measurement">Measurement
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('measurement',null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('measurement') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('qty') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qty">Quantity
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('qty',null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('qty') }}
                                </div>
                            </div>

                             
                             
                             
                             
                                     

                                     
                        
                             
                                     

                                      
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
    function toggleCat(className) {
        $('.sub-'+className).toggle();
    }
</script>
@endpush
