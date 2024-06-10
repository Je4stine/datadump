<div id="progressive_milestones" class="tab-pane fade">
    <h3>Progressive Milestones</h3>
    <div class="row"></div>
    @if(!count($order->progressiveMilestones))
        <div class="alert alert-info">
            There are no Milestones yet
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
                <?php $no =1 ?>
                @foreach($order->progressiveMilestones()->orderBy('id','desc')->get() as $milestone)
                    <tr>
                        <td>{{ "Milestone#".$no++ }}</td>
                        <td>{{ $milestone->pages }}</td>
                        <td>
                            @if($order->paid==0)
                               On Hold
                            @else
                                Working
                            @endif
                        </td>
                        <td>
                         {{ \Carbon\Carbon::createFromTimestamp(strtotime($milestone->deadline))->diffForHumans() }}
                        </td>
                        <td>
                            @if($order->paid == 1)
                            <button onclick="runPlainRequest('{{ URL::to("order/complete-milestone") }}',{{ $milestone->id }})" class="btn btn-success btn-sm">Mark Completed</button>
                            @else
                                <button onclick="runPlainRequest('{{ URL::to("order/pay-milestone") }}',{{ $milestone->id }})" class="btn btn-success btn-sm">Mark Paid</button>
                            @endif
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
                <form class="form-horizontal ajax-post" method="post" action="{{ URL::to("stud/add_milestones/$order->id") }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-2">&nbsp;</label>
                        <div class="col-md-10">
                            <h4>Progress #{{ count($order->progressiveMilestones)+1 }}</h4>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Date Due</label>
                        <div class="col-md-10">
                            <input type="text" required name="deadline" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Amount</label>
                        <div class="col-md-10">
                            <input type="text" required name="amount" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Instructions</label>
                        <div class="col-md-10">
                            <textarea id="milestone_instructions" rows="15" name="instructions" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">&nbsp;</label>
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-success btn-lg">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    tinymce.init({
        menubar: "false",
        selector: "#milestone_instructions",  // change this value according to your HTML
        setup: function (editor) {
            editor.on('keyup', function (event) {
                tinymce.triggerSave();
            });
        }
    });
    function setTemplate(id,template){
        alert(template);
        $("input[name='id']").val(id);
        $("textarea[name='template']").html(template);
    }
</script>
