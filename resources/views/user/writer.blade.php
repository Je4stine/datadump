 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">{{ $role }}</div>
        <div class="panel-body">
            <form method="GET" action="{{ URL::to('emails/send') }}">
                @if(Auth::user()->isAllowedTo('send_emails')) <input class="" type="checkbox" name="check_all" value="all" onchange="checkAllWriters()"> Check All <span style="display:none;" class="hidden_all"><input type="checkbox" name="all_pages" value="all page"> All Pages</span> @endif

                <table class="table table-bordered">
                <tr class="tabular">
                    <th>Id</th>
                    @if(Auth::user()->isAllowedTo('view_email'))
                        <th>E-mail</th>
                    @endif
                    <th>Name</th>
                    <th>Active<br/><small>(Orders)</small></th>
                    <th>Revision<br/><small>(Orders)</small></th>
                    <th>Pending<br/><small>(Orders)</small></th>
                    <th>Completed<br/><small>(Orders)</small></th>
                    <th>Action</th>
                </tr>
                @foreach($users as $writer)
                    <?php
                    $id = $writer->id;
                    ?>
                    <tr class="tabular">
                        <td>{{ $writer->id }}
                            @if(Auth::user()->isAllowedTo('send_emails'))   <input class="checkbox" type="checkbox" name="user_ids[]" value="{{ $writer->id }}"> @endif
                        </td>
                        @if(Auth::user()->isAllowedTo('view_email'))
                            <td>{{ $writer->email }}</td>
                        @endif
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
                        <td>
                            <a href="{{ URL::to("user/view/$writer->role/$writer->id") }}" class="btn btn-info btn-xs">View</a>
                          @if(Auth::user()->isAllowedTo('change_role'))  <a href="{{ URL::to("user/changerole/$writer->id") }}" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Role</a> @endif
                        </td>
                    </tr>
                    <div class="well gridular well-lg col-md-11">
                        <div class="row">
                            <div class="col-sm-4"><strong>Writer: </strong>#{{ $writer->id }} - {{ $writer->name  }}</div>
                            <div class="col-sm-4"><strong>Writer: </strong>{{ $writer->email  }}</div>
                            <div class="dropdown pull-right">
                                <a href="{{ URL::to("order/writer/$writer->id") }}" class="btn btn-info btn-xs">View</a>
                                <a href="{{ URL::to("user/changerole/$writer->id") }}" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Role</a>
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
                    @if(Auth::user()->isAllowedTo('send_emails'))
                        <input type="hidden" name="role" value="{{ $role }}">
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