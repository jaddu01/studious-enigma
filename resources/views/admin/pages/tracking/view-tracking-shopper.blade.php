@extends('admin.layouts.app')
@section('title', ' Shopper Tracking |')
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
                            <h2>Shopper Tracking</h2>

                            <div class="clearfix"></div>
                            <div id="map" style="width: 900px; height: 450px;"></div>
                        </div>
                        {{--<div class="col-sm-12">
                            <div class="zone-operation-nav-bar">
                            <ul>
                                <li class="active"><a href="">Operation</a></li>
                                <li><a href="">Info</a></li>
                                <li><a href="">City/Region</a></li>
                                <li><a href="">Statistics</a></li>
                            </ul>
                        </div>
                        </div>--}}
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
    // This example requires the Drawing library. Include the libraries=drawing
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">
    //window.shape=null;
   /* function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 7,
        center: new google.maps.LatLng(41.976816, -87.659916),
        mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var infowindow = new google.maps.InfoWindow();

        var marker, i, k;

        var locations = <?php echo json_encode($zones) ?>;

         for (k = 0; k < locations.length; k++) {
            console.log(locations[k]['lat']);

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[k]['lat'], '-'+locations[k]['lng']),
                icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function (marker, k) {
                return function () {
                    infowindow.setContent("<div style='color:#000000';> "+locations[k]['name']+"</div>");
                    infowindow.open(map, marker);
                }
            })(marker, k));
        
        }


}*/

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
            var iconsymbol = "{{asset('public/images/active-map.png')}}";
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[k]['current_lat'], locations[k]['current_lng']),
                map: map,
               icon:  iconsymbol,
               content:  locations[k]['name'],
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
