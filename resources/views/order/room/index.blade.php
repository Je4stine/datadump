@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
    $now = date('y-m-d H:i:s');
    $deadline = $assign->deadline;
    $today = date_create($now);
    $end = date_create($deadline);
    $diff = date_diff($today,$end);
    if($today>$end){
        $remaining = 'Late By: <br/><span style="color: red;">'.$diff->d.' Day(s) '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
    }else{

        $remaining = '<span style="color: darkgreen;">'.$diff->d.' Day(s) '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
    }
    ?>
<div class="card section">
    <div class="card-header">
        <div class="card-title">Room for Order <strong>#{{ $order->id.' - '.$order->topic }}</strong> and Writer <strong>#{{ $assign->user->id.'-'.$assign->user->name }}</strong></div>
        <ul class="actions">
            <li class="dropdown">
                <a href="" data-toggle="dropdown" aria-expanded="false">
                    <i class="zmdi zmdi-more-vert"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    @if($order->is_manual)
                    <li><a href="{{ URL::to("/stud/approve/$order->id") }}"><i class="fa fa-thumbs-up"></i> Approve</a> </li>
                    @else
                        <li><a href="{{ URL::to("/order/$order->id/confirm/$assign->id") }}"><i class="fa fa-thumbs-up"></i> Confirm</a> </li>

                    @endif
                        <li><a href="{{ URL::to("/order/$order->id/revision/$assign->id") }}"><i class="fa fa-adjust"></i> Request Revision</a></li>

                        <li><a href="{{ URL::to("/order/$order->id/setpending/$assign->id") }}"><i class="fa fa-thumbs-up"></i> Set Pending</a> </li>
                    @if(Auth::user()->isAllowedTo('fine_writer')) <li><a href="{{ URL::to("/order/fine/$assign->id") }}"><i class="fa fa-money"></i> Fine</a></li>@endif
                    <li><a href="{{ URL::to("/order/$order->id/extend/$assign->id") }}"><i class="fa fa-adjust"></i> Adjust</a> </li>
                    <li><a href="{{ URL::to("/order/$order->id/cancel/$assign->id") }}"><i style="color: red;" class="fa fa-times"></i> Cancel</a> </li>

                </ul>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div role="tabpanel">
            <ul class="tab-nav" role="tablist">
                <li class="active"><a href="#room_payments" aria-controls="room_payments" role="tab" data-toggle="tab">Home</a></li>
                <li><a href="#order_details" aria-controls="order_details" role="tab" data-toggle="tab">Order</a></li>
                <li><a href="#room_messages" aria-controls="room_messages" role="tab" data-toggle="tab">Messages</a></li>
                <li><a href="#room_files" aria-controls="room_files" role="tab" data-toggle="tab">Files</a></li>
                @if($order->is_manual == 0)
                <li><a href="#room_history" aria-controls="room_history" role="tab" data-toggle="tab">Client History</a></li>
                    @endif

            </ul>

            <div class="tab-content">
                @include("order.room.tabs.order")
                @include("order.room.tabs.messages")
                @include("order.room.tabs.payments")
                @include("order.room.tabs.files")
                @include("order.room.tabs.history")
            </div>
        </div>
    </div>
</div>
    @endsection