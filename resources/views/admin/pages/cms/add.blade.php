@extends('admin.layouts.app')

@section('title', 'CMS |')

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

                                {!! Form::open(['route' => 'category.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                                {{csrf_field()}}
                                <span class="section">Add Category</span>

                                <div class="item form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control col-md-7 col-xs-12','id'=>'name' )) !!}
                                        {{ Form::filedError('name') }}

                                    </div>
                                </div>
                                <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::file('image', null, array('placeholder' => 'image','class' => 'form-control col-md-7 col-xs-12','id'=>'image' )) !!}
                                        {{ Form::filedError('image') }}
                                    </div>
                                </div>
                                <div class="item form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Type <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::select('type', ['organizations'=>'Organizations','individuals'=>'Individuals'],null, array('class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Type')) !!}
                                        {{ Form::filedError('type') }}
                                    </div>
                                </div>
                                <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
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

@endpush
