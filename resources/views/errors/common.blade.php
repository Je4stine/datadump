@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
 <div class="alert alert-danger">
     <h3><strong>A Unexpected  Error Has Occurred</strong></h3>

 </div>
    <div style="overflow: auto;">
        {!! $data !!}
    </div>
    <div class="alert alert-info">
        <h5><span class="fa fa-info"></span></h5>
        <p>Admin has been notified of this error and it will be taken care of, <br/>
            Sorry for any inconvenience caused
            <a class="btn btn-success" onclick="return window.location.reload();">Try Again</a>
        </p>
    </div>
@endsection