 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Orders open for Bidding </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>Order ID</th>
                    <th>Topic</th>
                    <th>Subject</th>
                    <th>Pages</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Bid Amt</th>
                    <th>Bids</th>
                    <th>Client Deadline</th>
                    <th>Bid Deadline</th>
                    <th>Action</th>
                </tr>

                @foreach($bidmappers as $bidmapper)
                    <?php
                    $order = $bidmapper->order;
                    $now = date('y-m-d H:i:s');
                    $deadline = $order->deadline;
                    $today = date_create($now);
                    $end = date_create($deadline);
                    $diff = date_diff($today,$end);
                    if($today>$end){
                        if($diff->d){
                            $remaining = '<span style="color: red;">Late: '.$diff->d.' Day(s) '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
                        }else{
                            $remaining = '<span style="color: red;">Late: '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
                        }
                    }else{

                        if($diff->d){
                            $remaining = '<span style="color: darkgreen;">'.$diff->d.' Day(s) '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
                        }else{
                            $remaining = '<span style="color: darkgreen;">'.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
                        }
                    }

                    ?>
                    <?php
                        $order = $bidmapper->order;
                        $b_deadline = \Carbon\Carbon::createFromTimestamp(strtotime($bidmapper->deadline));

                    $c_deadline = \Carbon\Carbon::createFromTimestamp(strtotime($order->deadline));


                    ?>
                    <tr class="tabular">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->topic  }}</td>
                        <td>{{ $order->subject->label  }}</td>
                        <td>{{ $order->pages }}</td>
                        <td>{{ number_format($order->amount,2) }}</td>
                        <td>@if($order->paid)
                                <i style="color:green" class="fa fa-check"></i>
                            @else
                                <i style="color: red" class="fa fa-times"></i>
                            @endif</td>
                        <td>
                            @if($bidmapper->bid_amount)
                                {{ number_format($bidmapper->bid_amount,2) }}
                            @else
                                Default
                            @endif
                        </td>
                        <td>{{ count($order->bids()->get()) }}</td>
                        <td>{!! $remaining !!}</td>
                        <td>
                            @if($bidmapper->deadline != '0000-00-00 00:00:00')
                                {{ $b_deadline->diffForHumans() }}
                                @else
                                Default
                                @endif

                        </td>
                        <th>
                            <a class="btn btn-info btn-xs load-page" href="{{ URL::to('/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                            <a class="btn btn-success btn-xs" href="{{ URL::to("order/activate/$bidmapper->id") }}"><i class="fa fa-edit"></i> Edit Bid</a>
                            @if(Auth::user()->isAllowedTo('enable_take'))
                                @if($bidmapper->allow_take)
                                <a class="btn btn-warning btn-xs" href="{{ URL::to("order/enable_take/$bidmapper->id") }}"><i class="fa fa-thumbs-up"></i> Edit Take</a>
                                @else
                                <a class="btn btn-warning btn-xs" href="{{ URL::to("order/enable_take/$bidmapper->id") }}"><i class="fa fa-thumbs-up"></i> Enable Take</a>
                                    @endif
                            @endif
                            <a onclick="return confirm('Are you sure?');" class="btn btn-danger btn-xs" href="{{ URL::to("order/deactivate/$bidmapper->id") }}"><i class="fa fa-times"></i> Deactivate</a>
                            @if(Auth::user()->isAllowedTo('delete_data'))
                                <a onclick="return confirm('Delete order {{ $order->id }} ?\n All items and info associated with order will be permanently deleted!')" href="{{ URL::to("order/delete/$order->id") }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>
                            @endif
                            <a onclick="return confirm('Are you sure?')" class="btn btn-info btn-xs" href="{{ URL::to('/order/archived/'.''.$order->id) }}"><i class="zmdi zmdi-archive"></i> Archive</a>

                        </th>
                    </tr>
                    <div class="row"></div>
                    <div class="well well-lg col-md-12 gridular" style="padding-top: 10px;padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-sm7"><strong>Order: </strong>#<a href="{{ URL::to('/order/'.''.$order->id) }}">{{ $order->id }} - {{ $order->topic  }}</a></div>
                            <div class="dropdown pull-right">
                                <a class="btn btn-info btn-sm" href="{{ URL::to('/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                                <a class="btn btn-success btn-xs" href="{{ URL::to("order/activate/$bidmapper->id") }}"><i class="fa fa-edit"></i> Edit Bid</a>

                                <a onclick="return confirm('Are you sure?');" class="btn btn-danger btn-xs" href="{{ URL::to("order/deactivate/$bidmapper->id") }}"><i class="fa fa-times"></i> Deactivate</a>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Subject: </strong>{{ $order->subject->label  }}</div>
                            <div class="col-sm-3"><strong>Pages: </strong>{{ $order->pages  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Client Deadline: </strong>{{ date('d M Y, h:i a',strtotime($order->created_at)) }}</div>
                            <div class="col-sm-2"><strong>Bid Deadline: </strong> @if($bidmapper->deadline != '0000-00-00 00:00:00')
                                    {{ $b_deadline->diffForHumans() }}
                                @else
                                    Default
                                @endif</div>
                            <div class="col-sm-2"><strong>Bids: </strong>{{ count($order->bids()->get()) }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Paid:</strong>
                                @if($order->paid)
                                    <i style="color:green" class="fa fa-check"></i>
                                @else
                                    <i style="color: red" class="fa fa-times"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                {{ $bidmappers->links()  }}
            </table>
        </div>
    </div>
@endsection