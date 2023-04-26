@extends('admin.layouts.app')

@section('title', 'Dashboard')

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
 

<div class="right_col" role="main">

<div class="row tile_count" >
    <div class="col-lg-6 col-md-6">
        <div class="bg-red panel panel-yellow">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12 text-center">
                <i class="fa fa-user fa-5x"></i>
                </div>
                <div class="col-xs-12 text-center tile_stats_count">
                <div class="count"><a href="{{url('admin/customer')}}">{{ $totalUser}}</a></div>
                <div>Total User</div>
                </div>
            </div>
        </div>

        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="bg-blue panel panel-yellow">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12 text-center">
                <i class="fa fa-user fa-5x"></i>
                </div>
                <div class="col-xs-12 text-center tile_stats_count">
              <div class="count"><a href="{{url('admin/customer?type=today')}}">{{ $todayUser}}</a></div>
                <div>Today User</div>
                </div>
            </div>
        </div>

        </div>
    </div>
    <!-- <div class="col-lg-4 col-md-6">
        <div class="panel panel-yellow">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">
                <i class="fa fa-user fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right tile_stats_count">
               <div class="count">
                <a href="{{url('admin/user?search=shoper' )}}">{{ $totalShopper}}</a>
                </div>
                <div>Total Shopper</div>
                </div>
            </div>
        </div>

        </div>
    </div> -->


</div>
<div class="row tile_count">
<!-- <div class="col-lg-4 col-md-6">
    <div class="panel panel-yellow">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3">
            <i class="fa fa-user fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right tile_stats_count">
          <div class="count"><a href="{{url('admin/user?search=driver' )}}">{{ $totalDriver}}</a></div>
            <div>Total Driver</div>
            </div>
        </div>
    </div>

    </div>
</div> -->
<div class="col-lg-6 col-md-6">
    <div class="bg-blue-sky panel panel-yellow">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12 text-center">
            <i class="fa fa-shopping-cart fa-5x"></i>
            </div>
            <div class="col-xs-12 text-center tile_stats_count">
          <div class="count"><a href="{{url('admin/order')}}">{{ $totalOrder}}</a></div>
            <div>Total Order</div>
            </div>
        </div>
    </div>

    </div>
</div>
<div class="col-lg-6 col-md-6">
    <div class="bg-orange panel panel-yellow">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12 text-center">
            <i class="fa fa-shopping-cart fa-5x"></i>
            </div>
            <div class="col-xs-12 text-center tile_stats_count">
          <div class="count"><a href="{{url('admin/order?order_type=today')}}">{{ $todayOrder}}</a></div>
            <div>Today Order</div>
            </div>
        </div>
    </div>

    </div>
</div>
</div>
<!-- <div class="row tile_count">
    <div class="col-lg-4 col-md-6">
    <div class="panel panel-yellow">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3">
            <i class="fa fa-area-chart fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right tile_stats_count">
          <div class="count"></div>
            <div><a href="{{url('admin/operation' )}}">Operation</a></div>
            </div>
        </div>
    </div>

    </div>
</div>
<div class="col-lg-4 col-md-6">
    <div class="panel panel-yellow">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3">
            <i class="fa fa-tasks fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right tile_stats_count">
          <div class="count"></div>
            <div><a href="{{url('admin/vendor-product/shopperassignment' )}}">Shopper Assignment</a></div>
            </div>
        </div>
    </div>

    </div>
</div>
<div class="col-lg-4 col-md-6">
    <div class="panel panel-yellow">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3">
            <i class="fa fa-tasks fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right tile_stats_count">
          <div class="count"></div>
            <div><a href="{{url('admin/vendor-product/driverassignment' )}}">Driver Assignment</a></div>
            </div>
        </div>
    </div>

    </div>
