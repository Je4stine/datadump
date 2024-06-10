 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">Fill in Your Profile Details</div>
        <div class="panel-body">

        </div>
    </div>
@endsection