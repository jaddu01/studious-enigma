<div id="map" style="width: 900px; height: 450px;"></div>

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
            if (locations[k]['user_type'] == 'driver') {
				var iconsymbol = "{{asset('public/images/active-map.png')}}";
			} else {
				var iconsymbol = "{{asset('public/images/map-shoper-marker.png')}}";
			}
           
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
