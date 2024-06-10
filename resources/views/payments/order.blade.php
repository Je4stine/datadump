 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Order Payments</div>
        </div>
        <div class="panel-body">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Reference</th>
                    <th>Order#</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                @foreach($txns as $txn)
                <?php
                    if(!$txn->order){
                        $txn->delete();
                    }
                 ?>
                    <tr>
                        <td>{{ $txn->id }}</td>
                        <td>{{ $txn->txn_id }}</td>
                        <td>{{ $txn->order_id }}</td>
                        <?php
                    $rate = $txn->usd_rate ? $txn->usd_rate:1;
                                ?>
                        <td>${{
                            number_format($txn->amount*$rate,2)
                         }}</td>
                        <td>{{ $txn->state }}</td>
                        <td>{{ date('Y M d, H:i',strtotime($txn->create_time)) }}</td>
                        <td>
                            <a href="{{ URL::to('/order/'.''.$txn->order->id) }}" class="btn btn-info btn-xs">View Order</a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $txns->links() }}
        </div>
    </div>
@endsection