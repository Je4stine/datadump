<table class="table table-bordered">
    <tr>
        <th>Order#</th>
        <th>Message</th>
        <th>Amount</th>
        <th>Order#</th>
        <th>On</th>
        <th>Action</th>
    </tr>

    @foreach($bids as $bid)
        <?php
        if(!$bid->order || $bid->order->bidMapper == null){
            $bid->delete();
        }else{
        $bidmapper = $bid->order->bidMapper;
        $order = $bid->order;
        ?>
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $bid->message }}</td>
            <td>{{ $bid->amount }}</td>
            <td><a href="{{ "order/$bid->order_id" }}">{{ $bid->order->topic }}</a> </td>
            <td>{{ date('d M Y, h:i a',strtotime($bid->created_at)) }}</td>
            <th>
                <a class="btn btn-success btn-xs" href="{{ URL::to('/writer/bid/'.''.$bidmapper->id) }}"><i class="fa fa-edit"></i> Edit Bid</a>
                <a class="btn btn-info btn-xs" href="{{ URL::to('/writer/order/'.''.$order->id) }}"><i class="fa fa-eye fa-fw"></i> View</a>
                <a class="btn btn-danger btn-xs" onclick="deleteItem('{{ URL::to('/writer/bid/delete/')}}',{{ $bid->id }})"><i class="fa fa-trash fa-fw"></i> Remove</a>
            </th>
        </tr>
        <?php
        }
        ?>
    @endforeach
</table>
{{ $bids->links() }}