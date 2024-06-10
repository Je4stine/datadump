<script type="text/javascript">
    showRatingGauge();
    function showRatingGauge(){
        $(function () {

            $('#rating_gauge').highcharts({

                        chart: {
                            type: 'gauge',
                            plotBackgroundColor: null,
                            plotBackgroundImage: null,
                            plotBorderWidth: 0,
                            plotShadow: false
                        },

                        title: {
                            text: 'Writer Rating'
                        },

                        pane: {
                            startAngle: -150,
                            endAngle: 150,
                            background: [{
                                backgroundColor: {
                                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                                    stops: [
                                        [0, '#FFF'],
                                        [1, '#333']
                                    ]
                                },
                                borderWidth: 0,
                                outerRadius: '109%'
                            }, {
                                backgroundColor: {
                                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                                    stops: [
                                        [0, '#333'],
                                        [1, '#FFF']
                                    ]
                                },
                                borderWidth: 1,
                                outerRadius: '107%'
                            }, {
                                // default background
                            }, {
                                backgroundColor: '#DDD',
                                borderWidth: 0,
                                outerRadius: '105%',
                                innerRadius: '103%'
                            }]
                        },

                        // the value axis
                        yAxis: {
                            min: 0.0,
                            max: 5.0,

                            minorTickInterval: 'auto',
                            minorTickWidth: 0.1,
                            minorTickLength: 0.1,
                            minorTickPosition: 'inside',
                            minorTickColor: '#666',

                            tickPixelInterval: 0.5,
                            tickWidth: 1,
                            tickPosition: 'inside',
                            tickLength: 0.1,
                            tickColor: '#666',
                            labels: {
                                step: 2.0,
                                rotation: 'auto'
                            },
                            title: {
                                text: 'Rating'
                            },
                            plotBands: [{
                                from: 0.0,
                                to: 2.0,
                                color: '#DF5353' // red
                            }, {
                                from: 2.0,
                                to: 4.0,
                                color: '#DDDF0D' // yellow
                            }, {
                                from: 4.0,
                                to: 5.0,
                                color: '#55BF3B' // green
                            }]
                        },

                        series: [{
                            name: 'Rating',
                            data: [{{ $user->getRating()  }}],
                            tooltip: {
                                valueSuffix: ' Stars'
                            }
                        }]

                    }

            );
        });
    }

    showOrderStats();
    function showOrderStats(){
        $(function () {
            $('#order_stats').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Order Statistics'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: [
                        'Active',
                        'Revision',
                        'Pending',
                        'Cancelled',
                        'Completed'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Orders'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y} </b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Orders',
                    data: [{{ $user->active() }}, {{ $user->revision() }}, {{ $user->pending() }},{{ $user->cancelled() }},{{ $user->completed() }}]

                }]
            });
        });
    }
</script>
<div id="category_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content &times; -->
        <div class="modal-content">
            <div class="modal-header">
                <!--
                                <button class="btn btn-danger btn-xs pull-right" class="close" data-dismiss="modal">X</button>
                -->
                <a data-dismiss="modal" class="btn btn-danger btn-sm pull-right">X</a>

                <h4 class="modal-title"><label>Change Writer Category</label></h4>
            </div>
            <div class="modal-body">
                <form method="post" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-2">Category</label>
                        <div class="col-md-5">
                            <select name="writer_category_id" class="form-control">
                                @foreach(\App\WriterCategory::where('deleted','=',0)->get()as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">&nbsp;</label>
                        <div class="col-md-5">
                            <button class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>
