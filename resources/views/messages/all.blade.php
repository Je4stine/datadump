@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Active Orders</div>
        </div>
        <div class="panel-body">
        <table class="table table-condensed table-striped">
            <tr>
                <th>#</th>
                <th>Message</th>
                <th>Department</th>
                <th>On</th>
                <th>Action</th>
            </tr>
            @foreach($messages as $message)
                <tr>
                    <td>{{ $message->id }}</td>
                    <td>{{ $message->$message  }}</td>
                    <td>
                        @if($message->department_name)
                            {{ $message->department_name }}
                            @elseif($message->order_id)
                            {{ "Order#".$message->oder_id }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $message->created_at }}</td>
                    <td>
                        @if($message->department_name)
                            <a class="btn btn-info btn-sm"><i class="fa fa-comment"></i> Chat</a>
                        @elseif($message->order_id)
                            <a class="btn btn-success btn-sm"><i class="fa fa-users"></i> Room</a>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                @endforeach
        </table>
        </div>
    </div>
@endsection