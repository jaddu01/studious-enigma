@extends('admin.layouts.app')

@section('title', 'Add How It Works Image |')
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

                                {!! Form::open(['route' => 'how-it-works.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}
                                {{csrf_field()}}
                                <span class="section">Add How It Works Image</span>
                                

                            @foreach(config('translatable.locales') as $locale)
                                <div class="item form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Title In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::text('title:'.$locale, null, array('placeholder' => 'title','class' => 'form-control col-md-7 col-xs-12' )) !!}
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

                                        <input type="file" id="image" name="image:{{$locale}}" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                           

                          
                           
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
 <script src="{{asset('public/js/multiple-select.js')}}"></script>
  
@endpush
