<div role="tabpanel" class="tab-pane" id="room_history">
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Topic</th>
            <th>Pages</th>
            <th>Action</th>
        </tr>
        <?php
        $user = Auth::user();
        $related_orders = $order->user->orders()->join('assigns','orders.id','=','assigns.order_id')
            ->where([
                ['assigns.user_id','=',$assign->user->id]
            ])
            ->groupBy('orders.id')
            ->select('orders.*','assigns.id as assign_id')
            ->get();
        ?>
        @foreach($related_orders as $rorder)
            <tr>
                <td>{{ $rorder->id }}</td>
                <td>{{ $rorder->topic }}</td>
                <td>{{ $rorder->pages }}</td>
                <td>
                    <a class="btn btn-success btn-xs" href="{{ URL::to("order/$rorder->id/room/$rorder->assign_id") }}"><i class="fa fa-users fa-fw"></i>Room</a>

                </td>
            </tr>
        @endforeach
    </table>
</div>