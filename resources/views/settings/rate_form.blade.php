 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Order Rate Form</div>
        </div>
        <div class="panel-body">
            <form method="post" action="{{ URL::to('settings/rates') }}" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $urgency->id }}">
                <div class="form-group">
                    <label class="control-label col-md-3">Hours</label>
                    <div class="col-md-4">
                        <input type="number" required value="{{ $urgency->hours }}" name="hours" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Label</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ $urgency->label }}" name="label" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">High School</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ $urgency->high_school }}" name="high_school" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Undergraduate</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ $urgency->under_graduate }}" name="under_graduate" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Masters</label>
                    <div class="col-md-4">
                        <input type="text" value="{{ $urgency->masters }}" required name="masters" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Ph.D</label>
                    <div class="col-md-4">
                        <input type="text" required value="{{ $urgency->phd }}" name="phd" class="form-control">
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