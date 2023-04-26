@extends('admin.layouts.app')

@section('title', 'Add Coin Setting')

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

                                {!! Form::open(['route' => 'coin-settings.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                                {{csrf_field()}}
                                <span class="section">Add Coin Setting</span>

                                @foreach(config('translatable.locales') as $locale)
                                    <div class="item form-group{{ $errors->has('from_amount') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="from_amount">From Amount<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('from_amount', null, array('placeholder' => 'From Amount','class' => 'form-control col-md-7 col-xs-12') ) !!}
                                            @if ($errors->has('from_amount'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('from_amount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('to_amount') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_amount">To Amount<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('to_amount', null, array('placeholder' => 'To Amount','class' => 'form-control col-md-7 col-xs-12') ) !!}
                                            @if ($errors->has('to_amount'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('to_amount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('coin') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coin">Coin<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('coin', null, array('placeholder' => 'Coin','class' => 'form-control col-md-7 col-xs-12') ) !!}
                                            @if ($errors->has('coin'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('coin') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
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
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
   {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
<!-- FastClick -->
<script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('public/assets/nprogress/nprogress.js')}}"></script>
{{--<!-- validator -->
<script src="{{asset('public/assets/validator/validator.min.js')}}"></--}}script>



@endpush
