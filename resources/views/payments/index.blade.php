 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Payment Overview</div>
        </div>
        <div class="panel-body">

            </div>
        </div>
@endsection