 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Pending Orders</div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>Order</th>
                    <th>Pages</th>
                    <th>Subject</th>
                    <th>On</th>
                    <th>Writer</th>
                    <th>Action</th>
                </tr>

                @foreach($assigns as $assign)
                    <?php
                        $order = $assign->order;
                    $now = date('y-m-d H:i:s');
                    ?>
                    <tr class="tabular">
                        <td>{{ $order->id }} - {{ $order->topic  }}</td>
                        <td>{{ $order->pages  }}</td>
                        <td>{{ @$order->subject->label  }}</td>
                        <td>{{ date('d M Y, h:i a',strtotime($assign->updated_at)) }}</td>
                        <td><a target="_blank" href="{{ URL::to("order/writer".'/'.$assign->user->id) }}">{{ $assign->user->name  }}</a></td>
                        <td><a class="btn btn-info btn-sm" href="{{ URL::to("/order/$order->id/room/$assign->id") }}"><i class="fa fa-users fa-fw"></i>Room</a></td>
                    </tr>
                <div class="well gridular well-lg col-md-12">
                    <div class="row">
                        <div class="col-sm-4"><strong>Order: </strong>#{{ $order->id }} - {{ $order->topic  }}</div>
                        <div class="col-sm-3"><strong>Pages: </strong>{{ $order->pages  }}</div>
                        <div class="dropdown pull-right">
                            <a href="{{ URL::to("/order/$order->id/room/$assign->id") }}"><i class="fa fa-users fa-fw"></i>Room</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><strong>Completed on: </strong>{{ date('d M Y, h:i a',strtotime($assign->updated_at)) }}</div>
                        <div class="col-sm-3"><strong>Writer: </strong><a target="_blank" href="{{ URL::to("order/writer".'/'.$assign->user->id) }}">{{ $assign->user->name  }}</a></div>
                    </div>

                </div>
            @endforeach
            </table>
            {{ $assigns->links()  }}
        </div>
    </div>
@endsection