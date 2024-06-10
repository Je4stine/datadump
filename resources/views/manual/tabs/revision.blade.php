<table class="table table-bordered table-striped">
    <tr class="tabular">
        <th>ID</th>
        <th>Order Number</th>
        <th>Topic</th>
        <th>Subject</th>
        <th>Pages</th>
        <th>Deadline</th>
        <th>Writer</th>
        <th>Action</th>
    </tr>

    @foreach($orders as $assign)
        <?php
        $order = $assign->order;
        $deadline = \Carbon\Carbon::createFromTimestamp(strtotime($assign->deadline));
        ?>
        <tr class="tabular">
            <td>{{ $order->id }}</td>
            <td>{{ $order->order_number  }}</td>
            <td>{{ $order->topic  }}</td>
            <td>{{ $order->subject->label  }}</td>
            <td>{{ $order->pages }}</td>
            <td>{{ $deadline->diffForHumans() }}</td>
            <td>{{ $assign->user->name }}</td>
            <th>
                @if(Auth::user()->role == 'admin')
                    <a class="btn btn-success btn-sm load-page" href="{{ URL::to('order/'.''.$order->id.'/room/'.$assign->id) }}"><i class="fa fa-users"></i> Room</a>
                    <a class="btn btn-info btn-sm load-page" href="{{ URL::to('order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                @else
                    <a class="btn btn-success btn-sm load-page" href="{{ URL::to('writer/order/'.''.$order->id.'/room/'.$assign->id) }}"><i class="fa fa-users"></i> Room</a>

                @endif
            </th>
        </tr>
    @endforeach
</table>
{{ $orders->links() }}