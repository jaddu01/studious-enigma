@extends('admin.layouts.app')

@section('title', 'Edit Week Package |')
@push('css')

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

                            {!! Form::model($weekPackage,['route' => ['week-package.update',$weekPackage->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{method_field('put')}}

                            {{csrf_field()}}
                            <span class="section">Edit Week Package</span>
                            @foreach(config('translatable.locales') as $locale)
                                <div class="item form-group{{ $errors->has('name:'.$locale) ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::text('name:'.$locale, null, array('placeholder' => 'Name','class' => 'form-control col-md-7 col-xs-12',  'dir'=>($locale=="ar" ? 'rtl':'ltr') )) !!}
                                        @if ($errors->has('name:'.$locale))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name:'.$locale) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            @endforeach

                            <div class="item form-group {{ $errors->has('saturday_slot_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Saturday
                                    <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('saturday_slot_id', $slotGroup,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('saturday_slot_id') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('sunday_slot_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Sunday
                                    <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('sunday_slot_id', $slotGroup,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('sunday_slot_id') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('monday_slot_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Monday
                                    <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('monday_slot_id', $slotGroup,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('monday_slot_id') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('tuesday_slot_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Tuesday
                                    <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('tuesday_slot_id', $slotGroup,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('tuesday_slot_id') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('wednesday_slot_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Wednesday
                                    <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('wednesday_slot_id', $slotGroup,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('wednesday_slot_id') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('thursday_slot_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Thursday
                                    <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('thursday_slot_id', $slotGroup,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('thursday_slot_id') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('friday_slot_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Friday
                                    <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('friday_slot_id', $slotGroup,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('friday_slot_id') }}
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