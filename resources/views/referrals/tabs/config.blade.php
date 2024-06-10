<table class="table">
    <tr>
        <th>Website</th>
        <th>Referral Commission(%)</th>
        <th>Action</th>
    </tr>
    @foreach($websites as $website)
        <tr>
            <td>{{ $website->name }}</td>
            <td>{{ $website->referral_commission }}</td>
            <td>
                <a href="#update_modal" onclick="editWebsite({{ $website->id }},'{{ $website->referral_commission }}')" data-toggle="modal" class="btn btn-info">Edit</a>
            </td>
        </tr>
        @endforeach
</table>
<script type="text/javascript">
    function editWebsite(id,commission){
        $("input[name='id']").val(id);
        $("input[name='referral_commission']").val(commission);
    }
</script>

<div id="update_modal" class="modal fade" role="dialog">
    <div style=""  class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="btn btn-primary pull-right" class="close" data-dismiss="modal">&times;</button>
                Update Referral Commission
            </div>
            <div class="modal-body">
                <form action="{{ URL::to("referrals/config") }}" method="post" class="form-horizontal ajax-post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label class="control-label col-md-4">Referral Commission(%)</label>
                        <div class="col-md-6">
                            <input type="text" required value="" name="referral_commission" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">&nbsp;</label>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>