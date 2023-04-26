@extends('admin.layouts.app')

@section('title', 'Modify Delivery Date')
@push('css')
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">

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

                            {!! Form::model($order,['url' => ['admin/order/modify-delivery-date-or-slot',$order->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <input type="hidden" name="zone_id" value="{{$order->zone->id}}">
                            <span class="section">Modify Delivery Date</span>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load Zone<span class="required">:</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="pname"><span>{{$order->zone->name}}</span></p>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Select Date<span class="required">:</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="delivery_date" autocomplete="off" name="delivery_date" class="form-control datepicker">
                                </div>
                            </div>
                            <div class="item form-group" id="workday_div" style="display: none;">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load WorkDay<span class="required">:</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="pname" id="workday"></p>
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('delivery_time_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Time Slot <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('delivery_time_id',[], null, array('placeholder' => 'delivery time','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('delivery_time_id'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('delivery_time_id') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div id="ajax-data"></div>


                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <a href="{{ url()->previous()}}" type="button" class="btn btn-primary">Cancel</a>
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

@endsection
@push('scripts')
    <script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
     <script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
   <script>
        $(function () {
            $('.datepicker' ).datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                startDate: '+1d',
                //minDate: 1,
                endDate: '+3d'
            });
/*
            $("[name=delivery_date]").on('change',function () {
                $.ajax({
                    data: {
                        delivery_date:$(this).val()
                    },
                    type: "GET",
                    url: "{{ url('admin/order/modify-delivery-date-or-slot') }}/"+{{$order->id}},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( data ) {
                       $("#ajax-data").html(data.data);

                    },
                    error: function( data ) {
                        new PNotify({
                            title: 'Error',
                            text: 'something is wrong',
                            type: "error",
                            styling: 'bootstrap3'
                        });
                    }
                });
            });*/

            $("[name=delivery_date]").on("change" , function (){
                if($(this).val()==""){
                    return false
                }
                $.ajax({
                    url: "{!! route('get-delivery-day') !!}",
                    type: 'GET',
                    data: {
                        date : $(this).val(),
                        id : $("[name=zone_id]").val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function( data ) {
                        console.log(data);

                       var delivery_time = data.data.delivery_time;
                       $("#workday").text(data.data.name);
                        $("#workday_div").show();
                        var html ;
                        for (var i in delivery_time){
                            html+="<option value='"+delivery_time[i].id+"'>"+delivery_time[i].name+"</option>";
                        }
                        $("[name=delivery_time_id]").html(html);

                    },
                    error: function( data, status, error ) {

                        new PNotify({
                            title: 'Error',
                            text: data.responseJSON.message,
                            type: "error",
                            styling: 'bootstrap3'
                        });
                    }
                });
            });

        });
   </script>
@endpush
