    <div role="tabpanel" class="tab-pane" id="progressive_milestones">

    <h3>Progressive Milestones</h3>
    <a onclick="resetForm('mile_form')" href="#progress_milestone_modal" data-toggle="modal" class="btn btn-info btn-lg pull-right"><i class="fa fa-plus"></i> Add</a>
    <div class="row"></div>
    @if(!count($order->progressiveMilestones))
        <div class="alert alert-info">
            There are no Milestones yet, Please click Add to add a new one
        </div>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Pages</th>
                    <th>Status</th>
                    <th>Due</th>
                    <th>Action</th>
                </tr>
                <?php
                $no =1;
                $order_paid = $order->payments()->sum('amount');
                ?>
                @foreach($order->progressiveMilestones()->orderBy('id','asc')->get() as $milestone)
                  <tr>
                        <td>{{ $milestone->id }}</td>
                        <td>{{ $milestone->pages }}</td>
                        <td>
                            @if($milestone->paid==0)
                               On Hold
                            @elseif($milestone->status==1)
                                <i class="fa fa-check green"> Done</i>
                                @else
                                Working
                            @endif
                        </td>
                        <td>

                         {{ \Carbon\Carbon::createFromTimestamp(strtotime($milestone->deadline))->diffForHumans() }}
                        </td>
                        <td>
                            @if($milestone->paid==0)
                                <a class="btn btn-success btn-sm" href="{{ URL::to('stud/pay/'.''.$order->id.'?mile='.$milestone->id) }}"><i class="fa fa-paypal"></i> Pay (${{ $milestone->amount  }})</a>
                            @endif
                                <a onclick="return editMilestone({{ $milestone }});" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </thead>
            </table>
        @endif
</div>

<div id="progress_milestone_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="pull-right btn btn-danger" data-dismiss="modal">&times;</button>
                Add Milestones
            </div>
            <div class="modal-body">
                <form id="mile_form" class="form-horizontal ajax-post" method="post" action="{{ URL::to("stud/add_milestones/$order->id") }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label class="control-label col-md-2">&nbsp;</label>
                        <div class="col-md-10">
                            <h4>Part #{{ count($order->progressiveMilestones)+1 }}</h4>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Date Due</label>
                        <div class="col-md-10">
                            <input type="text" required name="deadline" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Pages</label>
                        <div class="col-md-10">
                            <input type="number" min="0" required name="pages" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">&nbsp;</label>
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-success btn-lg">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function setTemplate(id,template){
        alert(template);
        $("input[name='id']").val(id);
        $("textarea[name='template']").html(template);
    }

    function editMilestone(milestone){
        $("input[name='id']").val(milestone.id);
        $("input[name='pages']").val(milestone.pages);
        $("input[name='deadline']").val(milestone.deadline);
        $("input[name='amount']").val(milestone.amount);
        $("textarea[name='instructions']").val(milestone.instructions);
        $("#progress_milestone_modal").modal('show');
    }


</script>
