@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Update Management</div>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" action="{{ URL::to('updates') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-md-3">Zip File</label>
                    <div class="col-md-4">
                       <input type="file" name="zip" required class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">&nbsp;</label>
                    <div class="col-md-4">
                       <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload & Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection