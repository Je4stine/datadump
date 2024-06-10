 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">user Applications </div>
        <div class="panel-body">
            <form method="GET" action="{{ URL::to('emails/send') }}">
                @if(Auth::user()->isAllowedTo('send_emails')) <input class="" type="checkbox" name="check_all" value="all" onchange="checkAllWriters()"> Check All <span style="display:none;" class="hidden_all"><input type="checkbox" name="all_pages" value="all page"> All Pages</span> @endif

                <table class="table table-bordered">
                <tr class="tabular">
                    <th>Id</th>
                    <th>E-mail</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                @foreach($users as $user)
                    <?php
                    $id = $user->id ?>
                    <tr class="tabular">
                        <td>{{ $user->id }}
                            @if(Auth::user()->isAllowedTo('send_emails'))   <input class="checkbox" type="checkbox" name="user_ids[]" value="{{ $user->id }}"> @endif

                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->name }}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ URL::to("user/$user->id/activate") }}"><i class="fa fa-check"></i> Activate</a>
                            <a class="btn btn-success btn-sm" href="{{ URL::to("user/view/$user->role/$user->id") }}"><i class="fa fa-user"></i> Profile</a>
                            @if(Auth::user()->isAllowedTo('delete_data'))
                                <a onclick="return confirm('Delete User {{ $user->id }} ?\n All items and info associated with user will be permanently deleted!')" href="{{ URL::to("user/delete/$user->id") }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>
                            @endif
                        </td>
                    </tr>
                    <div class="well gridular well-lg col-md-11">
                        <div class="row">
                            <div class="col-sm-4"><strong>user: </strong>#{{ $user->id }} - {{ $user->name  }}</div>
                            <div class="col-sm-4"><strong>user: </strong>{{ $user->email  }}</div>
                            <div class="dropdown pull-right">
                                <a class="btn btn-info btn-sm" href="{{ URL::to("user/$user->id/application_info") }}"><i class="fa fa-info"></i> Application Info</a>
                            </div>
                        </div>

                    </div>
                @endforeach

            </table>
                    @if(Auth::user()->isAllowedTo('send_emails'))
                        <input type="hidden" name="role" value="{{ 'writer' }}">
                        @foreach(Request::all() as $param=>$value)
                            <input type="hidden" name="{{ $param }}" value="{{ $value }}">
                        @endforeach
                        <button type="submit" class="btn btn-success"><i class="fa fa-envelope"></i> Send Emails</button>
            </form>
            @endif
            {{ $users->links() }}
        </div>
    </div>

    <script type="text/javascript">
        function checkAllWriters(){
            var checked = $("input[name='check_all']").is(":checked");
            if(checked==true){
                $(".checkbox").prop('checked', true);
                $(".hidden_all").slideDown();
            }else{
                $(".checkbox").prop('checked', false);
                $(".hidden_all").hide();
            }
        }
    </script>
@endsection