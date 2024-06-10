 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">New Rate for <span style="color: green;">{{ $academic->name }}</span> </div>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-md-3">Hours</label>
                    <div class="col-md-4">
                        <input type="text" name="hours" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Amount(USD)</label>
                    <div class="col-md-4">
                        <input type="text" name="amount" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Label</label>
                    <div class="col-md-4">
                        <input type="text" name="label" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">&nbsp;</label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection