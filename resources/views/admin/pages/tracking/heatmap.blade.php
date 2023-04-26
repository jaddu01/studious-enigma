@extends('admin.layouts.app')
@section('title', ' Order Hit Map |')
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
    <div class="right_col" role="main">
        <div class="">

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Order Hit Map</h2>

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

 <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=visualization&callback=initMap&v=weekly">
    </script>

<script>

    var map,heatmapData, heatmap;
     
    var locations = <?php echo json_encode($heatMapArray) ?>;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: {lat: 15.5110778, lng: 32.4955184},
          mapTypeId: 'roadmap'
        });

        heatmap = new google.maps.visualization.HeatmapLayer({
          data: getPoints(),
          map: map,
          radius:20
        });
      }

      // Heatmap data lat and lng
console.log(locations);
      function getPoints() {
       var heatmapData=[];
        for (k = 1; k <= Object.keys(locations).length; k++) {
              heatmapData.push(new google.maps.LatLng(locations[k]['lat'], locations[k]['lng']));
              //console.log(heatmapData);
        }
        console.log(heatmapData);
        return heatmapData;
      }
       

</script>


@endpush
