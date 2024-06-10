<?php
$notice_time = 0;
$secs = 2000;
?>
@if(isset($notices))
    @foreach($notices as $notice)
        <?php
        $notice_time++;
        $time = $notice_time*$secs;
        ?>
        <script type="text/javascript">
            setTimeout(function(){
                noty(<?php echo json_encode(['text'=>'<strong>'.ucwords($notice['title']).'</strong><br/>'.$notice['message'],'layout'=>'center','type'=>'success']) ?>);

            },<?php echo $time ?>)
        </script>
    @endforeach
@endif
@if(Auth::user())
    @if(Auth::user()->role=='admin')
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-envelope-o"></i> Messages
        <span id="message_count" class="badge bg-green">...</span>
        <i class="fa fa-caret-down"></i>
    </a>
    <ul id="messages_preview" class="dropdown-menu">

                <li>
                    <a href="{{ URL::to("departments/messages") }}">Inbox</a>
                </li>
    </ul>
</li>
@endif
<script type="text/javascript">
    $(document).ready(function(){
        getUnread();
    });
    getUnread();
    function getUnread(){
        var url = "{{ URL::to('messages/unread') }}";
        var image = "{{ URL::to('images/img.jpg') }}";
        var role = '{{  Auth::user()->role }}';
        $.get(url,null,function(response){
            var messages = JSON.parse(response);
            $(".message_count").text(messages.messages.length+messages.order.length);
            // $("#message_count").text(messages.messages.length);
            $(".writer_inbox").addClass('badge');
            $(".message_count").addClass('badge');
            $(".writer_inbox").html(messages.messages.length);
            var data = messages.messages;
            for(i=0;i<data.length;i++){
                var message = data[i];
                if(parseInt(message.department_id) != 0){
                    $("#messages_preview").prepend('<li>' +
                            '<a href="{{ URL::to('departments/conversation') }}/'+message.department_id+'/'+message.client_id+'">' +
                            '<span class="message">'+message.message.substr(0,50)+'...</span>' +
                            '</a>' +
                            '</li>');
                }else{
                    $("#messages_preview").prepend('<li>' +
                            '<a href="{{ URL::to('') }}/messages/'+message.id+'">' +
                            '<span><span>Room #'+message.assign_id+'</span></span>' +
                            '<span class="message">'+message.message.substr(0,50)+'...</span>' +
                            '</a>' +
                            '</li>');
                }

            }
            for(i=0;i<messages.order.length;i++){
                var message = messages.order[i];
                if(role=='client'){
                    $("#messages_preview").prepend('<li>' +
                            '<a href="{{ URL::to('') }}/stud/order/'+message.order_id+'#o_messages">' +
                            '<span><span>Order #'+message.order_id+'</span></span>' +
                            '<span class="message">'+message.message.substr(0,50)+'...</span>' +
                            '</a>' +
                            '</li>');
                }else if(role=='admin'){
                    $("#messages_preview").prepend('<li>' +
                            '<a href="{{ URL::to('') }}/order/'+message.order_id+'#o_messages">' +
                            '<span><span>Order #'+message.order_id+'</span></span>' +
                            '<span class="message">'+message.message.substr(0,50)+'...</span>' +
                            '</a>' +
                            '</li>');
                }

            }
            console.log(response);
        });
    }
    $(window).resize(function(){
        var width = $(window).width();
        //console.log(width);
        if(width>750){
            $(".gridular").hide();
            $(".tabular").show();
        }else{
            $(".tabular").hide();
            $(".gridular").show();
        }
    });
    $(window).ready(function(){

        var width = $(window).width();
        //console.log(width);
        if(width>750){
            $(".gridular").hide();
            $(".tabular").show();
        }else{
            $(".tabular").hide();
            $(".gridular").show();
        }
    });
</script>
@if(Auth::user()->role=='writer' || Auth::user()->role=='client')
    <script type="text/javascript">

        function getDataCount(){
            var url = '{{ URL::to('writer/get_count') }}';
            $.get(url,null,function(response){
                var data = JSON.parse(response);
                for(var i=0;i<data.length;i++){
                    $("#"+data[i].target).addClass('badge');
                    $("."+data[i].target).addClass('badge');
                    $("#"+data[i].target).html(data[i].data_count);
                    //$("."+data[i].target).addClass('badge');
                    $("."+data[i].target).html(data[i].data_count);
                }
            });
        }

    </script>
@else
    <script type="text/javascript">

        function getDataCount(){

        }

    </script>
    @endif
    @endif
