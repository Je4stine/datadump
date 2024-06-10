 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">Writers</div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr class="tabular">
                    <th>Id</th>
                    <th>E-mail</th>
                    <th>Name</th>
                    <th>Active<br/><small>(Orders)</small></th>
                    <th>Revision<br/><small>(Orders)</small></th>
                    <th>Pending<br/><small>(Orders)</small></th>
                    <th>Completed<br/><small>(Orders)</small></th>
                    <th>Action</th>
                </tr>
                @foreach($writers as $writer)
                    <?php
                    $id = $writer->id ?>
                    <tr class="tabular">
                        <td>{{ $writer->id }}</td>
                        <td>{{ $writer->email }}</td>
                        <td>{{ $writer->name }}</td>
                        <td>
                            {{ $active = count(\App\Assign::where([
                                         ['user_id',$writer->id],
                                         ['status',0]
                                ])->get()) }}
                        </td>
                        <td>
                            {{ $revision = count(\App\Assign::where([
                                        ['user_id',$writer->id],
                                        ['status',2]
                               ])->get()) }}
                        </td>
                        <td>
                            {{ $pending = count(\App\Assign::where([
                                        ['user_id',$writer->id],
                                        ['status',3]
                               ])->get()) }}
                        </td>
                        <td>
                            {{ $completed = count(\App\Assign::where([
                                        ['user_id',$writer->id],
                                        ['status',4]
                               ])->get()) }}
                        </td>
                        <td><a href="{{ URL::to("order/writer/$writer->id") }}" class="btn btn-info">View</a> </td>
                    </tr>
                        <div class="well gridular well-lg col-md-11">
                            <div class="row">
                                <div class="col-sm-4"><strong>Writer: </strong>#{{ $writer->id }} - {{ $writer->name  }}</div>
                                <div class="col-sm-4"><strong>Writer: </strong>{{ $writer->email  }}</div>
                                <div class="dropdown pull-right">
                                    <a href="{{ URL::to("order/writer/$writer->id") }}" class="btn btn-info">View</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><strong>Active<small>(Orders)</small>: </strong>{{ $active }}</div>
                                <div class="col-sm-5"><strong>Revision<small>(Orders)</small>: </strong>{{ $revision }}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><strong>Pending<small>(Orders)</small>: </strong>{{ $pending }}</div>
                                <div class="col-sm-5"><strong>Completed<small>(Orders)</small>: </strong>{{ $completed }}</div>
                            </div>

                        </div>
                    @endforeach
            </table>
        </div>
    </div>
@endsection