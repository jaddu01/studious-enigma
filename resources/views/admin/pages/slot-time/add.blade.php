@extends('admin.layouts.app')

@section('title', 'Add Slot Time |')
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

                                {!! Form::open(['route' => 'slot-time.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','id'=>'slot_time_form']) !!}

                                {{csrf_field()}}
                                <span class="section">Add Slot Time</span>

                           
                            <div class="item form-group{{ $errors->has('from_time') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From Time <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('from_time', null, array('placeholder' => 'From Time','class' => 'form-control timepicker1 col-md-7 col-xs-12','id' =>'from_time')) !!}
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

                                    {!!  Form::text('to_time', null, array('placeholder' => 'To Time','class' => 'form-control timepicker1 col-md-7 col-xs-12','id' =>'to_time' )) !!}
                                    @if ($errors->has('to_time'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('to_time') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('lock_time') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Lock Time  <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('lock_time', null, array('placeholder' => 'Lock Time Hour','class' => 'form-control timepickerhour col-md-7 col-xs-12','id'=>'lock_time' )) !!}
                                    @if ($errors->has('lock_time'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('lock_time') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="item form-group{{ $errors->has('total_order') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Total Order <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('total_order', null, array('placeholder' => 'Total Order','class' => 'form-control  col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('total_order'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('total_order') }}</strong>
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
    <script src="{{asset('public/js/timepicki.js')}}"></script>

    <script>
        $('.timepicker1').timepicki();
        $('.timepickerhour').timepicki();
       /* $('.timepickerhour').timepicki({
            show_meridian:false,
            min_hour_value:0,
            max_hour_value:23,
            step_size_minutes:15,
            overflow_minutes:true,
            increase_direction:'up',
            disable_keyboard_mobile: true});*/


        $("form").submit(function () {
            var startTime = $("#from_time").val();
            var endTime = $("#to_time").val();
            var lockTime = $("input[name=lock_time]").val();
            var st = minFromMidnight(startTime);
            var et = minFromMidnight(endTime);
            //var ltam = hours_am_pm(lockTime);
            var time = lockTime;
            var timeArray = time.split(" ");
            timeArray = timeArray[0].split(":");
            //alert(parseInt(timeArray[0])*60+parseInt(timeArray[1]));
            var lt = parseInt(timeArray[0])*60+parseInt(timeArray[1]);
           if (lt > st-30){
                alert('Lock time must be atleast 30 min less than from time.');
                return false;
            }alert(st); alert(et);
             if (st >= et) {
                alert('To time must be greater than from time.');
                return false;
            }
           
        });
         

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
               // h += (ampm.match(/pm/i)) ? 12 : 0;
               if(h!=12){  h+=(ampm.match(/pm/i)) ? 12 : 0;  }
                else{ h=(ampm.match(/pm/i)) ? 12 : 0; }  
             
                 return h * 60 + m;
        }
</script>
@endpush