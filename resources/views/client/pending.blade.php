 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Pending Orders</div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>Order ID</th>
                    <th>Topic</th>
                    <th>Subject</th>
                    <th>Pages</th>
                    <th>Cost</th>
                    <th>On</th>
                    <th>Action</th>
                </tr>

                @foreach($orders as $order)
                    <?php
                    ?>
                    <tr class="tabular">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->topic  }}</td>
                        <td>{{ $order->subject->label  }}</td>
                        <td>{{ $order->pages }}</td>
                        <td>{{ $order->currency ? number_format($order->amount*$order->currency->usd_rate,2)." ".$order->currency->abbrev:'$'.number_format($order->amount,2) }}</td>
                        <td>{{ date('d M Y, h:i a',strtotime($order->created_at)) }}</td>
                        <th>
                            <a class="btn btn-info btn-sm" href="{{ URL::to('stud/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                        </th>
                    </tr>
                    <div class="row"></div>
                    <div class="well well-lg col-md-12 gridular" style="padding-top: 10px;padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-sm7"><strong>Order: </strong>#<a href="{{ URL::to('stud/order/'.''.$order->id) }}">{{ $order->id }} - {{ $order->topic  }}</a></div>
                            <div class="dropdown pull-right">
                                <a class="btn btn-info btn-sm" href="{{ URL::to('stud/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Subject: </strong>{{ $order->subject  }}</div>
                            <div class="col-sm-3"><strong>Pages: </strong>{{ $order->pages  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Placed: </strong>{{ date('d M Y, h:i a',strtotime($order->created_at)) }}</div>
                            <div class="col-sm-2"><strong>Cost: </strong>${{ number_format($order->amount,2) }}</div>
                        </div>

                    </div>

            @endforeach
            {{ $orders->links()  }}
            </table>
        </div>
    </div>
@endsection