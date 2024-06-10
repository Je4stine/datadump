 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">{{ $role }} @if($role=='admin') <a class="btn btn-info pull-right" href="{{ URL::to("user/add/$role")  }}"><i class="fa fa-plus"></i> ADD</a> @endif </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr class="tabular">
                    <th>Id</th>
                    <th>E-mail</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                @foreach($users as $writer)
                    <?php
                    $id = $writer->id ?>
                    <tr class="tabular">
                        <td>{{ $writer->id }}</td>
                        <td>{{ $writer->email }}</td>
                        <td>{{ $writer->name }}</td>
                        <td>
                            <a href="{{ URL::to("user/view/$role/$writer->id") }}" class="btn btn-info btn-xs">View</a>
                            <a href="{{ URL::to("user/changerole/$writer->id") }}" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Role</a>
                        </td>
                    </tr>
                    <div class="well gridular well-lg col-md-11">
                        <div class="row">
                            <div class="col-sm-4"><strong>Writer: </strong>#{{ $writer->id }} - {{ $writer->name  }}</div>
                            <div class="col-sm-4"><strong>Writer: </strong>{{ $writer->email  }}</div>
                            <div class="dropdown pull-right">
                                <a href="{{ URL::to("user/view/$role/$writer->id") }}" class="btn btn-info btn-xs">View</a>
                                <a href="{{ URL::to("user/changerole/$writer->id") }}" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Role</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </table>
            {{ $users->links() }}
        </div>
    </div>
@endsection