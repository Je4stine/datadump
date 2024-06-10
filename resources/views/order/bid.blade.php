 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
    $decrease_percent = $bid->user->writerCategory->deadline;
    if($order->is_manual){
        $c_deadline = \Carbon\Carbon::createFromTimestamp(strtotime($order->deadline));
        $decrease_percent = 100-$decrease_percent;
        $decrease_percent = $decrease_percent/100;
        $new_hours = $c_deadline->diffInHours()*$decrease_percent;
        $b_deadline = \Carbon\Carbon::now()->addHours($new_hours);
    }
    else if($order->bidmapper->deadline == '0000-00-00 00:00:00'){
        $c_deadline = \Carbon\Carbon::createFromTimestamp(strtotime($order->deadline));
        $decrease_percent = 100-$decrease_percent;
        $decrease_percent = $decrease_percent/100;
        $new_hours = $c_deadline->diffInHours()*$decrease_percent;
        $b_deadline = \Carbon\Carbon::now()->addHours($new_hours);
    }else{
        $b_deadline = $order->bidmapper->deadline;
    }
            ?>
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                View Bid:<strong>#{{ $bid->id }}</strong> On Order:<strong>#{{ $order->id.'-'.$order->topic }}</strong> Order Amount: <strong>{{  number_format($order->amount,2) }}</strong> Deadline <strong>{{ date('d M Y, h:i a',strtotime($order->deadline)) }}</strong>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-5">
                <table class="table table-condensed table-bordered">
                    <tr>
                        <th>Writer</th>
                        <td>{{ $bid->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>{{ $bid->amount }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Message</th>
                    </tr>
                    <tr>
                        <td colspan="2">{{ $bid->message }}</td>
                    </tr>
                    <tr>
                        <th>On</th>
                        <td>{{ date('d M Y, h:i a',strtotime($bid->created_at)) }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-5">
                <div class="card section">
                    <div class="card-header">Assign to Writer</div>
                    <div class="panel-body">
                        @if($order->status==0 || $order->status == 10)
                        <form class="form-horizontal" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-md-2">Amount</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="amount" value="{{ $bid->amount }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">Bonus</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="bonus" value="0.00">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">Deadline</label>
                                <div class="col-md-8">
                                    <input type="text" name="deadline" value="{{ $b_deadline }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">&nbsp;</label>
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-thumbs-up"></i> Assign</button>
                                </div>
                            </div>
                        </form>
                            @else
                        <div class="alert alert-info">
                            This order has been assigned to another writer
                        </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
  @endsection