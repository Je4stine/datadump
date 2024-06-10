@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                Orders being worked on currently
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>Order</th>
                    <th>Pages</th>
                    <th>Subject</th>
                    <th>Time</th>
                    <th>Writer</th>
                    <th>Progress</th>
                    <th>Action</th>
                </tr>
                @foreach($assigns as $assigned)
                    @if($assigned->order)
                    <?php
                        $order = $assigned->order;
                        $order->percent = @$assigned->progress->percent;
                    $now = date('y-m-d H:i:s');
                    $deadline = $assigned->deadline;
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
                    <tr class="tabular">
                        <td>{{ $order->id }} - {{ $order->topic  }}</td>
                        <td>{{ $order->pages  }}</td>
                        <td>{{ $order->subject->label  }}</td>
                        <td><?php echo $remaining; $user = $assigned->user;  ?></td>
                        <td><a target="_blank" href="{{ URL::to("user/view/$user->role/$user->id") }}">{{ $assigned->user->name  }}</a></td>
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
                       <td>
                       <a class="btn btn-info btn-xs" href="{{ URL::to('/order/'.''.$order->id) }}"><i class="fa fa-eye"></i> View</a>
                        <a class="btn btn-success btn-xs" href="{{ URL::to("/order/$order->id/room/$assigned->id") }}"><i class="fa fa-users fa-fw"></i>Room</a>
                        </td>
                    </tr>

                <div class="well gridular well-lg col-md-12">
                    <div class="row">
                        <div class="col-sm-4"><strong>Order: </strong>#{{ $order->id }} - {{ $order->topic  }}</div>
                        <div class="col-sm-3"><strong>Writer: </strong>{{ $assigned->user->name  }}</div>
                        <div class="dropdown pull-right">
                            <a href="{{ URL::to("/order/$order->id/room/$assigned->id") }}"><i class="fa fa-users fa-fw"></i>Room</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><strong>Assigned on: </strong>{{ date('d M Y, h:i a',strtotime($assigned->created_at)) }}</div>
                        <div class="col-sm-5"><strong>Remaining Time: </strong><?php echo $remaining  ?></div>
                    </div>

                </div>
                @else
                <?php
                $assigned->delete();
                ?>
                @endif
            @endforeach

            </table>
            {{ $assigns->links()  }}
        </div>
    </div>
@endsection