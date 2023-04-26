@extends('admin.layouts.app')

@section('title', 'Add Delivery Time')

@push('css')
    <link href="{{asset('public/css/timepicki.css')}}" rel="stylesheet">
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
                                {!! Form::model($deliveryTime,['route' => ['delivery-time.update',$deliveryTime->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <span class="section">Edit Delivery Time</span>
                            <div class="item form-group {{ $errors->has('delivery_day_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Delivery Day  <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('delivery_day_id',$deliveryDay,null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('delivery_day_id') }}
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('from_time') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From Time <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('from_time', null, array('placeholder' => 'from time','class' => 'form-control timepicker1 col-md-7 col-xs-12','id'=>'from_time' )) !!}
                                    @if ($errors->has('from_time'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('from_time') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="item form-group{{ $errors->has('to_time') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">To Time <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('to_time', null, array('placeholder' => 'to time','class' => 'form-control timepicker1 col-md-7 col-xs-12','id'=>'to_time' )) !!}
                                    @if ($errors->has('to_time'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('to_time') }}</strong>
                                        </span>
                                    @endif
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
        <script src="{{asset('public/js/timepicki.js')}}"></script>
        <script>
            $('.timepicker1').timepicki();
             function minFromMidnight(tm) {
                var ampm = tm.substr(-2);
                var clk;
                if (tm.length <= 6) {
                    clk = tm.substr(0, 4);
                } else {
                    clk = tm.substr(0, 5);
                }
                var m = parseInt(clk.match(/\d+$/)[0], 10);
                var h = parseInt(clk.match(/^\d+/)[0], 10);
                //h += (ampm.match(/pm/i)) ? 12 : 0;
                if(h!=12){  h+=(ampm.match(/pm/i)) ? 12 : 0;  }
                else{ h=(ampm.match(/pm/i)) ? 12 : 0; } 
                return h * 60 + m;
        }
          $("form").submit(function () {
            
            var startTime = $("#from_time").val();
            var endTime = $("#to_time").val();
           
            var st = minFromMidnight(startTime);
            var et = minFromMidnight(endTime);
            if (st >= et) {
                alert('To time must be greater than from time.');
                return false;
            }
           //return true;
           
        });
       
        </script>
    @endpush