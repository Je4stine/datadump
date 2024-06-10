 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
    $now = date('y-m-d H:i:s');
    $deadline = $assign->deadline;
    $today = date_create($now);
    $end = date_create($deadline);
    $diff = date_diff($today,$end);
    if($today>$end){
        $remaining = 'Late By: <br/><span style="color: red;">'.$diff->d.' Day(s) '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
    }else{

        $remaining = '<span style="color: darkgreen;">'.$diff->d.' Day(s) '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
    }
            ?>
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
            Adjust Details for Order <strong>#{{ $order->id.' - '.$order->topic }}</strong> and Writer <strong>#{{ $assign->user->id.'-'.$assign->user->name }}</strong>            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-5">
                <table class="table table-bordered">
                    <tr>
                        <th>Writer</th>
                        <td><a href=""> {{ $assign->user->name }}</a></td>
                    </tr>
                    <tr>
                        <th>Assigned On</th>
                        <td>{{ date('d M Y, h:i a',strtotime($assign->created_at)) }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td><a href=""> {{ $assign->amount }}</a></td>
                    </tr>
                    <tr>
                        <th>Bonus</th>
                        <td><a href=""> {{ $assign->bonus }}</a></td>
                    </tr>
                    <tr>
                        <th>Remaining Time</th>
                        <td><?php echo $remaining ?></td>
                    </tr>
                    <tr>
                        <th>Order</th>
                        <td><a target="_blank" href="{{ URL::to("/order/$order->id") }}">{{ $order->topic }}</a></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-5">
                <div class="card section">
                    <div class="card-header">Adjustment Details</div>
                    <div class="panel-body">
                            <form class="form-horizontal" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="control-label col-md-2">Amount</label>
                                    <div class="col-md-8">
                                        <input class="form-control" type="text" name="amount" value="{{ $assign->amount }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Bonus</label>
                                    <div class="col-md-8">
                                        <input class="form-control" type="text" name="bonus" value="{{ $assign->bonus }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Deadline</label>
                                    <div class="col-md-8">
                                        <input type="text" name="deadline" value="{{ $assign->deadline }}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">&nbsp;</label>
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-adjust"></i> Adjust</button>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection