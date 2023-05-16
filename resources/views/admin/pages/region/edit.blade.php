@extends('admin.layouts.app')

@section('title', 'Edit Region')

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
                                {!! Form::model($region,['route' => ['region.update',$region->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <span class="section">Edit Region</span>
                            <div class="item form-group {{ $errors->has('city_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Country <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('city_id', $cities,null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('city_id') }}
                                </div>
                            </div>
                            @foreach(config('translatable.locales') as $locale)
                                <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::text('name:'.$locale, null, array('placeholder' => 'Name','class' => 'form-control col-md-7 col-xs-12' ,  'dir'=>($locale=="ar" ? 'rtl':'ltr'))) !!}
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @endforeach

                            <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Lat <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('lat', null, array('placeholder' => 'Lat','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('lat'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('lat') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="item form-group{{ $errors->has('lng') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Lng <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('lng', null, array('placeholder' => 'lng','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('lng'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('lng') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                                <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],$region->status, array('class' => 'form-control col-md-7 col-xs-12')) !!}
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


@endpush