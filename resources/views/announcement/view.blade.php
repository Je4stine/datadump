 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">{{ ucwords($role).' Announcements' }} <a class="btn btn-primary pull-right" href="{{ URL::to("announcements/add/$role") }}">Add Announcement</a></div>
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
                        <td><?php echo  str_replace('{name}','<strong>'.Auth::user()->name.'</strong>' ,nl2br($announcement->message)) ?></td>
                        <td>{{ date('d/m/Y H:i', strtotime($announcement->created_at)) }}</td>
                        <td>
                            @if($announcement->published == 0)
                            <button onclick="runPlainRequest('{{ URL::to('announcements/publish') }}',{{ $announcement->id }})" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Publish</button>
                            <button onclick="deleteItem('{{ URL::to('announcements/delete') }}',{{ $announcement->id }})" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Remove</button>
                                @else
                                <button onclick="runPlainRequest('{{ URL::to('announcements/unpublish') }}',{{ $announcement->id }})" class="btn btn-xs btn-success"><i class="fa fa-thumbs-down"></i> UnPublish</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
            </table>
            {{ $announcements->links() }}
        </div>
    </div>
@endsection