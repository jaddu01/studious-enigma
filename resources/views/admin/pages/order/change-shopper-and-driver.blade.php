@extends('admin.layouts.app')

@section('title', 'Change shopper and driver')
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

                            {!! Form::model($order,['url' => ['admin/order/change-shopper-and-driver',$order->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">Change shopper and driver</span>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Select Shopper<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!!  Form::select('shopper_id', $shoper,null, array('class' => 'form-control select2-multiple','placeholder'=>'Shopper','id'=>'shopper')) !!}
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Select Driver<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!!  Form::select('driver_id',$driver,null, array('class' => 'form-control select2-multiple','placeholder'=>'Driver','id'=>'driver')) !!}
                                </div>
                            </div>



                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                   <!--  <a href="{{ url()->previous()}}" type="button" class="btn btn-primary">Cancel</a> -->
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

@endpush
