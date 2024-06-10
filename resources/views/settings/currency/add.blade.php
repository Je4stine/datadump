 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">currency Form</div>
        </div>
        <div class="panel-body">
            <form method="post" action="" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-md-3">Name</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ $currency->name }}" name="name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Abbrev</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ $currency->abbrev }}" name="abbrev" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">USD Rate</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ $currency->usd_rate }}" name="usd_rate" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">&nbsp;</label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection