 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Admin Groups</div>
        </div>
        <div class="panel-body">
            <table class="table table-stripped">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Users</th>
                    <th>Action</th>
                </tr>
                @foreach($admin_groups as $admin_group)
                    <tr>
                        <td>{{ $admin_group->id }}</td>
                        <td>{{ $admin_group->name }}</td>
                        <td>{{ $admin_group->description }}</td>
                        <td>{{ count($admin_group->users) }}</td>
                        <td>
                            <a class="btn btn-info" href="{{ URL::to("admin_groups/view/$admin_group->id") }}">View</a>
                        </td>
                    </tr>
                    @endforeach
            </table>
            {{ $admin_groups->links() }}
        </div>
    </div>
@endsection
