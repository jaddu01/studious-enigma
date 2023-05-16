@extends('admin.layouts.app')

@section('title', 'Add Vendor Commission |')

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
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
@endpush

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                            {!! Form::open(['route' => 'vendor-commission.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                            {{csrf_field()}}
                            <span class="section">Add Vendor Commission</span>

                            <div class="item form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Vendor
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('vendor_id', $users,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('vendor_id') }}
                                </div>
                            </div>

                   

                            <div class="item form-group{{ $errors->has('qty') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Percentage
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('percent', null, array('placeholder' => 'Percentage','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('percent'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('percent') }}</strong>
                                            </span>
                                    @endif
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
                placeholder: "No Offer",
                allowClear: true
            });
        });
    </script>
@endpush
