@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('title')
    Essay Test Section
@endsection
@section('content')
    <?php
    $test = Auth::user()->hasPendingTest();
    ?>
    @if($test->started_at)
        <link rel="stylesheet" href="{{ url("flipclock/flipclock.css") }}">
        <script src="{{ url("flipclock/flipclock.min.js") }}"></script>
        <div class="col-md-10">

                <table class="table table-condensed table-bordered">
                    <tr>
                        <th>Test ID</th>
                        <td>{{ $test->id }}</td>
                    </tr>
                    <tr>
                        <th>Duration</th>
                        <td>{{ $test->randomTest->duration.' Mins' }}</td>
                    </tr>
                    <tr>
                        <th>Essay Topic</th>
                        <td>{{ $test->randomTest->topic }}</td>
                    </tr>
                    <tr>
                        <th>Essay Instructions</th>
                        <td>{!! nl2br($test->randomTest->instructions) !!}</td>
                    </tr>
                    <tr>
                        <th>Remaining Time</th>
                        <td>
                            <?php
                            $start_time = \Carbon\Carbon::createFromTimestamp(strtotime($test->started_at));
                            $end_time = $start_time->addMinutes($test->randomTest->duration);
                            if($end_time->isPast()){
                                $secs = 0;
                            }else{
                                $secs = $end_time->diffInSeconds();
                            }
                            ?>

                            <div class="clock"></div>

                            <script type="text/javascript">
                                var clock = $('.clock').FlipClock({{ $secs }}, {
                                    countdown: true
                                });
                            </script>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="alert alert-info">
                                Kindly upload a well formatted essay test answer here after you have finished
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ url("writer/test") }}">
                                <input type="hidden" name="id" value="{{ $test->id }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="control-label col-md-4">Essay Upload</label>
                                    <div class="col-md-8">
                                        <input type="file" class="form-control" required name="essay_file">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">&nbsp</label>
                                    <div class="col-md-8">
                                        <button class="btn btn-info btn-lg" type="submit">Upload Essay</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                </table>
        </div>
    @else
        <div class="col-md-5">
                <table class="table table-condensed">
                    <tr>
                        <th>Test ID</th>
                        <td>{{ $test->id }}</td>
                    </tr>
                    <tr>
                        <th>Duration</th>
                        <td>{{ $test->randomTest->duration.' Mins' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a onclick="return runPlainRequest('{{ url("writer/test") }}',{{ $test->id }})" href="#" class="btn btn-info btn-lg">Start Test!</a>
                        </td>
                    </tr>
                </table>
            <div class="alert alert-info">
                The test essay will be completed within the specified duration…so there will be a timer which starts counting down

                after the test essay button is clicked.

                {{--Note: You will only get compensated for the work done in this test essay if the application is approved.--}}

            </div>
        </div>
    @endif
    <div class="row"></div>
@endsection