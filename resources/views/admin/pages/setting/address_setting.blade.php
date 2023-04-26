@extends('admin.layouts.app')

@section('title', 'Address Setting')

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

                            {!! Form::model($setting,['url' => 'admin/setting/address_setting','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">Address Setting</span>

                            <div class="item form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Address Name<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('address_name', null, array('id'=>'address_name', 'placeholder' => 'Address name','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('address_name'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('address_name') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                             <div id="us3" style="width: 550px; height: 300px;"></div>
                              <div class="clearfix">&nbsp;</div>
                          </div>
                            </div>
                             <div class="item form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">latitute <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('lat', null, array('id'=>'lat','placeholder' => 'latitute','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('lat'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('lat') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Longitute <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('long', null, array('id'=>'long','placeholder' => 'long','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('long'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('long') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> 
                                Description <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::textarea('description', null, array('placeholder' => 'description','id'=>'description','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('description') }}</strong>
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
<script src="{{ asset('public/ckeditor/ckeditor.js')}}"></script>
   <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&v=weekly&libraries=places"
           ></script>
   <script type="text/javascript" src="{{ asset('public/js/locationpicker.jquery.min.js')}}"></script>
    <script>
        CKEDITOR.replace( 'description');
    </script>
     <!-- <div class="clearfix"></div> -->
        <script>
            $('#us3').locationpicker({
                location: {
                    latitude: <?php echo $setting->lat; ?>,
                    longitude: <?php echo $setting->long; ?>
                },
                radius: 300,
                inputBinding: {
                    latitudeInput: $('#lat'),
                    longitudeInput: $('#long'),
                    //radiusInput: $('#us3-radius'),
                    locationNameInput: $('#address_name')
                },
                enableAutocomplete: true,
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    // Uncomment line below to show alert on each Location Changed event
                    //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                }
            });
        </script>
@endpush
