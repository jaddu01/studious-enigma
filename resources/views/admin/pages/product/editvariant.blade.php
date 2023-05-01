@extends('admin.layouts.app')

@section('title', 'Edit Product Variant |')

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
                            
                            <form class="form-horizontal form-label-left validation" method="post" action="{{route('admin.product.updatevariant',$variant->id)}}">
                            {{csrf_field()}}
                            <span class="section">Edit Product Variant</span>
                            <div class="item form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product">Product
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {{$variant->name}}
                                </div>
                            </div>

                            <div class="item form-group {{ $errors->has('color') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="color">Color
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('color',$variant->color, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('color') }}
                                </div>
                            </div>

                            <div class="item form-group {{ $errors->has('size') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="size">Size
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('size',$variant->size, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('size') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('measurement') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="measurement">Measurement
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('measurement',$variant->measurement, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('measurement') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('qty') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qty">Quantity
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('qty',$variant->qty, array('class' => 'form-control col-md-7 col-xs-12')) !!}
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

    <!-- /page content -->
@endsection 