</div>
    </div> -->
    <!-- <div class="row tile_count">
        <div class="col-lg-4 col-md-6">
        <div class="panel panel-yellow">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">
                <i class="fa fa-map-marker fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right tile_stats_count">
              <div class="count"><a href="{{url('admin/vendor-product/map' )}}">Map</a></div>
                <div> Driver/Shopper View</div>
                </div>
            </div>
        </div>

        </div>
    </div>
    
    </div> -->

        <!-- top tiles -->
       <!--  <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Users</span>
                <div class="count">2500</div>
                <span class="count_bottom"><i class="green">4% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-clock-o"></i> Average Time</span>
                <div class="count">123.50</div>
                <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Males</span>
                <div class="count green">2,500</div>
                <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Females</span>
                <div class="count">4,567</div>
                <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Collections</span>
                <div class="count">2,315</div>
                <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Connections</span>
                <div class="count">7,325</div>
                <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
            </div>
        </div> -->
        <br>

        <!-- /top tiles -->

    </div>
@endsection
@push('scripts')
<!-- FastClick -->
<script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('public/assets/nprogress/nprogress.js')}}"></script>
<!-- Chart.js -->
<script src="{{asset('public/assets/Chart.js/dist/Chart.min.js')}}"></script>
<!-- gauge.js -->
<script src="{{asset('public/assets/bernii/gauge.js/dist/gauge.min.js')}}"></script>
<!-- bootstrap-progressbar -->
<script src="{{asset('public/assets/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('public/assets/iCheck/icheck.min.js')}}"></script>
<!-- Skycons -->
<script src="{{asset('public/assets/skycons/skycons.js')}}"></script>
<!-- Flot -->
<script src="{{asset('public/assets/Flot/jquery.flot.js')}}"></script>
<script src="{{asset('public/assets/Flot/jquery.flot.pie.js')}}"></script>
<script src="{{asset('public/assets/Flot/jquery.flot.time.js')}}"></script>
<script src="{{asset('public/assets/Flot/jquery.flot.stack.js')}}"></script>
<script src="{{asset('public/assets/Flot/jquery.flot.resize.js')}}"></script>
<!-- Flot plugins -->
<script src="{{asset('public/js/flot/jquery.flot.orderBars.js')}}"></script>
<script src="{{asset('public/js/flot/date.js')}}"></script>
<script src="{{asset('public/js/flot/jquery.flot.spline.js')}}"></script>
<script src="{{asset('public/js/flot/curvedLines.js')}}"></script>
<!-- jVectorMap -->
<script src="{{asset('public/js/maps/jquery-jvectormap-2.0.3.min.js')}}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{asset('public/js/moment/moment.min.js')}}"></script>
<script src="{{asset('public/js/datepicker/daterangepicker.js')}}"></script>


<!-- Flot -->
<script>
    $(document).ready(function() {
        var data1 = [
            [gd(2012, 1, 1), 17],
            [gd(2012, 1, 2), 74],
            [gd(2012, 1, 3), 6],
            [gd(2012, 1, 4), 39],
            [gd(2012, 1, 5), 20],
            [gd(2012, 1, 6), 85],
            [gd(2012, 1, 7), 7]
        ];

        var data2 = [
            [gd(2012, 1, 1), 82],
            [gd(2012, 1, 2), 23],
            [gd(2012, 1, 3), 66],
            [gd(2012, 1, 4), 9],
            [gd(2012, 1, 5), 119],
            [gd(2012, 1, 6), 6],
            [gd(2012, 1, 7), 9]
        ];
        $("#canvas_dahs").length && $.plot($("#canvas_dahs"), [
            data1, data2
        ], {
            series: {
                lines: {
                    show: false,
                    fill: true
                },
                splines: {
                    show: true,
                    tension: 0.4,
                    lineWidth: 1,
                    fill: 0.4
                },
                points: {
                    radius: 0,
                    show: true
                },
                shadowSize: 2
            },
            grid: {
                verticalLines: true,
                hoverable: true,
                clickable: true,
                tickColor: "#d5d5d5",
                borderWidth: 1,
                color: '#fff'
            },
            colors: ["rgba(38, 185, 154, 0.38)", "rgba(3, 88, 106, 0.38)"],
            xaxis: {
                tickColor: "rgba(51, 51, 51, 0.06)",
                mode: "time",
                tickSize: [1, "day"],
                //tickLength: 10,
                axisLabel: "Date",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 10
            },
            yaxis: {
                ticks: 8,
                tickColor: "rgba(51, 51, 51, 0.06)",
            },
            tooltip: false
        });

        function gd(year, month, day) {
            return new Date(year, month - 1, day).getTime();
        }
    });
