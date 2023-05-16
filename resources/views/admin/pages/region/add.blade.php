@extends('admin.layouts.app')

@section('title', 'Add Region |')

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

                                {!! Form::open(['route' => 'region.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}

                                {{csrf_field()}}
                                <span class="section">Add Region</span>
                            <div class="item form-group {{ $errors->has('city_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">City <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('city_id',$cities,null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('city_id') }}
                                </div>
                            </div>
                            @foreach(config('translatable.locales') as $locale)
                                <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::text('name:'.$locale, null, array('placeholder' => 'Name','class' => 'form-control col-md-7 col-xs-12',  'dir'=>($locale=="ar" ? 'rtl':'ltr') )) !!}
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
