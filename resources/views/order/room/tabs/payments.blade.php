<div role="tabpanel" class="tab-pane active" id="room_payments">
    <?php
    $fines = $assign->fines()->sum('amount');

    ?>
    @if($assign->status < 3)
        <div class="panel panel-success">
            <div class="panel-body">
                <form id="progress_form" class="ajax-post">
                    <?php
                    $progress = $assign->progress;
                    if(!$progress){
                        $progress = $assign->progress()->updateOrCreate(['assign_id'=>$assign->id],[
                            'progress'=>0
                        ]);
                    }

                    ?>
                    Order Progress: <span id="current_progress">{{ (int)$progress->percent }}</span>% Done
                    <input onchange="return setRange();" type="range" name="progress" value="{{ (int)$progress->percent }}">
                </form>
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
        <tr>
            <th>Writer</th>
            <td>{{ $assign->user->name }}<a class="pull-right label label-info" href="{{ URL::to("user/view/writer/".$assign->user->id) }}"><i class="fa fa-eye"></i> View</a> </td>
        </tr>
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
                        <td>{{ @number_format($fines,2) }} <a href="#fines_modal" data-toggle="modal"><i class="fa fa-eye"></i> </a> </td>
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
                <td><?php echo $remaining ?></td>
            @endif
        </tr>
    </table>
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
                                @if(Auth::user()->isAllowedTo('fine_writer'))
                                    <th>Action</th>
                                @endif
                            </tr>
                            @foreach($assign->fines as $fine)
                                <tr>
                                    <td>{{ $fine->id }}</td>
                                    <td>{{ number_format($fine->amount,2) }}</td>
                                    <td>{{ $fine->reason }}</td>
                                    <td>{{ $fine->created_at }}</td>
                                    @if(Auth::user()->isAllowedTo('fine_writer'))
                                        <td>
                                            <a onclick="return confirm('Are you sure?');" href="{{ URL::to("fines/remove/$fine->id") }}" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> Remove</a>
                                            <a onclick="return editFine({{$fine->id}},'{{ @number_format($fine->amount,2) }}','{{ $fine->reason }}')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Edit</a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr id="fine_form" style="display: none;">

                                <form method="post" action="{{ URL::to('fines/update') }}" class="form-horizontal col-md-3">
                                    {{ csrf_field() }}
                                    <td><input class="form-control" type="hidden" name="fine_id"> </td>
                                    <td><input class="form-control" type="text" required name="fine_amount"> </td>
                                    <td><textarea class="form-control" name="fine_reason" required></textarea> </td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i> Save</button>
                                        <a onclick="return cancelEdit();" class="btn btn-warning btn-sm">Cancel</a>
                                    </td>
                                </form>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

</div>
