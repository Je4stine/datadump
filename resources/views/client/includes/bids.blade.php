<div id="o_bids" class="tab-pane fade">
    <h3>Bids</h3>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Amount</th>
            <th>Message</th>
            <th>Writer</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->bids as $bid)
            <tr>
                <td>{{ $bid->id }}</td>
                <td>{{ $bid->amount }}</td>
                <td>{{ substr($bid->message,0,25) }}...</td>
                <td>{{ $bid->user->name }}</td>
                <td><a class="btn btn-info" href="{{ URL::to('/order/'.$order->id.'/bid/'.$bid->id) }}"><i class="fa fa-eye"></i> View</a> </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>