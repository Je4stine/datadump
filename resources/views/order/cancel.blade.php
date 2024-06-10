 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header"><i class="fa fa-times fa-2x"></i> Cancel Order for Writer <strong>{{ $assign->user->name }}</strong> Order<strong>#{{ $order->id.' '.$order->topic }}</strong></div>
        <div class="panel-body">
            <form method="post" action="{{ URL::to("order/$order->id/cancel/$assign->id") }}" class="col-md-offset-1 form-horizontal">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="input-2" class="control-label col-md-3">Rate Writer</label>
                    <div class="col-md-5">
                        <input id="input-2" value="" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">
                        Fine Amount
                    </label>
                    <div class="col-md-4">
                        <input type="text" name="amount" value="0.00" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">
                        Order Cancel Reason
                    </label>
                    <div class="col-md-6">
                        <textarea class="form-control" rows="10" name="comments"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">
                        &nbsp;
                    </label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> Cancel Order</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection