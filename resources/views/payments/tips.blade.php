 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Writer Tips</div>
        </div>
        <div class="panel-body">
            <table class="table">
                <tr>
                    <th>Writer#</th>
                    <th>Order#</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                @foreach($tips as $tip)
                    <?php $assign = $tip->assign ?>
                    <tr>
                        <td>{{ $assign->user_id }}</td>
                        <td>{{ $assign->order_id }}</td>
                        <td>${{ number_format($tip->amount*$tip->usd_rate,2) }}</td>
                        <td>{{ date('Y M d, H:i',strtotime($tip->create_time)) }}</td>
                        <td>
                            <a class="btn btn-success btn-xs" href="{{ URL::to("order/$assign->order_id/room/$assign->id") }}"><i class="fa fa-users"></i> Room</a>
                            <a class="btn btn-success btn-xs" href="{{ URL::to("order/$assign->order_id") }}"><i class="fa fa-eye"></i> Order</a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $tips->links() }}
        </div>
    </div>
@endsection