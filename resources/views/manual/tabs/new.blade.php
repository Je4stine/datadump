<table class="table table-bordered table-striped">
    <tr class="tabular">
        <th>ID</th>
        <th>Order Number</th>
        <th>Topic</th>
        <th>Pages</th>
        <th>Deadline</th>
        <th>Action</th>
    </tr>

    @foreach($orders as $order)
        <?php
        $deadline = \Carbon\Carbon::createFromTimestamp(strtotime($order->deadline));
        ?>
        <tr class="tabular">
            <td>{{ $order->id }}</td>
            <td>{{ $order->order_number  }}</td>
            <td>{{ $order->topic  }}</td>
            <td>{{ $order->pages }}</td>
            <td>{{ $deadline->diffForHumans() }}</td>
            <td>
                @if(Auth::user()->role =='admin')
                <a class="btn btn-info btn-sm load-page" href="{{ URL::to('order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                <a onclick="deleteItem('{{ url("manual/delete") }}',{{ $order->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>
            @else
                    <a onclick="loadBidForm({{ $order->id }})" href="#bid_modal" data-toggle="modal" class="btn btn-primary">Bid</a>
                    <a class="btn btn-info btn-sm load-page" href="{{ URL::to('writer/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>

                @endif
            </td>

        </tr>
        @endforeach
</table>
{{ $orders->links() }}
<div class="modal fade" id="bid_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    Bid Form
                    <a href="#" data-dismiss="modal" class="btn btn-danger pull-right">&times;</a>
                </div>
            </div>
            <div class="modal-body bid_details">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function loadBidForm(order_id){
        $.get("{{ url("manual/bid") }}/"+order_id,null,function(response){
            $(".bid_details").html(response);
        });
    }
</script>