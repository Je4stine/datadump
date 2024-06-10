@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <a onclick="addTest();" class="btn btn-info btn-lg pull-right" data-toggle="modal" href="#test_modal"><i class="zmdi zmdi-plus"></i> ADD TEST</a>
    <hr/>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Topic</th>
            <th>Instructions</th>
            <th>Duration</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @foreach($random_tests as $random_test)
            <tr>
                <td>{{ $random_test->id }}</td>
                <td>{{ $random_test->topic }}</td>
                <td>{{ $random_test->instructions }}</td>
                <td>{{ $random_test->duration.' mins' }}</td>
                <td>
                    @if($random_test->active)
                        <span class="label label-success">Active</span>
                    @else
                        <span class="label label-warning">Disabled</span>
                    @endif
                </td>
                <td>
                    <a onclick="editTest({{ $random_test->id }},'{{ $random_test->topic }}','{{ $random_test->duration }}','{{ $random_test->instructions }}')" href="#test_modal" class="btn btn-info btn-xs" data-toggle="modal"><i class="fa fa-edit"></i> EDIT</a>
                    @if($random_test->active)
                        <button class="btn btn-warning btn-xs" onclick="runPlainRequest('{{ url("settings/tests/status") }}',{{ $random_test->id }})"><i class="fa fa-times"></i> Disable</button>
                    @else
                        <button class="btn btn-success btn-xs" onclick="runPlainRequest('{{ url("settings/tests/status") }}',{{ $random_test->id }})"><i class="fa fa-check"></i> Enable</button>
                    @endif
                </td>
            </tr>
            @endforeach
    </table>

    <div class="modal fade" id="test_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Essay Test Form
                    <a href="#" data-dismiss="modal" class="btn btn-danger pull-right"><i class="zmdi zmdi-close"></i> </a> </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal ajax-post" method="post" action="{{ url("settings/tests") }}">
                        <input type="hidden" name="id">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Topic</label>
                            <div class="col-md-10">
                                <input type="text" name="topic" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Duration (Mins)</label>
                            <div class="col-md-10">
                                <input type="number" name="duration" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Test Instructions</label>
                            <div class="col-md-10">
                                <textarea name="instructions" class="form-control" rows="15" required></textarea>
                            </div>
                        </div><div class="form-group">
                            <label class="col-md-2 control-label">&nbsp;</label>
                            <div class="col-md-10">
                               <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function addTest(){
            $("input[name='id']").val('');
            $("input[name='topic']").val('');
            $("textarea[name='instructions']").text('');
        }
        function editTest(id,topic,duration,instructions){
            $("input[name='id']").val(id);
            $("input[name='topic']").val(topic);
            $("input[name='duration']").val(duration);
            $("textarea[name='instructions']").text(instructions);
        }
    </script>
@endsection
