 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                Orders Under Revision
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr class="">
                    <th>Order</th>
                    <th>Pages</th>
                    <th>Subject</th>
                    <th>On</th>
                    <th>Writer</th>
                    <th>Deadline</th>
                    <th>Action</th>
                </tr>

                @foreach($assigns as $assigned)
                    <?php
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
                     $order = $assigned->order;

                    ?>
                    <tr class="tadfbular">
                        <td>{{ $order->id }} - {{ $order->topic  }}</td>
                        <td>{{ $order->pages  }}</td>
                        <td>{{ $order->subject->label  }}</td>
                        <td>{{ date('d M Y, h:i a',strtotime($assigned->updated_at)) }}</td>
                        <td><a target="_blank" href="{{ URL::to("order/writer".'/'.$assigned->user->id) }}">{{ $assigned->user->name  }}</a></td>
                        <td>{!! $remaining !!} </td>
                        <td><a href="{{ URL::to("/order/$order->id/room/$assigned->id") }}"><i class="fa fa-users fa-fw"></i>Room</a></td>
                    </tr>
            @endforeach
            </table>
            {!! $assigns->links() !!}
        </div>
    </div>
@endsection