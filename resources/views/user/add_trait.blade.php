 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">{{  $user->name }} Trait</div>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{ URL::to("user/$user->id/add_trait") }}">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $trait->id }}">
                <div class="form-group">
                    <label class="control-label col-md-3">Trait</label>
                    <div class="col-md-4">
                        <input type="text" value="{{ $trait->trait }}" required name="trait" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Description</label>
                    <div class="col-md-4">
                        <textarea required name="description" class="form-control">{{ $trait->description }}</textarea>
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