<div class="alert alert-info">
    <strong style="font-size: larger;font-style: italic">Did you know <i class="fa fa-question fa-2x"></i> </strong>
    <p>You can invite contacts from your gmail address and earn <strong>$40 withdrawable</strong> for each contact that will sign up and place an order</p>
    <a class="btn btn-primary btn-large" href="{{ url('api/gmail/invite') }}"><i class="fa fa-check"></i> Invite Now</a>
</div>
<a href="#email_modal" data-toggle="modal" class="btn btn-info">Invite By Email</a>
<table class="table table-striped">
    <tr>
        <th>ID</th>
        <th>email</th>
    </tr>
    @foreach($gmail_contacts as $contact)
        <tr>
            <td>{{ $contact->id }}</td>
            <td>{{ $contact->email }}</td>
        </tr>
    @endforeach
</table>
{{ $gmail_contacts->links() }}
<div style="display: none;" class="modal fade" id="email_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a data-dismiss="modal" class="btn btn-danger pull-right modal-closer">&times;</a>
                <h5>Invite By Email</h5>
            </div>
            <div class="modal-body">
                <form class="form-horizontal ajax-post" method="post" action="{{ url('stud/affiliate/gmail') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-3 control-label">Name</label>
                        <div class="col-md-7">
                            <input type="text" name="name" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Email</label>
                        <div class="col-md-7">
                            <input type="text" name="email" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">&nbsp;</label>
                        <div class="col-md-7">
                            <input type="submit" name="submit" required class="btn btn-success" value="Invite Now">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>