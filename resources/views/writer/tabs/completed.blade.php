<table class="table">
    <thead>
    <tr>
        <th>Order</th>
        <th>Pages</th>
        <th>Deadline</th>
        <th>Status</th>
        <th>Amount</th>
        <th>Bonus</th>
        <th>Fine</th>
        <th>Rating(/5)</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $amount = 0;
    $fine = 0;
    $bonus = 0;
    ?>
    @foreach($assigns as $assign)
        <tr>
            <td>{{ $assign->order_id.' '.trim($assign->topic) }}</td>
            <td>{{ $assign->pages }}</td>
            <td>{{ date('d, M Y',strtotime($assign->deadline)) }}</td>
            <td>
                @if($assign->status == 4 && $assign->order_status == 6)
                    <i class="fa fa-check green"></i> Approved
                @elseif($assign->status == 3)
                    <i class="fa fa-lock" style="color:yellow"></i> Proofreading
                @elseif($assign->status == 4)
                    <i class="fa fa-warning"></i> Pending
                @elseif($assign->status == 7)
                    <i class="fa fa-times"></i> Cancelled
                @endif
            </td>
            <td>{{ @number_format($assign->amount,2) }}</td>
            <td>{{ @number_format($assign->bonus,2) }}</td>
            <td>{{ @number_format($assign->fines()->sum('amount'),2) }}</td>
            <td>
                @if($assign->rating && $assign->order_status == 6)
                    <input style="font-size: small;" id="input-2" value="{{ $assign->rating }}" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1">
                @else
                    --
                @endif
            </td>
            <td>
                <a class="btn btn-success btn-xs load-page" href="{{ URL::to("writer/order/$assign->order_id/room/$assign->id") }}"><i class="fa fa-users fa-fw"></i>Room</a>
                <a class="btn btn-info btn-xs load-page" href="{{ URL::to('/writer/order/'.''.$assign->order_id) }}"><i class="fa fa-eye fa-fw"></i> View</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $assigns->links() }}