@extends('admin.layouts.app')

@section('title', 'Modify Order Address')

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
                        
                            {!! Form::model($order,['url' => ['admin/order/modify-address',$order->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                            {{csrf_field()}}
                            <span class="section">Modify Order Address</span>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load Coustomer<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="pname"><span>{{$order->user->full_name}}</span></p>
                                </div>
                            </div>

                            <div class="item form-group {{ $errors->has('shipping_location') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Select Address<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!!  Form::select('shipping_location', $deliveryLocationArray,$order->shipping_location->id, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                    {{ Form::filedError('shipping_location') }}
                                </div>
                            </div>

                            <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Address Name<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('name', $order->shipping_location->name, array('id'=>'name', 'placeholder' => 'Address name','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('address_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Address Location<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('address_name', $order->shipping_location->name, array('id'=>'address_name', 'placeholder' => 'Address name','class' => 'form-control col-md-7 col-xs-12'  , 'onFocus'=>"geolocate()" )) !!}
                                    @if ($errors->has('address_name'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('address_name') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                             <div id="us3" style="width: 550px; height: 300px;"></div>
                              <div class="clearfix">&nbsp;</div>
                          </div>
                            </div>
                             <div class="item form-group{{ $errors->has('lat') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">latitute <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('lat',  $order->shipping_location->lat, array('id'=>'lat','placeholder' => 'latitute','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('lat'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('lat') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('lng') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Longitute <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('lng', $order->shipping_location->lng, array('id'=>'long','placeholder' => 'lng','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('lng'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('lng') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div class="item form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                                    Description <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::textarea('address', $order->shipping_location->address, array('placeholder' => 'address','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="button" class="btn btn-primary">Cancel</button>
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
   {{-- {!! $validator !!}--}}
@endsection
@push('scripts')
<!-- <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places&callback=initAutocomplete"
        async defer></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&v=weekly&libraries=places"></script>
    <script type="text/javascript" src="{{ asset('public/js/locationpicker.jquery.min.js')}}"></script>
    <script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
         
   <script>
 
       $(function () {
      
        var placeSearch, autocomplete;
            var componentForm = {
              street_number: 'short_name',
              route: 'long_name',
              locality: 'long_name',
              administrative_area_level_1: 'short_name',
              country: 'long_name',
              postal_code: 'short_name'
            };
        });
       function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.

        autocomplete = new google.maps.places.Autocomplete((document.getElementById('address_name')));

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

          $("[name=shipping_location]").on("change" , function () {
              var pos = $(this).find(":selected").index();
              //alert($(this).val());
              if($(this).val()!= 0){
                var data = JSON.parse('<?php echo json_encode($order->user->deliveryLocation) ?>');
                console.log(data);
                  $("[name=name]").val( data[pos].name);
                  $("[name=lat]").val( data[pos].lat);
                  $("[name=lng]").val( data[pos].lng);
                  $("[name=address]").val( data[pos].address);
                  $("[name=address_name]").val( data[pos].address);
                  $('#us3').locationpicker({
                      location: {
                          latitude: data[pos].lat,
                          longitude:  data[pos].lng
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
              }else{
                //alert('hi');
                 $("[name=name]").val('');
                  $("[name=lat]").val('');
                  $("[name=lng]").val('');
                  $("[name=address]").val('');
                   $("[name=address_name]").val('');
                  
                   $('#us3').locationpicker({
                      location: {
                          latitude: 20.593683,
                          longitude: 78.962883
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
              }

             
          });
          $('#us3').locationpicker({
            location: {
                latitude: <?php echo $order->shipping_location->lat?>,
                longitude: <?php echo $order->shipping_location->lng?>
            },
            radius: 300,
            inputBinding: {
                latitudeInput: $('#lat'),
                longitudeInput: $('#long'),
              
                locationNameInput: $('#address_name')
            },
            enableAutocomplete: true,
            onchanged: function (currentLocation, radius, isMarkerDropped) {
              
            }
        });
        
      
      
      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }

        
    </script>
@endpush
