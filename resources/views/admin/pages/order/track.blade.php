@extends('admin.layouts.app')
@section('title', ' Categories |')
@push('css')
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
      #floating-panel {
        background-color: #fff;
        border: 1px solid #999;
        left: 25%;
        padding: 5px;
        position: absolute;
        top: 10px;
        z-index: 5;
      }
    </style>
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
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
    <?php   $pick_lat=$shopperLat;
            $pick_lng=$shopperLng;
           
            $drop_lat=$customerLat;
            $drop_lng=$customerLng;

            $driver_lat=$curlat;
            $driver_lng=$curlng;
  ?>
    <div class="right_col" role="main">
        <div class="">

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Order Tracking</h2>
                            <div class="clearfix"></div>
                            <input type="hidden" name="driver_id" value="{{$driver_id}}">
                            <div class="driver-map" id="googleMap" style="height: 450px;">
                            </div>
                        </div>
                       
                        <div class="x_content">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection
@push('scripts')
<!-- FastClick -->
    

 <script>
var drivermarker=null;
var map=null;
var istransitionrunning = 0;
  
function myMap() {
var mapProp= {
    // center:new google.maps.LatLng(26.545456,75.45645),
    //zoom:5,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};

var directionsService = new google.maps.DirectionsService;
var directionsDisplay = new google.maps.DirectionsRenderer;

var bounds = new google.maps.LatLngBounds();
var pickLatLng = {lat: '<?php echo $pick_lat;?>', lng: '<?php echo $pick_lng;?>'};
var dropLatLng = {lat: '<?php echo $drop_lat;?>', lng: '<?php echo $drop_lng;?>'};
//bounds.extend(pickLatLng);
//bounds.extend(dropLatLng);
map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

/*var picmarker = new google.maps.Marker({
    position: pickLatLng,
    map: map,
    title: 'PICKUP!'
  });
  
  var dropmarker = new google.maps.Marker({
    position: dropLatLng,
    map: map,
    title: 'DROP!'
  });*/
  //picmarker.setMap(map);
  //dropmarker.setMap(map);

  map.fitBounds(bounds);
 // map.panToBounds({lat: 26.363, lng: 75.044} || {lat: 26.552, lng: 76.999});
 directionsDisplay.setMap(map);
 calculateAndDisplayRoute(directionsService, directionsDisplay);
 setTimeout('', 2000);
 var driverlocdata={latitude: <?php echo $driver_lat;?>, longitude: <?php echo $driver_lng;?>};
 driverMarker(driverlocdata);
}

function driverMarker(data) {
  var pickLatLng = {lat: data.latitude, lng: data.longitude};
  
  if(drivermarker == null) {  
      var goldStar = {
        //path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
          path : "M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759 c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z    M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z M15.741,21.713   v4.492l-2.73-0.349V14.502L15.741,21.713z M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z M14.568,40.882l2.218-3.336   h13.771l2.219,3.336H14.568z M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805z" ,
        fillColor: "#F18A2E",
        fillOpacity: 1,
        scale: 0.7,
        strokeColor: "#000000",
        strokeWeight: 2,
        rotation : 0
      };

      
            
    drivermarker = new google.maps.Marker({
      position: pickLatLng,
      title: 'DRIVER!',
      icon: goldStar,
      map: map
      });
      drivermarker.setMap(map);
  } else {
    //drivermarker.setPosition(pickLatLng);
    transition(pickLatLng, drivermarker);
  }
}
var numDeltas = 100;
var delay = 16; //milliseconds
var i = 0;

function transition(result, drivermarker){
  if(istransitionrunning==0) {
    istransitionrunning=1;
    var position=drivermarker.getPosition();
    var finalPosition=result;

    var startTime=new Date().getTime();
    var durationinmilli=1000;
    
    
    i = 0;
    var deltaLat = (result.lat - position.lat())/numDeltas;
    var deltaLng = (result.lng - position.lng())/numDeltas;
    
    //alert(deltaLat);
    //alert(deltaLng);
    moveMarker(drivermarker, position, finalPosition, startTime, durationinmilli);
  }
}

function moveMarker(drivermarker, position, finalPosition, startTime, durationinmilli) {
  var elapsed = new Date().getTime() - startTime;
  var t= elapsed / durationinmilli;
  
  var v=t;
  
  var lastLocation = drivermarker.getPosition();
  
  var newlo =interpolate(v, position, finalPosition);
  var newAngle= computeHeadingCalculate(lastLocation, newlo);
  //alert(newAngle);
    /*var xxxx=newlo.lat()+','+newlo.lng();
      var el = document.createElement('div');
      el.innerText = xxxx;
      document.getElementById('debug').appendChild(el);
  */
    
    var goldStar = {
        path : "M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759 c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z    M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z M15.741,21.713   v4.492l-2.73-0.349V14.502L15.741,21.713z M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z M14.568,40.882l2.218-3.336 h13.771l2.219,3.336H14.568z M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805z" ,
        fillColor: "#F18A2E",
        fillOpacity: 1,
        scale: 0.7,
        strokeColor: "#000000",
        strokeWeight: 2,
        rotation : newAngle
      };
      drivermarker.setIcon(goldStar);   
    
  if(newAngle!=0) {
    //drivermarker.getIcon.rotation+=newAngle;
  }

  drivermarker.setPosition(newlo);
    if(t < 1){
       setTimeout(moveMarker, delay, drivermarker, position, finalPosition, startTime, durationinmilli);
    } else {
    istransitionrunning=0;
  }
}

function toRadians (angdeg) {

  return angdeg / 180 * Math.PI;
}

function toDegrees (angdeg) {
  return angdeg * 180 / Math.PI;
}

function computeAngleBetween (fromLat, fromLng, toLat, toLng) {
  // Haversines formula
  var dLat = fromLat - toLat;
  var dLng = fromLng - toLng;
  return 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(dLat / 2), 2) + Math.cos(fromLat) * Math.cos(toLat) * Math.pow(Math.sin(dLng / 2), 2)));
}

