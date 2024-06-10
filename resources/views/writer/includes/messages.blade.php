<div id="o_messages" class="tab-pane fade">
    <h3>Messages</h3>
    <a href="#i_message_modal" data-toggle="modal" class="btn btn-success btn-lg pull-left"><i class="fa fa-plus"></i> New Message</a>
    <div class="col-md-12">
        <table class="table hover">
            <tr>
                <th>FROM/TO</th>
                <th>MESSAGE</th>
                <th>DATE</th>
            </tr>
            @foreach($assign->messages()->orderBy('id','desc')->get() as $message)
               <tr onclick="markRead('{{ $message->id }}');" style="@if($message->seen == 0 && $message->user_id != Auth::user()->id) background-color:  #F5F5F5 @endif;" class="hidden-message-{{ $message->id }}">
                    <td><i class="fa fa-chevron-right"> <?php echo  $message->sender==1 ? 'WRITER &emsp; <i class="fa fa-arrow-right"></i>  &emsp; '.$message->from_user:ucwords($message->user->role.' <i class="fa fa-arrow-right"></i> Writer') ?></td>
                    <td class="more"> {!!$message->message !!}</td>
                    <td>{{ $message->created_at }}</td>

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
                <form id="messageform" method="post" action="{{ URL::to("/messages/$order->id/room/$assign->id/send") }}" class="form-horizontal ajax-post">
                    <div class="form-group">
                        <label class="col-sm-1">To</label>
                        <div class="col-md-10">
                            <select name="to" class="form-control">
                                <option>Support</option>
                                <option>Client</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            {{ csrf_field() }}
                            <input type="hidden" name="sender" value="1">
                            <input type="hidden" name="client_id" value="{{ $order->user->id }}">
                            <textarea required id="newmessage" name="message" class="form-control" placeholder="Compose new Message"></textarea>
                            <button type="submit" class="btn btn-default pull-right"><i class="fa fa-mail-forward"></i>Send</button>
                        </div>
                    </div>
                </form>                        </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        // Configure/customize these variables.
        var showChar = 30;  // How many characters are shown by default
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

