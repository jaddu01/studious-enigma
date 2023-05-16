@extends('admin.layouts.app')

@section('title', 'Add Zone |')

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

                                {!! Form::open(['route' => 'zone.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','id'=>'zone_form']) !!}

                                {{csrf_field()}}
                                <span class="section">Add Zone</span>
                            <div id="map" style="height: 300px;"></div>
<br>                  
                            @foreach(config('translatable.locales') as $locale)
                                <div class="item form-group{{ $errors->has('name:'.$locale) ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::text('name:'.$locale, null, array('placeholder' => 'Name','class' => 'form-control col-md-7 col-xs-12',  'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale )) !!}
                                        @if ($errors->has('name:'.$locale))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name:'.$locale) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item form-group{{ $errors->has('description:'.$locale) ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Description In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::textarea('description:'.$locale, null, array('placeholder' => 'Name','class' => 'form-control col-md-7 col-xs-12','rows'=>'2',  'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale )) !!}
                                        @if ($errors->has('description:'.$locale))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('description:'.$locale) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            @endforeach
                                <input type="hidden" name="point" value="">
                                <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                        {{ Form::filedError('status') }}
                                    </div>
                                </div>




                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                       {{-- <button type="submit" class="btn btn-primary">Cancel</button>--}}
                                        {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!}
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
   
    <script>
        // This example requires the Drawing library. Include the libraries=drawing
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">
        window.shape=null;
        function initMap() {
           var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 25.14901470, lng: 75.83543170},
                zoom: 5
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

                if(shape!=null){
                    shape.overlay.setMap(null);
                }
                if (event.type == 'polygon') {
                    shape=event;
                    var radius = event.overlay.getPath().getArray();

                    //console.log(JSON.stringify(radius));
                    $("[name=point]").val(JSON.stringify(radius));
                }
            });
        }
 
 $("#zone_form").submit(function(){
    var pointVal = $("[name=point]").val();
    if(pointVal == ''){
        alert('Please draw a zone area first');
        return false;
    }

 });

    </script>
 <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=drawing&callback=initMap&v=weekly"
            async defer></script>
@endpush
