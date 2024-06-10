<div role="tabpanel" class="tab-pane" id="room_messages">
    <a href="#i_message_modal" data-toggle="modal" class="btn btn-info pull-right"><i class="fa fa-plus"></i> New Message</a>

    <table class="table">
        <tr>
            <th>Id</th>
            <th>Sender</th>
            <th>Message</th>
            <th>Date</th>
        </tr>
    @foreach($msgs = $assign->messages()->orderBy('id','desc')->get() as $message)
        <tr>
            <td>{{ $message->id }}</td>
            <td>{{ ucwords($message->user->role) }}</td>
            <td>{!! $message->message !!}</td>
            <td>{{ $message->created_at }}</td>
        </tr>
        @endforeach
    </table>
    <div class="modal fade" role="dialog" id="i_message_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <a class="btn btn-danger pull-right" data-dismiss="modal">&times;</a>
                        <h4>New Message</h4>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="messageform" method="post" action="{{ URL::to("/messages/$order->id/room/$assign->id/send") }}" class="form-horizontal ajax-post">
                        <div class="form-group">
                            <label class="control-label col-md-3">Message</label>
                            <div class="col-md-9">
                                {{ csrf_field() }}
                                <input type="hidden" name="sender" value="0">
                                <input type="hidden" name="client_id" value="{{ $order->user->id }}">
                                <textarea required id="newmessage" name="message" class="form-control" placeholder="Compose new Message"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Copy to sms</label>
                                <div class="col-md-9">
                                    <input type="checkbox" name="copy_sms" class="checkbox">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">&nbsp;</label>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-info">Send Message</button>
                                </div>
                            </div>
                        </div>
                    </form>                     </div>
            </div>
        </div>
    </div>
</div>
