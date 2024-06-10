 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">style Form</div>
        </div>
        <div class="panel-body">
            <form method="post" action="" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $style->id }}">
                <div class="form-group">
                    <label class="control-label col-md-3">Increment Type</label>
                    <div class="col-md-4">
                        <select required name="inc_type" class="form-control">
                            <option selected value="percent">Percentage (%)</option>
                            <option value="money">Money</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Amount</label>
                    <div class="col-md-4">
                        <input type="number" required value="{{ (int)$style->amount }}" name="amount" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Label</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ $style->label }}" name="label" class="form-control">
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