 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Suspend <strong>{{ $user->name }}</strong><a class="btn btn-info btn-xs pull-right" href="{{ URL::to("user/view/client/$user->id") }}"><i class="fa fa-user"></i> Profile</a> </div>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{ URL::to("user/$user->id/suspend") }}">
               {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-md-3">Suspension Reason</label>
                    <div class="col-md-4">
                        <textarea class="form-control" name="reason" required></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">&nbsp;</label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-warning">Suspend</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection