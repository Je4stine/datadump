<table class="table table-bordered table-striped">
    <tr class="tabular">
        <th>Order</th>
        <th>Instructions</th>
        <th>Pages</th>
        <th>CPP</th>
        <th>Subject</th>
        <th>Time</th>
        <th>Action</th>
    </tr>
    @foreach($revision as $assigned)
        <?php
        $now = date('y-m-d H:i:s');
        $deadline = $assigned->deadline;
        $today = date_create($now);
        $end = date_create($deadline);
        $order = $assigned->order()->get()[0];
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
            <td>{{ substr($order->instructions,0,100)  }}..</td>
            <td>{{ $order->pages  }}</td>
            <td>{{ number_format($assigned->amount/$order->pages,2) }}</td>
            <td>{{ $order->subject->label  }}</td>
            <td><?php echo $remaining  ?></td>
            <td>
                <a class="btn btn-success btn-xs load-page" href="{{ URL::to("writer/order/$order->id/room/$assigned->id") }}"><i class="fa fa-users fa-fw"></i>Room</a>
                <a class="btn btn-info btn-xs load-page" href="{{ URL::to('/writer/order/'.''.$order->id) }}"><i class="fa fa-eye fa-fw"></i> View</a>
            </td>
        </tr>
        <div class="well gridular well-lg col-md-11">
            <div class="row">
                <div class="col-sm-7"><strong>Order: </strong>#{{ $order->id }} - {{ $order->topic  }}</div>
                <div class="dropdown pull-right">
                    <a class="btn btn-success btn-xs load-page" href="{{ URL::to("writer/order/$order->id/room/$assigned->id") }}"><i class="fa fa-users fa-fw"></i>Room</a>
                    <a class="btn btn-info btn-xs load-page" href="{{ URL::to('/writer/order/'.''.$order->id) }}"><i class="fa fa-eye fa-fw"></i> View</a>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-4"><strong>Subject: </strong>{{ $order->subject->label  }}</div>
                <div class="col-sm-3"><strong>Pages: </strong>{{ $order->pages  }}</div>
            </div>
            <div class="row">
                <div class="col-sm-3"><strong>CPP: </strong>{{ number_format($assigned->amount/$order->pages,2) }}</div>
                <div class="col-sm-5"><strong>Remaining Time: </strong><?php echo $remaining  ?></div>
            </div>

        </div>
    @endforeach
</table>
{{ $revision->links() }}