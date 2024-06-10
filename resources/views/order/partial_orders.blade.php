@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">{{ $status }} Partial Orders</div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr class="">
                    <th>Order ID</th>
                    <th>Topic</th>
                    <th>Subject</th>
                    <th>Pages</th>
                    <th>Amt. Paid</th>
                    <th>Total</th>
                    <th>On</th>
                    <th>Action</th>
                </tr>

                @foreach($orders as $order)
                    <?php
                    ?>
                    <tr class="">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->topic  }}</td>
                        <td>{{ $order->subject->label  }}</td>
                        <td>{{ $order->pages }}</td>
                        <td>{{ $order->paypalTxns()->sum('amount') }}</td>
                        <td>{{ $order->currency ? number_format($order->amount*$order->currency->usd_rate,2)." ".$order->currency->abbrev:'$'.number_format($order->amount,2) }}</td>
                        <td>{{ date('d M Y, h:i a',strtotime($order->created_at)) }}</td>
                        <th>
                            <a class="btn btn-info btn-sm" href="{{ URL::to('order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                        </th>
                    </tr>

                @endforeach
            </table>
            {{ $orders->links()  }}
        </div>
    </div>
@endsection