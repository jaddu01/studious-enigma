@extends('layouts.app')
@section('content')
   <section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>Profile</li>
	<li>Add New Address</li>	
	</ul>
	</div>	
</section>

<section class="product-listing-body">
	<div class="container">
		<div class="row">

                         <div class="clearfix"></div>
                            <div id="map" style="height: 300px;"></div>

		</div>
	</div>	
</section>

@endsection
@push('scripts')
<!-- FastClick -->
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=drawing&callback=initMap&v=weekly"
        async defer></script>
<script>
    // This example requires the Drawing library. Include the libraries=drawing
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">
    window.shape=null;
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
             center: {lat: 25.8819299, lng: 50.9042899},
            zoom: 9
        });

        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [/*'marker', 'circle','polyline', 'rectangle',*/ 'polygon']
            },
            markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},
            circleOptions: {
                fillColor: '#ffff00',
                fillOpacity: 1,
                strokeWeight: 5,
                clickable: false,
                editable: true,
                zIndex: 1
            }
        });

        var zones = <?php echo json_encode($zones) ?>;
        for (var i in zones){
            var bermudaTriangle_all = new google.maps.Polygon({
                paths: zones[i]['points'],
                strokeColor: '#2aff20',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#2529ff',
                fillOpacity: 0.35
            });
            bermudaTriangle_all.setMap(map);
        }

        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            bermudaTriangle.setMap(null);
            if(shape!=null){
                shape.overlay.setMap(null);
            }
            if (event.type == 'polygon') {
                shape=event;
                var radius = event.overlay.getPath().getArray();\
                //console.log(JSON.stringify(radius));
                $("[name=point]").val(JSON.stringify(radius));
            }
        });
    }


</script>

@endpush