</script>
<!-- /Flot -->

<!-- jVectorMap -->
<script src="{{asset('js/maps/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{asset('js/maps/jquery-jvectormap-us-aea-en.js')}}"></script>
<script src="{{asset('js/maps/gdp-data.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#world-map-gdp').vectorMap({
            map: 'world_mill_en',
            backgroundColor: 'transparent',
            zoomOnScroll: false,
            series: {
                regions: [{
                    values: gdpData,
                    scale: ['#E6F2F0', '#149B7E'],
                    normalizeFunction: 'polynomial'
                }]
            },
            onRegionTipShow: function(e, el, code) {
                el.html(el.html() + ' (GDP - ' + gdpData[code] + ')');
            }
        });
    });
</script>
<!-- /jVectorMap -->

<!-- Skycons -->
<script>
    $(document).ready(function() {
        var icons = new Skycons({
                "color": "#73879C"
            }),
            list = [
                "clear-day", "clear-night", "partly-cloudy-day",
                "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
                "fog"
            ],
            i;

        for (i = list.length; i--;)
            icons.set(list[i], list[i]);

        icons.play();
    });
</script>
<!-- /Skycons -->

<!-- Doughnut Chart -->
<script>
    $(document).ready(function(){
        var options = {
            legend: false,
            responsive: false
        };

        new Chart(document.getElementById("canvas1"), {
            type: 'doughnut',
            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
            data: {
                labels: [
                    "Symbian",
                    "Blackberry",
                    "Other",
                    "Android",
                    "IOS"
                ],
                datasets: [{
                    data: [15, 20, 30, 10, 30],
                    backgroundColor: [
                        "#BDC3C7",
                        "#9B59B6",
                        "#E74C3C",
                        "#26B99A",
                        "#3498DB"
                    ],
                    hoverBackgroundColor: [
                        "#CFD4D8",
                        "#B370CF",
                        "#E95E4F",
                        "#36CAAB",
                        "#49A9EA"
                    ]
                }]
            },
            options: options
        });
    });
</script>
<!-- /Doughnut Chart -->

<!-- bootstrap-daterangepicker -->
<script>
    $(document).ready(function() {

        var cb = function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        };

        var optionSet1 = {
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2015',
            dateLimit: {
                days: 60
            },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'left',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary',
            cancelClass: 'btn-small',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            locale: {
                applyLabel: 'Submit',
                cancelLabel: 'Clear',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        };
        $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
        $('#reportrange').daterangepicker(optionSet1, cb);
        $('#reportrange').on('show.daterangepicker', function() {
            console.log("show event fired");
        });
        $('#reportrange').on('hide.daterangepicker', function() {
            console.log("hide event fired");
        });
        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
        });
        $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
            console.log("cancel event fired");
        });
        $('#options1').click(function() {
            $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
        });
        $('#options2').click(function() {
            $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
        });
        $('#destroy').click(function() {
            $('#reportrange').data('daterangepicker').remove();
        });
    });
</script>
<!-- /bootstrap-daterangepicker -->

<!-- gauge.js -->
<script>
    var opts = {
        lines: 12,
        angle: 0,
        lineWidth: 0.4,
        pointer: {
            length: 0.75,
            strokeWidth: 0.042,
            color: '#1D212A'
        },
        limitMax: 'false',
        colorStart: '#1ABC9C',
        colorStop: '#1ABC9C',
        strokeColor: '#F0F3F3',
        generateGradient: true
    };
    var target = document.getElementById('foo'),
        gauge = new Gauge(target).setOptions(opts);

    gauge.maxValue = 6000;
    gauge.animationSpeed = 32;
    gauge.set(3200);
    gauge.setTextField(document.getElementById("gauge-text"));
</script>
@endpush