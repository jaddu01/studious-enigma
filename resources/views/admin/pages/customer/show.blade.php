@extends('admin.layouts.app')

@section('title', ' Customer Details |')
@push('css')
<style type="text/css">
    /* #locationField, #controls {
        position: relative;
        width: 480px;
      }
      #address_name {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 99%;
      }*/
      hr {clear: both;}
    .pac-container {  
                z-index: 10000;
    }
</style>
    <link href="{{asset('css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
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
                      
                            <span class="section"> Customer Details </span>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >Name : </label>
                                {{$user->name}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >mobile : </label>
                                {{$user->phone_code.'-'.$user->phone_number}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >email : </label>
                                {{$user->email}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >No Of Orders : </label>
                                {{$user->totalOrder()}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Delivered Orders : </label>
                                {{$user->deliveredOrder()}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Total Amount : </label>
                                {{$user->totalAmount()}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Join At : </label>
                                {{$user->created_at}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Membership : </label>
                                @php
                                    if(isset($user->membership) && !empty($user->membership)) {
                                        $date_now = date('Y-m-d');
                                        $date2    = date('Y-m-d',strtotime($user->membership_to));
                                        if($date_now <= $date2) {
                                            echo 'Active';
                                        } else {
                                            echo 'Expired';
                                        }
                                    } else {
                                        echo '---';
                                    }
                                @endphp
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Membership ends on: </label>
                                @php
                                    if(!empty($user->membership_to)){ 
                                        echo date('d-M-Y',strtotime($user->membership_to));
                                    } else {
                                        echo "--";
                                    }
                                @endphp
                                <hr>
                            </div>

                      <table class="table table-striped table-bordered" id="users-table">
                                <thead  class="success">
                                <tr>
                                    <th>ID</th>
                                    <th>Address Name </th>
                                    <th>Address Location </th>
                                    <th>Address Description </th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->deliveryLocation as $key=>$deliveryLocation)
                                <tr>

									<th>{{$key+1}}</th>
									<th>{{$deliveryLocation->name}}</th>
									<th>{{$deliveryLocation->address}}</th>
									<th>{{$deliveryLocation->description}}</th>
									<th>
                                        <a onclick="openOrderAddressModel({{$deliveryLocation->id}})" href="javascript:void(0)" class="btn btn-success btn-xs">Edit</a>
                                        {!! Form::open(['route' => ["delivery_location.destroy",$deliveryLocation->id],'method'=>'post','class'=>'']) !!}
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}
                                        <button type="submit"  class="btn btn-danger btn-xs">Delete</button>{!! Form::close() !!}
                                    </th>

                                </tr>
                                  @endforeach
                                </tbody>
                            </table>
                            <div class="col-sm-12 actionbutton">
                                  <a href="{{url('admin/order')}}?phone_number={{$user->phone}}" class="btn btn-success ">Invoice</a>
                                <a href="{{url('admin/order')}}?phone_number={{$user->phone}}" class="btn btn-success ">Order</a>
                                <a href="javascript:void(0)" class="btn btn-success " data-toggle="modal" data-target="#selectOrderAddress">Add Address</a>
                            </div>
                            <!-- <div class="col-sm-12 text-center padd40">
                                <button type="reset" class="btn btn-default">Cancel</button>
                            </div> -->

                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--select delivery address model -->
    <div id="selectOrderAddress" class="modal fade" role="dialog" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select delivery address </h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','id'=>'modify-address-from']) !!}
                    {{csrf_field()}}
                    <input type="hidden" name="shipping_location">
                    <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <!--  <label for="locationTextField">Location</label>
                    <input id="locationTextField" type="text" size="50"> -->
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Address Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('name', null, array('id'=>'name', 'placeholder' => 'Address name','class' => 'form-control col-md-7 col-xs-12' )) !!}
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
                            <div id="locationField">
                                {!!  Form::text('address', null, array('id'=>'address_name', 'placeholder' => 'Address name','class' => 'form-control col-md-7 col-xs-12')) !!}
                                
                                @if ($errors->has('address_name'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('address_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div id="us3" style="width: 300px; height: 300px;"></div>
                            <div class="clearfix">&nbsp;</div>
                        </div>
                    </div>
                    <div class="item form-group{{ $errors->has('lat') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">latitute <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('lat',  null, array('id'=>'lat','placeholder' => 'latitute','class' => 'form-control col-md-7 col-xs-12' )) !!}
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

                            {!!  Form::text('lng', null, array('id'=>'long','placeholder' => 'lng','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('lng'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('lng') }}</strong>
                                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="item form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Description
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::textarea('description', null, array('placeholder' => 'address','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('address'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button style="margin-bottom: 5px;"  onclick="addAddress()" type="button" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
      
    </div>
@endsection
@push('scripts')

<!-- <script src="{{asset('public/js/bootstrap.min.js')}}"></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places&v=weekly"></script>

<script type="text/javascript" src="{{ asset('public/js/locationpicker.jquery.min.js')}}"></script>
<script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>


      <!-- Latest compiled and minified JavaScript -->

    <script>


        function changeStatus(id,status){
            $.ajax({
                url: "{!! route('admin.order.status') !!}",
                type: 'PATCH',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                data: {
                _method: 'PATCH',
                status : status,
                id : id,
                _token: '{{ csrf_token() }}'
                },
                success: function( data ) {
                   
                    alertify.success("Success "+data.message);

                },
                error: function( data ) {
                    alertify.error("some thinng is wrong");

                }
            });
            
          
        }

        function openOrderAddressModel(id) {
            $.ajax({
                url: "{!! route('delivery-location-by-id') !!}",
                type: 'GET',
                data: {
                    id : id,
                    _token: '{{ csrf_token() }}'
                },
                success: function( data ) {
                    console.log(data);
                    delivery_locations = data.data;
                    $("#name").val(delivery_locations.name);
                    $("#address_name").val(delivery_locations.address);
                    $("#lat").val(delivery_locations.lat);
                    $("#long").val(delivery_locations.lng);
                    $("[name=description]").val(delivery_locations.description);
                    $("[name=shipping_location]").val(delivery_locations.id);
                    $('#us3').locationpicker({
                      location: {
                          latitude: delivery_locations.lat,
                          longitude: delivery_locations.lng
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
                
            
            $("#selectOrderAddress").modal('show');
        }

        $("#selectOrderAddress").on('hidden.bs.modal', function () {
            $("[name=name]").val('');
            $("[name=address]").val('');
            $("[name=address_name]").val('');
                  
                $('#us3').locationpicker({
                    location: {
                        latitude: 26.8634,
                        longitude: 75.7776
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
              
        });
        function addAddress() {
            $.ajax({
                url: "{!! route('delivery_location.store') !!}",
                type: 'POST',
                data:  $('#modify-address-from').serialize()+'&user_id={{$user->id}}&_token={{ csrf_token() }}',
                success: function( data ) {
                    $("#selectOrderAddress").modal('hide');
                    $('#modify-address-from').trigger("reset");
                    new PNotify({
                        title: 'success',
                        text: data.message,
                        type: "success",
                        styling: 'bootstrap3'
                    });
                    window.location.reload();

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

        }
        $('#us3').locationpicker({
            location: {
                latitude: 26.8634,
                longitude: 75.7776
                /*latitude: $('#lat').val(),
                longitude: $('#lng').val()*/
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
