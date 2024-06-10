 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Order Rates<a href="{{ URL::to("settings/add/urgency/0") }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> ADD</a> </div>
        </div>
        <div class="panel-body">
            <table id="rates_table" class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>ID</th>
                    <th>Hours</th>
                    <th>Label</th>
                    <th>High School</th>
                    <th>Undergraduate</th>
                    <th>Masters</th>
                    <th>Ph. D</th>
                    <th>Action</th>
                </tr>

                @foreach($urgencies as $urgency)
                    <?php
                    ?>
                    <tr class="tabular">
                        <td>{{ $urgency->id }}</td>
                        <td>{{ $urgency->hours  }}</td>
                        <td>{{ $urgency->label }}</td>
                        <td>{{ $urgency->high_school  }}</td>
                        <td>{{ $urgency->under_graduate  }}</td>
                        <td>{{ $urgency->masters }}</td>
                        <td>{{ $urgency->phd }}</td>
                        <td>
                            <a class="btn btn-info btn-xs" onclick="" href="{{ URL::to("settings/add/urgency/$urgency->id") }}"><i class="fa fa-edit"></i> Edit</a>
                            <a onclick="return confirm('Are you sure? ');" class="btn btn-danger btn-xs" href="{{ URL::to("settings/delete/urgency/$urgency->id") }}"><i class="fa fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                    <div class="row"></div>
                    <div class="well well-lg col-md-12 gridular" style="padding-top: 10px;padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-sm-7"><strong>ID: </strong>{{ $urgency->id  }}</div>
                            <div class="dropdown pull-right">
                                <a class="btn btn-info btn-xs" onclick="" href="{{ URL::to("settings/add/urgency/$urgency->id") }}"><i class="fa fa-edit"></i> Edit</a>
                                <a onclick="return confirm('Are you sure? ');" class="btn btn-danger btn-xs" href="{{ URL::to("settings/delete/urgency/$urgency->id") }}"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>High School: </strong>{{ $urgency->high_school  }}</div>
                            <div class="col-sm-3"><strong>Undergraduate: </strong>{{ $urgency->undergraduate  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Ph.D: </strong>{{ $urgency->phd  }}}</div>
                            <div class="col-sm-2"><strong>Masters: </strong>{{ $urgency->masters  }}</div>
                        </div>

                    </div>
                @endforeach
            </table>
            {{ $urgencies->links() }}

            </div>
        </div>
    <script type="text/javascript">
        function showForm(){
            $("#rates_form").slideToggle();
            $("#rates_table").slideToggle();
            return false;
        }
    </script>
@endsection