function interpolate (fraction, from, to) {
  var fromLat = toRadians(from.lat());
  var fromLng = toRadians(from.lng());
  var toLat = toRadians(to.lat);
  var toLng = toRadians(to.lng);
  var cosFromLat = Math.cos(fromLat);
  var cosToLat = Math.cos(toLat);

  // Computes Spherical interpolation coefficients.
  var angle = computeAngleBetween(fromLat, fromLng, toLat, toLng);
  var sinAngle = Math.sin(angle);
  if (sinAngle < 1E-6) {
    return from;
  }
  var a = Math.sin((1 - fraction) * angle) / sinAngle;
  var b = Math.sin(fraction * angle) / sinAngle;
  
  // Converts from polar to vector and interpolate.
  var x = a * cosFromLat * Math.cos(fromLng) + b * cosToLat * Math.cos(toLng);
  var y = a * cosFromLat * Math.sin(fromLng) + b * cosToLat * Math.sin(toLng);
  var z = a * Math.sin(fromLat) + b * Math.sin(toLat);

  // Converts interpolated vector back to polar.
  var lat = Math.atan2(z, Math.sqrt(x * x + y * y));
  var lng = Math.atan2(y, x);
  

    
  return new google.maps.LatLng(toDegrees(lat), toDegrees(lng));
}


function wrap (n, min, max) {
  return (n >= min && n < max) ? n : (mod(n - min, max - min) + min);
}

function mod(x, m) {
  return ((x % m) + m) % m;
}

function computeHeadingCalculate(from, to) {
  // http://williams.best.vwh.net/avform.htm#Crs<br /> 
  var fromLat = toRadians(from.lat());
  var fromLng = toRadians(from.lng());
  var toLat = toRadians(to.lat());
  var toLng = toRadians(to.lng());
  var dLng = toLng - fromLng;
  //alert(toLng);
    //alert(fromLng);
    //  alert(dLng);
  
  
  
  var heading = Math.atan2(
  Math.sin(dLng) * Math.cos(toLat),
  Math.cos(fromLat) * Math.sin(toLat) - Math.sin(fromLat) * Math.cos(toLat) * Math.cos(dLng));
  //alert(heading);
  return wrap(toDegrees(heading), -180, 180);
}

/*function angleFromCoordinate(double lat1, double long1, double lat2,
        double long2) {

    double dLon = (long2 - long1);

    double y = Math.sin(dLon) * Math.cos(lat2);
    double x = Math.cos(lat1) * Math.sin(lat2) - Math.sin(lat1)
            * Math.cos(lat2) * Math.cos(dLon);

    double brng = Math.atan2(y, x);

    brng = Math.toDegrees(brng);
    brng = (brng + 360) % 360;
    brng = 360 - brng; // count degrees counter-clockwise - remove to make clockwise

    return brng;
}*/
</script>


<script>
  $(document).ready(function(){
  setInterval(
      function() {
      $.ajax({
        data: {id:$("input[name=driver_id]").val()},
        method:'post',
        url: "{!! route('order.track.current') !!}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: 'json',
    
      })
    .done(function( data ) {
      
       var driverlocdataa={latitude: data[0].latitude, longitude: data[0].longitude};
      driverMarker(driverlocdataa);
      // driverMarker(data);
       
      
    });
      }, 5000);
  });
        
    
  
        function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        directionsService.route({
        origin: new google.maps.LatLng('<?php echo $pick_lat; ?>', '<?php echo $pick_lng; ?>'),
        destination: new google.maps.LatLng('<?php echo $drop_lat; ?>', '<?php echo $drop_lng; ?>'),
        travelMode: 'DRIVING'
        }, function(response, status) {
        if (status === 'OK') {
          directionsDisplay.setDirections(response);
        } else {
           // window.alert('Directions request failed due to ' + status);
        }
        });
    }
        
       

    </script>

<script src="https://maps.googleapis.com/maps/api/js?callback=myMap&key={{env('GOOGLE_API_KEY')}}&v=weekly">
      
    </script>
 
@endpush
