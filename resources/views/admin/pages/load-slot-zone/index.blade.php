@extends('admin.layouts.app')

@section('title', 'Load Slot Zone')
@push('css')
    <link href="{{asset('public/css/timepicki.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
<style type="text/css">
    .form-inline .form-group {
     padding-bottom: 0px; }
     .form-inline .form-control {display: block;}
     .btn {margin-top: 30px;}
     .datepicker {border-radius:0px;}
</style>
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
                            <h2>Load Slot Zone</h2>

                            <div class="clearfix"></div>
                            <div id="map" style="height: 300px;"></div>
                        </div>
                        <div class="x_content">

                                <form role="form"  class="form-inline" id="slot-vs-zone" method="POST">

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="from_date">Select Zone</label>
                                            {!!  Form::select('zone_id', $zones,null, array('class' => 'form-control select2-multiple','id'=>'zone')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="from_date">Current Date </label>
                                        <input type="text" id="from_date" name="current_date" class="form-control datepicker">
                                    </div>
                                   <!--   <div class="form-group">
                                        <label for="from_date">Current Time </label>
                                        <input type="text" id="to_date" name="current_time" class="form-control timepicker1">
                                    </div> -->
                                   <!--  <div class="form-group">
                                        <label for="to_date">Current Time </label>
                                        <input type="text" id="to_date" name="current_time" class="form-control timepicker1">
                                    </div> -->


                                    <div class="form-group">
                                        <button class="btn btn-primary" id="slot-vs-zone-button" type="button">Apply filter</button>
                                    </div>
                                </form>



                            <div id="ajax-data"></div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /page content -->

@endsection
@push('scripts')
 <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=drawing&callback=initMap&v=weekly"
            async defer></script>
    <script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('public/js/timepicki.js')}}"></script>
      <script>
       
        window.shape=null;
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -34.397, lng: 150.644},
                zoom: 8
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
            var zones = <?php echo json_encode($zonePoints) ?>;
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

                if(shape!=null){
                    shape.overlay.setMap(null);
                }
                if (event.type == 'polygon') {
                    shape=event;
                    var radius = event.overlay.getPath().getArray();

                    console.log(JSON.stringify(radius));
                    $("[name=point]").val(JSON.stringify(radius));
                }
            });
        }
        
        $(function () {
            $('.timepicker1').timepicki();
            $('.datepicker' ).datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
            });

            $("#slot-vs-zone-button").on('click',function () {

                $.ajax({
                    data: $('#slot-vs-zone').serialize(),
                    type: "GET",
                    url: "{{ url('admin/load-slot-zone') }}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( data ) {
                        $("#ajax-data").html(data.data);

                    },
                    error: function( data ) {
                        new PNotify({
                            title: 'Error',
                            text: 'something is wrong',
                            type: "error",
                            styling: 'bootstrap3'
                        });
                    }
                });
            })

        })
    </script>
@endpush
