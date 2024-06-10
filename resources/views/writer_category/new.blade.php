 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Writer category Form</div>
        </div>
        <div class="panel-body">
            <form method="post" action="" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $writer_category->id }}">
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
                        <input type="number" required value="{{ (int)$writer_category->amount }}" name="amount" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Default CPP</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ (int)$writer_category->cpp }}" name="cpp" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Adjust Deadline by(%)</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ (int)$writer_category->deadline }}" name="dline" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Late Fine(%)</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ $writer_category->fine_percent }}" name="fine_percent" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Name</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ $writer_category->name }}" name="name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Description</label>
                    <div class="col-md-4">
                        <textarea name="description" class="form-control" required>{{ $writer_category->description }}</textarea>
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