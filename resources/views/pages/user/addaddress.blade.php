@extends('layouts.app')
@section('title', ' Address Details |')
@push('css')
<style type="text/css">
      hr {clear: both;}
    .pac-container {  
                z-index: 10000;
    }
</style>
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
@endpush
@section('content')
<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="{{url('/')}}">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li><a href="{{url('/profile')}}">Profile</a></li>
  <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li><a href="{{url('/addnewaddress')}}">Add New Address</a></li>	
	</ul>
	</div>	
</section>

<section class="product-listing-body">
	<div class="container">
		<div class="row">

  		<div class="col-sm-4 col-md-3">
    		<div class="sdbr_wshlst_mn">
    	
    	     <div class="prfl_sdbr clearfix">
                <div class="prfl_sdbr_slf">
                <?php if(($user->image!='')){?>
                <img src="{{$user->image}}" alt="profile">
                <?php }else{ ?>
                <img src="{{url('/storage/app/public/upload/404.jpeg')}}" alt="profile">
                <?php } ?>
              </div>
              <div class="prfl_sdbr_con">
                <span>Welcome</span>
                <h4>{{$user->name}}</h4>
              </div>
           </div>

           <div class="sdbr_othr_con">
             <div class="sdbr_oc_sngl">
                <h4>Account Setting</h4>
                <ul>
                  <li>
                    <a href="{{url('/profile')}}">Profile Information</a>
                  </li>
                  <li>
                    <a href="{{url('/addnewaddress')}}">Manage Address</a>
                  </li>
                  <!-- <li>
                   <a href="{{url('/change-password')}}"> Change Password </a>
                  </li> -->
                </ul>
              </div>

            <div class="sdbr_oc_sngl">
                <h4>Payments</h4>
                <ul>
                  <li>
                    <a href="{{url('/mywallet')}}">My Wallet <span class="lbl_sdbr_wslst">₹ {{number_format($user->wallet_amount,2,'.',',')}}</span></a>
                  </li>
                  <li>
                    <a href="{{url('/mycoins')}}">My Coins <span class="lbl_sdbr_wslst" style="text-align: right"><img src="{{url('/public/images/daarbar-coin.webp')}}" style="width: 8%" /> {{ number_format($user->coin_amount,2,'.',',') }}</span></a>
                  </li>
                   <li>
                    <a href="{{url('/membership')}}">Membership <span class="lbl_sdbr_wslst">
                      {{ (!empty($user->membership) &&  ($user->membership_to >= date('Y-m-d H:i:s')) ) ? "YES" : "NO"}} </span></a>
                  </li>
                  <li>
                    <a href="{{url('/orderhistory')}}">View shoping orders <span class="lbl_sdbr_wslst">{{$total_order}}</span></a>
                  </li>
                </ul>
              </div>

              <div class="sdbr_oc_sngl">
                <h4>Customer Service</h4>
                <ul>
                  <li>
                    <a href="{{url('/support')}}">Contact us</a>
                  </li>
                   <li>
                    <a href="{{url('/about-us')}}">About us</a>
                  </li>
                  <li>
                    <a href="{{url('/faq')}}">Faq's</a>
                  </li> 
                  <li>
                    <a href="{{url('/terms-and-condition')}}">Terms & conditions</a>
                  </li>
                  <li>
                    <a href="{{url('/privacy-policy')}}">Privacy policy</a>
                  </li>
                </ul>
              </div>

            <!--   <div class="sdbr_oc_sngl">
                <h4>Language</h4>
                <ul>
                  <li>
                    <a href="#">English</a>
                  </li>
                  <li>
                    <a href="#">Arabic</a>
                  </li>
                </ul>
              </div> -->
           </div>

        </div>
    			
    	</div>
	
  		<div class="col-sm-8 col-md-9">
  			 <div class="wshlst_rt_mn clearfix new-address-form">
            <h3>Add New Address</h3>   
            <div class="row">
			<div class="form-group clearfix">
			<div class="col-sm-12">
			<a  data-toggle="modal" data-target="#selectOrderAddress"><button class="green-btn" type="button"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>  Add My Location From Map</button></a>
			</div>
			</div>	
     
      
      <table class="table table-striped table-bordered" >
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
          @foreach($deliverylocations as $key=>$deliveryLocation)
                                <tr>

                  <th>{{$key+1}}</th>
                  <th>{{$deliveryLocation->name}}</th>
                  <th>{{$deliveryLocation->address}}</th>
                  <th>{{$deliveryLocation->description}}</th>
                  <th>
                                        <button onclick="openOrderAddressModel({{$deliveryLocation->id}})" type="button" class="btn btn-success btn-xs addrbtn  ">Edit</button>
                                        {!! Form::open(['route' => ["delivery_location.destroy",$deliveryLocation->id],'method'=>'post','class'=>'']) !!}
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}
                                        <button type="submit"  class="btn btn-danger btn-xs addrbtn">Delete</button>{!! Form::close() !!}
                                    </th>

                                </tr>
                                  @endforeach
                                </tbody>
                            </table>
           </div>
		 </div>
  		</div>
		</div>
	</div>	
</section>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Latitute <span class="required">*</span>
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
                   <!--  <div class="item form-group{{ $errors->has('building') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">   Building<span class="required">*</span></label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!!  Form::text('building', null, array('placeholder' => 'building','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('building'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('building') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="item form-group{{ $errors->has('flat') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Flat <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('flat', null, array('placeholder' => 'flat','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('flat'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('flat') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                      <div class="item form-group{{ $errors->has('floor_number') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Floor_number <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('floor_number', null, array('placeholder' => 'Floor Number','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('floor_number'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('floor_number') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                        <div class="item form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Street
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('street', null, array('placeholder' => 'Street','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('street'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('street') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                     <div class="item form-group{{ $errors->has('zone') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Zone
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::text('zone', null, array('placeholder' => 'zone','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('zone'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('zone') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div> -->
                    <div class="item form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Description
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            {!!  Form::textarea('description', null, array('placeholder' => 'address description','class' => 'form-control col-md-7 col-xs-12' )) !!}
                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
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
                    $("[name=building]").val(delivery_locations.building);
                    $("[name=flat]").val(delivery_locations.flat);
                    $("[name=floor_number]").val(delivery_locations.floor_number);
                    $("[name=street]").val(delivery_locations.street);
                    $("[name=zone]").val(delivery_locations.zone);
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
                    if(data.error){
                      new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: "error",
                        styling: 'bootstrap3'
                    });

                    }else{
                      new PNotify({
                        title: 'success',
                        text: data.message,
                        type: "success",
                        styling: 'bootstrap3'
                    });
                    }
                    
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
