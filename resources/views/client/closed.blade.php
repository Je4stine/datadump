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
                    <th>On</th>
                    <th>Action</th>
                </tr>
                @foreach($orders as $order)
                    <?php
                        $assign = @$order->assigns()->where('status','=',4)->get()[0];
                    ?>
                    <tr class="tabular">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->topic  }}</td>
                        <td>{{ $order->pages }}</td>
                        <td>${{ number_format($order->amount,2) }}</td>
                        <td>
                            <small>Writer: <strong>#{{ @$assign->user->id }}</strong>-{{ @$assign->user->name }}</small>
                            <input style="font-size: small;" id="input-2" value="{{ $assign->rating }}" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1">
                        </td>
                        <td>{{ date('d M Y, h:i a',strtotime($order->created_at)) }}</td>
                        <th>
                            <a class="btn btn-info btn-xs" href="{{ URL::to('stud/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                        </th>
                    </tr>
                    <div class="row"></div>
                    <div class="well well-lg col-md-12 gridular" style="padding-top: 10px;padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-sm7"><strong>Order: </strong>#<a href="{{ URL::to('stud/order/'.''.$order->id) }}">{{ $order->id }} - {{ $order->topic  }}</a></div>
                            <div class="dropdown pull-right">
                                <a class="btn btn-info btn-xs" href="{{ URL::to('stud/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Rating: </strong> <small>Writer: <strong>#{{ $assign->user->id }}</strong>-{{ $assign->user->name }}</small>
                                <input style="font-size: small;" id="input-2" value="{{ $assign->rating }}" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Placed: </strong>{{ date('d M Y, h:i a',strtotime($order->created_at)) }}</div>
                        </div>
                    </div>

                @endforeach
            </table>
            {{ $orders->links()  }}
        </div>
    </div>
    <script type="text/javascript">
        $('.rating').rating({displayOnly: true, step: 0.5});
    </script>
@endsection