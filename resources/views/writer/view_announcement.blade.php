@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">{{ 'Announcements' }}</div>
        <div class="panel-body">

            <table align="centre" class="table table-bordered">
                <tr>
                    <td class="titlecolumn">ID</td>
                    <td>{{ $announcement->id }}</td>
                    <td class="titlecolumn">Date</td>
                    <td>{{ date('d/m/Y H:i', strtotime($announcement->created_at)) }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Announcement Body</strong></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <?php echo  str_replace('{name}','<strong>'.Auth::user()->name.'</strong>' ,nl2br(str_limit($announcement->message))) ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <style type="text/css">
        .titlecolumn {
            background: whitesmoke;
            white-space: nowrap;
            text-align: right;
            font-weight: bold;
            width: 5%;
        }
    </style>
@endsection