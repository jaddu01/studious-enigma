@extends('admin.layouts.app')

@section('title', 'Add Slot Group |')
@push('css')
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
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

                                {!! Form::open(['route' => 'slot-group.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                                {{csrf_field()}}
                                <span class="section">Add Slot Group</span>
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

                            <div class="item form-group {{ $errors->has('slot_ids') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Slot Time
                                    <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('slot_ids[]', $slotTime,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple','multiple'=>'multiple')) !!}
                                    {{ Form::filedError('slot_ids') }}
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
        $(document).ready(function () {
            $('.select2-multiple').select2({
                placeholder: "Slot Time",
                allowClear: true
            });
        });
    </script>
@endpush