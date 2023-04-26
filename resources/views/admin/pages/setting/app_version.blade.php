@extends('admin.layouts.app')

@section('title', 'App Version- Android')

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


                            {!! Form::close() !!}

                            {!! Form::model($setting,['url' => 'admin/setting/app_version','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">App Version- Android</span>
                            <?php 
                            if(!$setting->ios_mandatory_update){$setting->ios_mandatory_update = 0;}
                            if(!$setting->ios_main_tenance_mode){$setting->ios_main_tenance_mode = 0;}
                            if(!$setting->shopper_android_mandatory_update){$setting->shopper_android_mandatory_update = 0;}
                            if(!$setting->shopper_android_main_tenance_mode){$setting->shopper_android_main_tenance_mode = 0;}
                            if(!$setting->driver_android_mandatory_update){$setting->driver_android_mandatory_update = 0;}
                            if(!$setting->driver_android_main_tenance_mode){$setting->driver_android_main_tenance_mode = 0;}
                            if(!$setting->customer_android_mandatory_update){$setting->customer_android_mandatory_update = 0;}
                            if(!$setting->customer_android_main_tenance_mode){$setting->customer_android_main_tenance_mode = 0;}

                            ?>
							<h4>Customer Current Version [Android]*:</h4>
                            <div class="item form-group{{ $errors->has('android_current_version') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Current Version <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('customer_android_current_version', null, array('placeholder' => 'Android Current Version','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('customer_android_current_version'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('customer_android_current_version') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('android_mandatory_update') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Mandatory Update <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                   <input name="customer_android_mandatory_update" type="checkbox" value="{{$setting->customer_android_mandatory_update}}" class="expand" >
                                    @if ($errors->has('customer_android_mandatory_update'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('customer_android_mandatory_update') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('android_main_tenance_mode') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Maintenance Mode <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    <input name="customer_android_main_tenance_mode" type="checkbox" value="{{$setting->customer_android_main_tenance_mode}}" class="expand" >
                                    @if ($errors->has('customer_android_main_tenance_mode'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('customer_android_main_tenance_mode') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <!-- Driver sec -->
                            <h4>Driver Current Version [Android]*:</h4>
                            <div class="item form-group{{ $errors->has('android_current_version') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Current Version <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('driver_android_current_version', null, array('placeholder' => 'Android Current Version','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('driver_android_current_version'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('driver_android_current_version') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('android_mandatory_update') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Mandatory Update <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                   <input name="driver_android_mandatory_update" type="checkbox" value="{{$setting->driver_android_mandatory_update}}" class="expand" >
                                    @if ($errors->has('driver_android_mandatory_update'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('driver_android_mandatory_update') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('android_main_tenance_mode') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Maintenance Mode <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                     <input name="driver_android_main_tenance_mode" type="checkbox" value="{{$setting->driver_android_main_tenance_mode}}" class="expand" >
                                    @if ($errors->has('driver_android_main_tenance_mode'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('driver_android_main_tenance_mode') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                              <!-- Shopper sec -->
                            <h4>Shopper Current Version [Android]*:</h4>
                            <div class="item form-group{{ $errors->has('android_current_version') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Current Version <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('shopper_android_current_version', null, array('placeholder' => 'Android Current Version','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('shopper_android_current_version'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('shopper_android_current_version') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('android_mandatory_update') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Mandatory Update <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                     <input name="shopper_android_mandatory_update" type="checkbox" value="{{$setting->shopper_android_mandatory_update}}" class="expand" >
                                    @if ($errors->has('shopper_android_mandatory_update'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('shopper_android_mandatory_update') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('android_main_tenance_mode') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Android Maintenance Mode <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                     <input name="shopper_android_main_tenance_mode" type="checkbox" value="{{$setting->shopper_android_main_tenance_mode}}" class="expand" >
                                   
                                    @if ($errors->has('shopper_android_main_tenance_mode'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('shopper_android_main_tenance_mode') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <h4>Current Version [IOS]*:</h4>
                            <div class="item form-group{{ $errors->has('ios_current_version') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Ios Current Version <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('ios_current_version', null, array('placeholder' => 'Ios Current Version','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('ios_current_version'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('ios_current_version') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('ios_mandatory_update') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Ios Mandatory Update <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="ios_mandatory_update" type="checkbox" value="{{$setting->ios_mandatory_update}}" class="expand" >
                                    
                                    @if ($errors->has('ios_mandatory_update'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('ios_mandatory_update') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('ios_main_tenance_mode') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Ios Maintenance Mode <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input name="ios_main_tenance_mode" type="checkbox" value="{{$setting->ios_main_tenance_mode}}" class="expand" >

                                    @if ($errors->has('ios_main_tenance_mode'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('ios_main_tenance_mode') }}</strong>
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
    <script src="{{asset('public/js/bootstrap.min.js')}}"></script>
    <script>
        $(".expand").on("click", function () {
         if($(this).val()==0)
            {
            $(this).val(1);
            }
            else
            {
             $(this).val(0);
        }
    });
        $('.expand').each(function(e){
            if($(this).val() == 1){
                $(this).attr("checked", "checked");
            }
});
        
    </script>
@endpush
