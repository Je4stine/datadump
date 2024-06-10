
    <?php
    $user = Auth::user();
    ?>

            <a href="{{ URL::to('stud/new') }}" class="btn btn-success btn-xl">New Order</a>
            <table class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>Order ID</th>
                    <th>Topic</th>
                    <th>Status</th>
                    <th>Subject</th>
                    <th>Pages</th>
                    <th>WriterID</th>
                    <th>Cost</th>
                    <th>Deadline</th>
                    <th>Action</th>
                </tr>

                @foreach($orders as $order)
                    <?php
                    $deadline = \Carbon\Carbon::createFromTimestamp(strtotime($order->deadline));
                    ?>
                    <tr class="tabular">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->topic  }}</td>
                        <td>
                            @if($order->status==1)
                                {{ (int)$order->percent }}% Complete
                                <div class="progress" style="width: 200px !important;">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                                         aria-valuemin="0" aria-valuemax="100" style="width:{{ (int)$order->percent }}%;">
                                        {{ (int)$order->percent }}%
                                    </div>
                                </div>
                            @else
                                On Hold
                            @endif
                        </td>
                        <td>{{ $order->subject->label  }}</td>
                        <td>{{ $order->pages }}</td>
                        <td>
                            @if($assign = $order->assigns()->where('status','<=','4')->first())
                                {{ $assign->user_id }}
                            @else
                                --
                            @endif
                        </td>
                        <td>{{ $order->currency ? number_format($order->amount*$order->currency->usd_rate,2)." ".$order->currency->abbrev:'$'.number_format($order->amount,2) }}</td>
                        <td>{{ $deadline->diffForHumans() }}</td>
                        <th><a class="btn btn-info btn-sm load-page" href="{{ URL::to('stud/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a></th>
                    </tr>
                    <div class="row"></div>
                    <div class="well well-lg col-md-12 gridular" style="padding-top: 10px;padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-sm7"><strong>Order: </strong>#<a href="{{ URL::to('stud/order/'.''.$order->id) }}">{{ $order->id }} - {{ $order->topic  }}</a></div>
                            <div class="dropdown pull-right">
                                <a class="btn btn-info btn-sm" href="{{ URL::to('stud/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Status: </strong>
                                @if($order->status==1)
                                    {{ (int)$order->percent }}% Complete
                                    <div class="progress" style="width: 200px !important;">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                                             aria-valuemin="0" aria-valuemax="100" style="width:{{ (int)$order->percent }}%;">
                                            {{ (int)$order->percent }}%
                                        </div>
                                    </div>
                                @else
                                    On Hold
                                @endif
                            </div>
                            <div class="col-sm-3"><strong>WriterID: </strong>
                                @if($assign = $order->assigns()->where('status','<=','4')->first())
                                    {{ $assign->user_id }}
                                @else
                                    --
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Subject: </strong>{{ $order->subject->label  }}</div>
                            <div class="col-sm-3"><strong>Pages: </strong>{{ $order->pages  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Deadline: </strong>{{ $deadline->diffForHumans() }}</div>
                            <div class="col-sm-2"><strong>Cost: </strong>{{ $order->currency ? number_format($order->amount*$order->currency->usd_rate,2)." ".$order->currency->abbrev:'$'.number_format($order->amount,2) }}</div>
                        </div>

                    </div>

                @endforeach
            </table>
            {{ $orders->links()  }}

    @if($website->wallet == 1)
        {{--@include('client.e_wallet')--}}
    @endif
    @include('client.modals')