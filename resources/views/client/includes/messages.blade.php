    <div role="tabpanel" class="tab-pane" id="o_messages">
    <h3>Messages</h3>
    <a href="#i_message_modal" data-toggle="modal" class="btn btn-success btn-lg pull-left"><i class="fa fa-plus"></i> New Message</a>
    <div class="col-md-12">
        <table class="table hover">
            <tr>
                <th>FROM/TO</th>
                <th>MESSAGE</th>
                <th>DATE</th>
            </tr>
            @foreach($order->messages()->orderBy('id','desc')->get() as $message)
            <?php
            if($message->seen == 0 && $message->user_id != Auth::user()->id){

                   $color = '#F5F5F5';
            }else{
                $color = 'white';
            }
            if($message->user_id == Auth::user()->id){
                $sender = "ME";
                $receiver = strtoupper($message->destination);
            }else{
                $sender = strtoupper(str_replace('admin','SUPPORT',$message->user->role));
                $receiver = "ME";
            }
            ?>
                <tr onclick="markRead('{{ $message->id }}');" style="background-color:  {{ $color }}" class="hidden-message-{{ $message->id }}">
                    <td><i class="fa fa-chevron-right"> {{ $sender }} </i>   <i class="fa fa-arrow-right"></i> {{ $receiver }} </td>
                    <td  class="more"> {!!$message->message !!}</td>
                    <td>{{ \Carbon\Carbon::createFromTimestamp(strtotime($message->created_at))->diffForHumans() }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
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
                <form id="messageform" class="ajax-post form-horizontal" method="post" action="{{ URL::to("messages/ordermessages/$order->id") }}" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Message</label>
                        <div class="col-md-8">
                            {{ csrf_field() }}
                            <input type="hidden" name="sender" value="0">
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="client_id" value="{{ $order->user->id }}">
                            <textarea required id="newmessage" name="message" class="form-control" placeholder="Compose new Message"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">To</label>
                        <div class="col-md-8">
                            <select name="destination" class="form-control">
                                <option value="support">Support</option>
                                <option value="writer">Writer</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">&nbsp;</label>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-success">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //        setInterval(function(){
    //            getMessages();
    //        },5000);
    function scrollDown(){
        $("#div1").animate({ scrollTop: $('#div1')[0].scrollHeight}, 1000);
    }


    function runAfterSubmit(messages){
        getMessages();
    }
    scrollDown();
    function getMessages(){
        var count = "";
        var url = $("#messageform").attr('action');
        $.get(url,{count:count},function(response){
            $(".chat").html('');
            var messages = response;
            for(var i =0;i<messages.length;i++){
                var message = messages[i];
                if(message.sender==1){
                    $(".chat").append('<hr><div class="bubble you">'+message.message+'<br/>' +
                            ' <strong>FROM:'+message.destination+'</strong><br/><small><strong>'+message.created_at+'</strong></div>');
                }else{
                    $(".chat").append('<hr><div class="bubble me">'+message.message+'<br/>' +
                            +' <strong>'+message.destination+'</strong><br/><small><strong>'+message.created_at+'</strong></div>');
                }
            }
        });
        scrollDown();
    }



    /**
     * Send a message to writer
     */
    function sendMessage(){
        var url = $("#messageform").attr('action');
        var data = $("#messageform").serialize();
        $.post(url,data,function(response){
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
        scrollDown();
        return false;
    }
</script>
<script type="text/javascript">

    $(document).ready(function() {
        // Configure/customize these variables.
        var showChar = 20;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "More>";
        var lesstext = "Hide";


        $('.more').each(function() {
            var content = $(this).html();
            if(content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a style="color:red;" href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }

        });

        $(".morelink").click(function(){
            if($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });

    function markRead(id){
        jQuery.get('{{ URL::to("messages/room/markread") }}'+'/'+id,null,function(){

        });
    }
</script>
<style type="text/css">
    .morecontent span {
        display: none;
    }
    .morelink {
        display: block;
        color: red;
    }
</style>