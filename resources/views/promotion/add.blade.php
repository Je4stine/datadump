 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                Order Promotions
            </div>
        </div>
        <div class="panel-body">
            <form action="{{ URL::to("promotions/add") }}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-md-4">Discount Code</label>
                    <div class="col-md-4">
                        <input type="text" name="code" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Percentage (%)</label>
                    <div class="col-md-4">
                        <input type="number" name="percent" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Status</label>
                    <div class="col-md-4">
                        <select class="form-control" name="status">
                            <option value="1">Active</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">&nbsp</label>
                    <div class="col-md-4">
                       <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection