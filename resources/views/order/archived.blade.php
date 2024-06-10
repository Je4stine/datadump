@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')

    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Archived Orders</div>
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
                    <th>Created</th>
                    <th>Action</th>
                </tr>

                @foreach($orders as $order)

                    <tr class="sdfgh">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->topic  }}</td>
                        <td>{{ $order->subject->label  }}</td>
                        <td>{{ $order->pages }}</td>
                        <td>
                            @if($order->amount<1)
                                <strong style="color:green;">Pending</strong>
                            @else
                                {{ number_format($order->amount,2) }}
                            @endif
                        </td>
                        <td>
                            @if($order->paid)
                                <i style="color:green" class="fa fa-check"></i>
                            @else
                                <i style="color: red" class="fa fa-times"></i>
                            @endif
                        </td>
                        <td>{{ $order->created_at }}</td>
                        <th>
                            <a class="btn btn-info btn-xs" href="{{ URL::to('/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                            @if(Auth::user()->isAllowedTo('delete_data'))
                                <a onclick="return confirm('Delete order {{ $order->id }} ?\n All items and info associated with order will be permanently deleted!')" href="{{ URL::to("order/delete/$order->id") }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>
                            @endif
                            <a onclick="return confirm('Are you sure?')" href="{{ URL::to("order/archived/$order->id/restore") }}" class="btn btn-xs btn-success"><i class="zmdi zmdi-refresh"></i> Restore</a>

                        </th>
                    </tr>

                @endforeach
                {{ $orders->links()  }}
            </table>
        </div>
    </div>
@endsection