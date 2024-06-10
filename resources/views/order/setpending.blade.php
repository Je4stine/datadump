 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section" xmlns="http://www.w3.org/1999/html">
        <div class="card-header">
            <div class="panel-title">Set Pending Order <strong>#{{ $order->id }} - {{ $order->topic }}</strong></div>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                <strong><i class="fa fa-info fa-2x"></i> Set Order to Pending</strong><br/>
                Set this order to pending. This means order is still under checks like plagiarism, client confirmation etc. The order can then be confirmed in <br/>
                <a target="_blank" href="{{ URL::to('/order/pending') }}">Pending Orders</a>
               <form class="form-horizontal" method="post" action="">
                   {{ csrf_field() }}
                   <input name="_method" value="put" type="hidden">
                   <br/>
                    <button class="btn btn-success" type="submit">Set Pending</button>
               </form>
            </div>
        </div>
    </div>
@endsection