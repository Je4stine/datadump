 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
               Template Chooser
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-5">
                <form action="" method="post" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <select class="form-control" name="template_id">
                        <option value="">New Template</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->action }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-success" type="submit"><i class="fa fa-check"></i>Proceed</button>
                </form>
            </div>
        </div>
    </div>
@endsection
