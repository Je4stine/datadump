 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php $user = Auth::user();
            ?>
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">{{ $admin_group->name }}</div>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                {{ $admin_group->description }}
                <div class="btn-group pull-right">
                    <a class="btn btn-success" href="{{ URL::to("admin_groups/add?id=$admin_group->id") }}">Edit</a>
                </div>
            </div>
                <div class="row"></div>
                <div class="col-md-8">
                    <div class="card section">
                        <div class="card-header">Users <a id="btn_new" href="#add_user_modal" data-toggle="modal" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</a> </div>
                        <div class="panel-body">
                            <table class="table table-condensed table-striped">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                    <th>Phone</th>
                                    @if($user->isAllowedTo('change_role'))
                                    <th>Action</th>
                                        @endif
                                </tr>
                                @foreach($users = $admin_group->users()->paginate(10) as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                       @if($user->isAllowedTo('change_role'))
                                        <td>
                                            <a class="btn btn-warning btn-xs" href="{{ URL::to("user/changerole/$user->id") }}">Change Role</a>
                                        </td>
                                           @endif
                                    </tr>
                                    @endforeach
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin_groups.new_user_modal');
@endsection
