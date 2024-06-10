<div id="add_email_modal" class="modal fade" role="dialog">
    <div style=""  class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="btn btn-primary pull-right" class="close" data-dismiss="modal">&times;</button>
                E-mail Template Modal
            </div>
            <div class="modal-body">
                <form action="{{ URL::to("websites/emails/$website->id/add") }}" method="post" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label class="control-label col-md-4">Action</label>
                        <div class="col-md-6">
                            <input required type="text" name="action" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Description</label>
                        <div class="col-md-6">
                            <textarea required name="description" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4">&nbsp</label>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function setEmail(id,action,description){
        $("input[name='id']").val(id);
        $("input[name='action']").val(action);
        $("textarea[name='description']").val(description);
    }
</script>