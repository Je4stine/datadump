
<div id="client_history" class="tab-pane fade">
    <div class="col-md-8">
        <div class="card section">
            <div class="card-header">
                <div class="panel-title">Client History <a onclick=" $('#revbody22').slideToggle('slow'); return false;" class="pull-right" style="text-decoration: none;"><i class="fa fa-toggle-down fa-2x"></i> </a> </div>
            </div>
            <div class="panel-body" id="revbody22" style="">
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>Topic</th>
                        <th>Pages</th>
                        <th>Action</th>
                    </tr>
                    <?php
                        $user = Auth::user();
                        $related_orders = $user->assigns()->join('orders','orders.id','=','assigns.order_id')
                            ->where([
                                    ['orders.user_id','=',$order->user_id],
                                    ['assigns.user_id','=',$user->id]
                            ])
                                ->groupBy('orders.id')
                                ->select('orders.*','assigns.id as assign_id')
                        ->get();
                    ?>
                    @foreach($related_orders as $rorder)
                        <tr>
                            <td>{{ $rorder->id }}</td>
                            <td>{{ $rorder->topic }}</td>
                            <td>{{ $rorder->pages }}</td>
                            <td>
                                <a class="btn btn-success btn-xs" href="{{ URL::to("writer/order/$rorder->id/room/$rorder->assign_id") }}"><i class="fa fa-users fa-fw"></i>Room</a>

                            </td>
                        </tr>
                        @endforeach
                </table>
            </div>
        </div>
    </div>
</div>