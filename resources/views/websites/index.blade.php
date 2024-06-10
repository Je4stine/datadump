 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Company Websites<a href="{{ URL::to("websites/add") }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> ADD</a></div>
        </div>
        <div class="panel-body">
            <table id="rates_table" class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Url</th>
                    <th>Role</th>
                    <th>Layout</th>
                    <th>Action</th>
                </tr>
                @foreach($websites as $website)
                    <?php
                    ?>
                    <tr class="tabular">
                        <td>{{ $website->id }}</td>
                        <td>{{ $website->name  }}</td>
                        <td>{{ $website->home_url }}</td>
                        <td>{{ $website->role  }}</td>
                        <td>{{ $website->layout  }}</td>
                        <td>
                            <a class="btn btn-info btn-xs" onclick="" href="{{ URL::to("websites/view/$website->id") }}"><i class="fa fa-eye"></i> View</a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $websites->links() }}
        </div>
    </div>
    </div>

@endsection
