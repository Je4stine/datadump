<div id="payment_details" class="tab-pane fade">

<div class="col-md-5">
    <div class="card section">
        <div class="card-header">
            Order Assignment Details
        </div>
        <div class="panel-body">
            @if($assign->status < 3)
                <div class="panel panel-success">
                    <div class="panel-body">
                        <script type="text/javascript">
                            function setRange(){
                                var val = $("input[name='progress']").val();
                                var current = $("#current_progress").text();
                                val = parseFloat(val);
                                current = parseFloat(current);

                                var url = '{{ URL::to("/order/assign/$assign->id/progress") }}?progress='+val;
                                runPlainRequest(url);
                            }

                            function runAfterSubmit(response){
                                if(response.percent){
                                    $("#current_progress").text(response.percent);
                                }
                            }
                        </script>
                    </div>
                </div>
            @endif
            <table class="table table-bordered">
                <?php
                $fines = $assign->fines()->sum('amount');
                ?>
                <tr>
                    <th>Assigned On</th>
                    <td>{{ date('d M Y, h:i a',strtotime($assign->created_at)) }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="table table-bordered">
                            <tr>
                                <th>Amount</th>
                                <th>Bonus</th>
                                <th>Fine</th>
                                <th>Total</th>
                            </tr>
                            <tr>
                                <td>{{ @number_format($assign->amount,2) }}</td>
                                <td>{{ @number_format($assign->bonus,2) }}</td>
                                <td>{{ @number_format($fines,2) }}<a data-toggle="modal" class="label label-success" href="#fines_modal">View</a> </td>
                                <td>{{ @number_format(($assign->amount+$assign->bonus)-$fines,2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    @if($order->status==4 && $assign->status==4)
                        <td>Completed</td>
                    @elseif($order->status==3 && $assign->status==3)
                        <td>Pending</td>
                    @else
                        <td>Deadline: <?php echo \Carbon\Carbon::createFromTimestamp(strtotime($assign->deadline))->diffForHumans() ?></td>
                    @endif
                </tr>
                <tr>
                    <th>Order</th>
                    <td><a target="_blank" href="{{ URL::to("/writer/order/$order->id") }}">{{ $order->topic }}</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>
    </div>

<div id="fines_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    Fines <button data-dismiss="modal" class="btn btn-danger pull-right"><i class="fa fa-times"></i> </button></div>
            </div>
            <div class="modal-body">
                <h4>Writer Fines</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Reason</th>
                        <th>On</th>
                    </tr>
                    @foreach($assign->fines as $fine)
                        <tr>
                            <td>{{ $fine->id }}</td>
                            <td>{{ number_format($fine->amount,2) }}</td>
                            <td>{{ $fine->reason }}</td>
                            <td>{{ $fine->created_at }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
