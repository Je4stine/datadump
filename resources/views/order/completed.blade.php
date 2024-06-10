 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Closed Orders</div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>Order ID</th>
                    <th>Topic</th>
                    <th>Pages</th>
                    <th>Cost</th>
                    <th>Rating</th>
                    <th>Comments</th>
                    <th>On</th>
                    <th>Action</th>
                </tr>
                @foreach($assigns as $assign)
                    <?php
                        $order = $assign->order;
                        $last_update = \Carbon\Carbon::createFromTimestamp(strtotime($assign->updated_at));
                        if($last_update->diffInDays()>=14 && $order->status != 6 && $assign->status == 4){
                            $assign->rating = 4;
                            $assign->comments = "AUTO APPROVED";
                            $order->status = 6;
                            $assign->save();
                            $order->update();
                        }
                        if($assign->user){
                    ?>
                    <tr class="tabular">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->topic  }}</td>
                        <td>{{ $order->pages }}</td>
                        <td>${{ number_format($order->amount,2) }}</td>
                        <td>
                            <small>Writer: <strong>#{{ $assign->user->id }}</strong>-{{ $assign->user->name }}</small>
                            @if($assign->rating)
                                <input style="font-size: small;" id="input-2" value="{{ $assign->rating }}" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1">
                           @else

                                <i style="color: darkorange" class="fa fa-warning"></i> (Pending)
                                @if($order->user_id == Auth::user()->id)
                  <a class="btn btn-success btn-xs" href="{{ URL::to('stud/approve/'.''.$order->id) }}"><i class="fa fa-thumbs-up"></i> Approve</a>
                                @endif
                            @endif
                        </td>
                        <td>{!! nl2br($assign->comments) !!}</td>
                        <td>{{ date('d M Y, h:i a',strtotime($assign->updated_at)) }}</td>
                        <th>
                            <a class="btn btn-info btn-xs" href="{{ URL::to('order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                        <a class="btn btn-info btn-xs" href="{{ URL::to("/order/$order->id/room/$assign->id") }}"><i class="fa fa-users fa-fw"></i>Room</a>
                                  @if(Auth::user()->role == 'admin')
                  <a class="btn btn-success btn-xs" href="{{ URL::to('stud/approve/'.''.$order->id) }}"><i class="fa fa-thumbs-up"></i> Approve</a>
                                @endif
                        </th>
                    </tr>
                    <div class="row"></div>
                    <div class="well well-lg col-md-12 gridular" style="padding-top: 10px;padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-sm7"><strong>Order: </strong>#<a href="{{ URL::to('stud/order/'.''.$order->id) }}">{{ $order->id }} - {{ $order->topic  }}</a></div>
                            <div class="dropdown pull-right">
                                <a class="btn btn-info btn-xs" href="{{ URL::to('order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Rating: </strong> <small>Writer: <strong>
                                        #{{ $assign->user->id }}</strong>-{{ $assign->user->name }}</small>
                                @if($assign->rating)
                                <input style="font-size: small;" id="input-2" value="{{ $assign->rating }}" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1">
                                @else
                                    <i style="color: darkorange" class="fa fa-warning"></i> (Pending)
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Placed: </strong>{{ date('d M Y, h:i a',strtotime($order->created_at)) }}</div>
                        </div>
                    </div>
                        <?php 
                    }
                    ?>
                @endforeach
            </table>
            {{ $assigns->links()  }}
        </div>
    </div>
    </div>
    <script type="text/javascript">
        $('.rating').rating({displayOnly: true, step: 0.5});
    </script>
@endsection