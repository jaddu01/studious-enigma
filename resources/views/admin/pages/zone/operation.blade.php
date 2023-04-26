@extends('admin.layouts.app')

@section('title', ' Zone Operation |')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <style type="text/css">
        .dataTables_length {width: 20%;}
        .dt-buttons .btn {border-radius: 0px;padding: 4px 12px;}

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
                            <h2>Zone Operation</h2>

                            <div class="clearfix"></div>
                            <div id="map" style="height: 300px;"></div>
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
                        <!-- <div class="x_content"> -->

                        <table class="table table-striped table-bordered table-responsive" id="users-table">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Id</th>
                                    <th>Zone</th>
                                    <th>Vendor</th>
                                    <th>Shopper</th>
                                    <th>Driver</th>
                                    <th>Delivery Charges</th>
                                    <th>Minimum Order Amount</th>
                                    <th>Delivery Times</th>
                                </tr>
                                </thead>
                            </table>
                       <!--  </div> -->
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
<script src="{{asset('public/assets/jszip/dist/jszip.min.js')}}"></script>
<script src="{{asset('public/assets/pdfmake/build/pdfmake.min.js')}}"></script>
<script src="{{asset('public/assets/pdfmake/build/vfs_fonts.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
<script src="{{asset('public/js/bootstrap-toggle.min.js')}}"></script>

<script>
    var buttonCommonn = {
            exportOptions: {
                format: {
                    body: function (data, column, row, node) {
                        // if it is select
                        if (column == 3 || column == 4 || column == 5 || column == 7) {
                            return $(data).find("option:selected").text()
                        }else if (column == 6) {
                         return $(data).val()
                         } else return data
                    }
                }
            }
        };

$(function() {

    window.table=$('#users-table').DataTable({

        scrollX: true,
        scrollCollapse: true,
        fixedColumns: true,
        dom: 'lBfrtip',
        buttons: [
            
              $.extend(true, {}, buttonCommonn, {
                    extend: "excel"
                 }), 
                 $.extend(true, {}, buttonCommonn, {
                    extend: "pdf"
                }),
                 $.extend(true, {}, buttonCommonn, {
                    extend: "print"
                }),
        ],
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        responsive: false,
        processing: true,
        oLanguage: {
        sProcessing: "<img style='width:50%;height:auto' src='{{asset('public/upload/loader.gif')}}'>"
        },
        serverSide: true,

        ajax: '{!! route('operation.datatable') !!}',
        fnDrawCallback :function() {
            $('.data-toggle-coustom').bootstrapToggle({
            });
            $('.data-toggle-coustom').change(function() {
                var user_id =$(this).attr('user-id');
                changeStatus(user_id,$(this).val());
            })
        },
        columns: [
            { data: 'Slno', name: 'Slno' },
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'vendor', name: 'vendor',orderable: false, searchable: false },
            { data: 'shopper', name: 'shopper',orderable: false, searchable: false },
            { data: 'driver', name: 'driver' ,orderable: false, searchable: false},
            { data: 'delivery_charges', name: 'delivery_charges' ,orderable: false, searchable: false},
            { data: 'minimum_order_amount', name: 'minimum_order_amount' ,orderable: false, searchable: false},
            { data: 'delivery_times', name: 'delivery_times',orderable: false, searchable: false },
        ]
    });
});

function updateUserZone(obj,zone_id,user_id) {

    $.ajax({
        data: {
            zone_id:zone_id,
            old_user_id:$(obj).siblings().val(),
            _method:'PATCH'
        },
        type: "PATCH",
        url: "{!! url('admin/operation') !!}/"+(user_id=='' ? 0 : user_id),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
            new PNotify({
                title: 'Success',
                text: data.message,
                type: 'success',
                styling: 'bootstrap3'
            });
            $(obj).siblings().val(user_id);
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
    
}

function updateDelivaryCharges(deliveryCharge,zone_id) {
    if(isNaN(deliveryCharge)){
        alert("please enter valid number");
        return false
    }
    $.ajax({
        data: {
            zone_id:zone_id,
            delivery_charges:parseFloat(deliveryCharge),
            _method:'PATCH'
        },
        type: "POST",
        url: "{!! url('admin/zone') !!}/"+zone_id,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
            console.log(data);
            new PNotify({
                title: 'Success',
                text: data.message,
                type: 'success',
                styling: 'bootstrap3'
            });
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

}

function updateMinimumOrderAmount(minimumOrderAmount,zone_id) {
    if(isNaN(minimumOrderAmount)){
        alert("please enter valid number");
        return false
    }
    $.ajax({
        data: {
            zone_id:zone_id,
            minimum_order_amount:parseFloat(minimumOrderAmount),
            _method:'PATCH'
        },
        type: "POST",
        url: "{!! url('admin/zone') !!}/"+zone_id,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
            console.log(data);
            new PNotify({
                title: 'Success',
                text: data.message,
                type: 'success',
                styling: 'bootstrap3'
            });
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

}

function updateDeliveryTimes(deliveryTimes,zone_id) {
    $.ajax({
        data: {
            zone_id:zone_id,
            package_id:deliveryTimes,
            _method:'PATCH'
        },
        type: "POST",
        url: "{!! url('admin/zone') !!}/"+zone_id,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function( data ) {
            console.log(data);
            new PNotify({
                title: 'Success',
                text: data.message,
                type: 'success',
                styling: 'bootstrap3'
            });
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

}

</script>

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
            bermudaTriangle.setMap(null);
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


</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=drawing&callback=initMap&v=weekly"
        async defer></script>
@endpush