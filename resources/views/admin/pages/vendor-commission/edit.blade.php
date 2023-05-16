@extends('admin.layouts.app')

@section('title', 'Edit Commission')

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
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet" />
     <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
@endpush

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">
                                {!! Form::model($commission,['route' => ['vendor-commission.update',$commission->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','autocomplete'=>'off','id'=>'vendor_commision_form']) !!}
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <span class="section">Edit commission</span>

                            <div class="item form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Store
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('vendor_id', $users,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('vendor_id') }}
                                </div>
                            </div>

                       

    <div class="item form-group{{ $errors->has('percent') ? ' has-error' : '' }}">
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
                                 <!--    {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!} -->
                                   <button id="send" onclick="checkValue()" type="button" class="btn btn-success">Submit</button>
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
    <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2({ placeholder: "Offer", allowClear: true});
        });
        $('[name=percent]').bind('keyup paste', function(){
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        $("#vendor_commision_form").submit(function(){
            var percentVal = parseInt($("[name=percent]").val());
            if(percentVal > 100){
                    new PNotify({
                        title: 'Error',
                        text: 'Percent can not be greater than 100',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                 return false;

            }
            
        });
        function checkValue(){
          var percentVal = parseInt($("[name=percent]").val());
            if(percentVal > 100){
                    new PNotify({
                        title: 'Error',
                        text: 'Percent can not be greater than 100',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                 return false;

            }else{
                    $("#vendor_commision_form").submit();
            }
        }

    </script>
@endpush
