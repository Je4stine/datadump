@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">{{ 'Announcements' }}</div>
        <div class="panel-body">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>

                @foreach($announcements as $announcement)
                    <tr>
                        <td>{{ $announcement->id }}</td>
                        <td><?php echo  str_replace('{name}','<strong>'.Auth::user()->name.'</strong>' ,nl2br(str_limit($announcement->message))) ?></td>
                        <td>{{ date('d/m/Y H:i', strtotime($announcement->created_at)) }}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ URL::to("announcements/read/$announcement->id") }}">More... </a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $announcements->links() }}
        </div>
    </div>
@endsection