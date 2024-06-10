<div id="o_messages" class="tab-pane fade">
    <a href="#ii_message_modal" data-toggle="modal" class="btn btn-success pull-right"><i class="fa fa-plus"></i> New Message</a>
    <h3>Messages</h3>

    <hr/>
    <div id="div1" class="row">
        <div class="table-responsive">
            <table class="table table-bordered table-condensed">
                <tr>
                    <th>ID</th>
                    <th>Sender</th>
                    <th>Message</th>
                    <th>Sms</th>
                    <th>Time</th>
                </tr>
                @foreach($order->messages()->orderBy('id','desc')->get() as $message)
                    <tr>
                        <td>{{ $message->id }}</td>
                        <td>
                            {{ ucwords($message->user->role) }}

                        </td>
                        <td>
                            <?php echo $message->message ?>
                        </td>
                        <td>
                            @if($message->sms == 1)
                                <i class="fa fa-check"></i>
                            @else
                                <i class="fa fa-times"></i>
                            @endif
                        </td>
                        <td>
                            {{ $message->created_at }}
                        </td>
                    </tr>
                @endforeach
            </table>

        </div>
    </div>

</div>

<script type="text/javascript">
    //        setInterval(function(){
    //            getMessages();
    //        },5000);
    function scrollDown(){
        // $("#div1").animate({ scrollTop: $('#div1')[0].scrollHeight}, 1000);
    }
    function markRead(){
        var url = $("#messageform").attr('action')+'/markread';
        var data = $("#messageform").serialize();
        $.get(url,data,function(response){
        });
    }

    scrollDown();
    function getMessages(){
        var count = "";
        var url = $("#messageform").attr('action');
        $.get(url,{count:count},function(response){
            $(".chat").html('');
            $(".chat").html('');
            $("#newmessage").val('');
            var messages = JSON.parse(response);
            for(var i =0;i<messages.length;i++){
                var message = messages[i];
                if(message.sender==1){
                    $(".chat").append('<hr><div class="bubble you">'+message.message+'<br/><small><strong>'+message.created_at+'</strong></div>');
                }else{
                    $(".chat").append('<hr><div class="bubble me">'+message.message+'<br/><small><strong>'+message.created_at+'</strong></div>');
                }
            }
        });
        // scrollDown();
    }

    /**
     * Send a message to writer
     */
    function sendMessage(){
        var url = $("#messageform").attr('action');
        var data = $("#messageform").serialize();
        $.post(url,data,function(response){
            window.location.reload();
            $(".chat").html('');
            $("#newmessage").val('');
            var messages = JSON.parse(response);
            for(var i =0;i<messages.length;i++){
                var message = messages[i];
                if(message.sender==1){
                    $(".chat").append('<hr><div class="bubble you">'+message.message+'<br/><small><strong>'+message.created_at+'</strong></div>');
                }else{
                    $(".chat").append('<hr><div class="bubble me">'+message.message+'<br/><small><strong>'+message.created_at+'</strong></div>');
                }
            }
        });

        return false;
    }
</script>
<div class="modal fade" role="dialog" id="ii_message_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <a class="btn btn-danger pull-right" data-dismiss="modal">&times;</a>
                    <h4>New Message</h4>
                </div>
            </div>
            <div class="modal-body">


                <form id="messageform" method="post" action="{{ URL::to("messages/ordermessages/$order->id") }}" class="form-horizontal ajax-post">
                    <div class="form-group">
                        <label class="control-label col-md-3">Message</label>
                        <div class="col-md-9">
                            {{ csrf_field() }}
                            <input type="hidden" name="sender" value="1">
                            <input type="hidden" name="client_id" value="{{ $order->user->id }}">
                            <textarea required style="min-height: 15px !important;" id="newmessage" name="message" rows="6" class="form-control" placeholder="Compose new Message"></textarea>
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
                </form>
            </div>
        </div>
    </div>
</div>