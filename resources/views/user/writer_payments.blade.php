@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Payments</div>
        </div>
        <div class="panel-body">
            @include('user.acc_payments')
            <h3>Order History</h3>
            <div class="row"></div>
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
                          @if(Auth::user()->role == 'admin' && $assign->rating == null)
                  <a class="btn btn-success btn-xs" href="{{ URL::to('stud/approve/'.''.$assign->order_id) }}"><i class="fa fa-thumbs-up"></i> Approve</a>
                                @endif
                            <a class="btn btn-success btn-xs" href="{{ URL::to("/order/$assign->order_id/room/$assign->id") }}"><i class="fa fa-users fa-fw"></i>Room</a>
                            <a class="btn btn-info btn-xs" href="{{ URL::to('/writer/order/'.''.$assign->order_id) }}"><i class="fa fa-eye fa-fw"></i> View</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $assigns->links() }}
        </div>
    </div>
    <script type="text/javascript">
        $('.rating').rating({displayOnly: true, step: 0.5});
    </script>
@endsection