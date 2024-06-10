<table class="table table-bordered table-striped">
    <tr class="tabular">
        <th>Order ID</th>
        <th>Topic</th>
        <th>Subject</th>
        <th>Pages</th>
        <th>Cost</th>
        <th>Deadline</th>
        <th>Action</th>
    </tr>

    @foreach($orders as $order)
        <?php
        $deadline = \Carbon\Carbon::createFromTimestamp(strtotime($order->deadline));

        ?>
        <tr class="tabular">
            <td>{{ $order->id }}</td>
            <td>{{ $order->topic  }}</td>
            <td>{{ $order->subject->label  }}</td>
            <td>{{ $order->pages }}</td>
            <td>
                @if($order->amount<1)
                    <strong style="color:green;">Pending</strong>
                @else
                    {{ $order->currency ? number_format($order->amount*$order->currency->usd_rate,2)." ".$order->currency->abbrev:'$'.number_format($order->amount,2) }}
                @endif
            </td>
            <td>{{ $deadline->diffForHumans() }}</td>
            <th>
                <a class="btn btn-info btn-sm load-page" href="{{ URL::to('stud/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                @if($order->amount>0)
                    <a class="btn btn-success btn-sm load-page" href="{{ URL::to('stud/pay/'.''.$order->id) }}"><i class="fa fa-paypal"></i> Pay</a>
                @endif
                <a onclick="deleteItem('{{ URL::to('stud/delete-order') }}',{{ $order->id }})" class="btn btn-danger btn-sm" href="#"><i class="fa fa-trash"></i> Discard</a>
            </th>
        </tr>
        <div class="row"></div>
        <div class="well well-lg col-md-12 gridular" style="padding-top: 10px;padding-bottom: 10px;">
            <div class="row">
                <div class="col-sm7"><strong>Order: </strong>#<a href="{{ URL::to('stud/order/'.''.$order->id) }}">{{ $order->id }} - {{ $order->topic  }}</a></div>
                <div class="dropdown pull-right">
                    <a class="btn btn-info btn-sm" href="{{ URL::to('stud/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                    @if($order->amount>0)
                        <a class="btn btn-success btn-sm" href="{{ URL::to('stud/pay/'.''.$order->id) }}"><i class="fa fa-paypal"></i> Pay</a>
                    @endif
                    <a onclick="deleteItem('{{ URL::to('stud/delete-order') }}',{{ $order->id }})" class="btn btn-danger btn-sm" href="#"><i class="fa fa-trash"></i> Discard</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4"><strong>Subject: </strong>{{ $order->subject->label  }}</div>
                <div class="col-sm-3"><strong>Pages: </strong>{{ $order->pages  }}</div>
            </div>
            <div class="row">
                <div class="col-sm-4"><strong>Deadline: </strong>{{ $deadline->diffForHumans() }}</div>
                <div class="col-sm-2"><strong>Amount: </strong> @if($order->amount<1)
                        <strong style="color:green;">Pending</strong>
                    @else
                        {{ $order->currency ? number_format($order->amount*$order->currency->usd_rate,2)." ".$order->currency->abbrev:'$'.number_format($order->amount,2) }}
                    @endif
                </div>
            </div>

        </div>

    @endforeach
    {{ $orders->links()  }}
</table>