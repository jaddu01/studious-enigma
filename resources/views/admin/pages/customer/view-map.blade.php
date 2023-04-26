@extends('admin.layouts.app')
@section('title', ' Customer Address View Map |')
@push('css')
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
    <div class="right_col" role="main">
        <div class="">

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Address View Map</h2>
                            <div class="row">
							
								<div class="col-md-6 col-sm-6 col-xs-6">
								</div>
                            </div>
                           
                            <div class="clearfix"></div>
                            <div id="map" style="width: 900px; height: 450px;"></div>
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
<script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>

<!-- Datatables -->
<script src="{{asset('public/assets/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
{{--<script src="{{asset('public/assets/datatables.net-scroller/js/datatables.scroller.min.js')}}"></script>--}}
<script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
<script src="{{asset('public/js/bootstrap-toggle.min.js')}}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&callback=initialize&v=weekly"
        async defer></script>
        
        
<script>
    
function initialize() {     
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: new google.maps.LatLng(20.5937, 78.9629),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        
        var infowindow = new google.maps.InfoWindow();      
        var markers = new Array();
        var locations = <?php echo json_encode($zones) ?>;
        var i=0;
        for (k = 0; k < locations.length; k++) {
          /*  if (locations[k]['user_type'] == 'driver') {
				var iconsymbol = "{{asset('public/images/active-map.png')}}";
			} else {*/
				var iconsymbol = "{{asset('public/images/map-shoper-marker.png')}}";
			/*}
         */  
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[k]['lat'], locations[k]['lng']),
                map: map,
               icon:  iconsymbol,
               content:  locations[k]['name']+' <br /> '+locations[k]['address']
              });
             markers.push(marker);
              google.maps.event.addListener(marker, 'click', (function(marker, i)  {
            return function() {
                console.log(marker);
                infowindow.setContent(marker.content);
                infowindow.open(map, marker);
            }
          })(marker, i));
        
         }
         
         
        
         var bounds = new google.maps.LatLngBounds();
          //  Go through each...
          $.each(markers, function (index, marker) {
            bounds.extend(marker.position);
          });
          //  Fit these bounds to the map
          map.fitBounds(bounds);      
        
    } 

</script>


@endpush
