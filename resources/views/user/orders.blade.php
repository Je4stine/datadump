 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')

    <div class="card section">
        <div class="card-header">
            <div class="panel-title">{{ $user->name.' Orders' }}<a class="btn btn-info btn-xs pull-right" href="{{ URL::to("user/view/client/$user->id") }}"><i class="fa fa-user"></i> Profile</a> </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>Order ID</th>
                    <th>Topic</th>
                    <th>Subject</th>
                    <th>Pages</th>
                    <th>Amount</th>
                    <th>Paid</th>
                    <th>On</th>
                    <th>Action</th>
                </tr>

                @foreach($orders as $order)
                    <?php
                    $deadline = Carbon\Carbon::createFromTimestamp(strtotime($order->created_at));
                    ?>
                    <tr class="tabular">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->topic  }}</td>
                        <td>{{ $order->subject->label  }}</td>
                        <td>{{ $order->pages }}</td>
                        <td>{{ number_format($order->amount,2) }}</td>
                        <td>
                            @if($order->paid)
                                <i style="color:green" class="fa fa-check"></i>
                            @else
                                <i style="color: red" class="fa fa-times"></i>
                            @endif
                        </td>
                        <td>{{ $deadline->diffForHumans() }}</td>
                        <th>
                            <a class="btn btn-info btn-xs" href="{{ URL::to('/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>

                        </th>
                    </tr>
                    <div class="row"></div>
                    <div class="well well-lg col-md-12 gridular" style="padding-top: 10px;padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-sm7"><strong>Order: </strong>#<a href="{{ URL::to('/order/'.''.$order->id) }}">{{ $order->id }} - {{ $order->topic  }}</a></div>
                            <div class="dropdown pull-right">
                                <a class="btn btn-info btn-xs" href="{{ URL::to('/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Subject: </strong>{{ $order->subject->label  }}</div>
                            <div class="col-sm-3"><strong>Pages: </strong>{{ $order->pages  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Placed: </strong>{{ $deadline->diffForHumans() }}</div>
                            <div class="col-sm-2"><strong>Amount: </strong>{{ number_format($order->amount,2) }}</div>
                            <div class="col-sm-2"><strong>Paid: </strong>@if($order->paid)
                                    <i style="color:green" class="fa fa-check"></i>
                                @else
                                    <i style="color: red" class="fa fa-times"></i>
                                @endif</div>
                        </div>

                    </div>

                @endforeach
                {{ $orders->links()  }}
            </table>
        </div>
    </div>
@endsection