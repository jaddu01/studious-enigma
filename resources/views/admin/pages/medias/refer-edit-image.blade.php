@extends('admin.layouts.app')

@section('title', 'Edit Media (Refer Image)')
@push('css')
    <link href="{{asset('public/css/multiple-select.css')}}" rel="stylesheet">
    <style type="text/css">
        .ms-parent {
    width: 100% !important;
    }
    .ms-choice {border: none; background-color: transparent;}
    </style>
@endpush
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
                                {!! Form::model($slider,['route' => ['admin.media.refer-image.update',$slider->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <span class="section">Edit Media (Refer Image)</span>
                                 

                            @foreach(config('translatable.locales') as $locale)
                                <div class="item form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::text('title:'.$locale, null, array('placeholder' => 'Title','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image In {{$locale}}<span class="required">*</span><br>(540X720)
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <img src="{{$slider->{'image:'.$locale} }}" height="75" width="75"/>
                                        <input type="file" id="image" name="image:{{$locale}}" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                {{ Form::hidden('media_type', 'refer', array('id' => 'media_type')) }}
                               
                                <div class="item form-group {{ $errors->has('message') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="msg">Message <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::textarea('message:'.$locale, null, array('placeholder' => 'message','class' => 'form-control col-md-7 col-xs-12','id'=>'msg' )) !!}
                                        @if ($errors->has('message'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('message') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                           
                                <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],$slider->status, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                        {{ Form::filedError('status') }}
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <button type="reset" class="btn btn-primary">Reset</button>
                                        <button id="send" type="submit" class="btn btn-success">Submit</button>
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
 <script src="{{asset('public/js/multiple-select.js')}}"></script>

@endpush