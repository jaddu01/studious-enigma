@extends('admin.layouts.app')

@section('title', 'Add Coin Entry')

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

                                {!! Form::open(['route' => ['wallet-management.add-coin-entry',$id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                                {{csrf_field()}}
                                <span class="section">Add Coin Entry</span>

                                @foreach(config('translatable.locales') as $locale)
                                    <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="transaction_type">Transaction Type
                                            
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('transaction_type', ['CREDIT'=>'CREDIT','DEBIT'=>'DEBIT'],null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'transaction_type')) !!}
                                            {{ Form::filedError('transaction_type') }}
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('amount') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">Amount <span
                                                    class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('amount',null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'amount')) !!}
                                            {{ Form::filedError('amount') }}
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span
                                                    class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::textarea('description',null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'description')) !!}
                                            {{ Form::filedError('description') }}
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
