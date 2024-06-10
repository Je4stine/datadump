@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">{{ $user->name }}</div>
        <div class="panel-body">
            <div class="panel-body" >
                <div id="div1" class="row col-md-7" style="max-height: 400px; overflow-y: scroll">
                    <div class="chat">
                        @foreach($messages as $message)
                            @if($message->sender==1)
                                <hr/>
                                <div class="bubble me row">
                                    {{--<strong>On {{ date('Y M d H:i',strtotime($message->created_at)) .' Department: '.$department->name }}</strong><br/>--}}
                                    {!! nl2br($message->message) !!}
                                </div><br/>
                            @else
                                <hr/>
                                <div class="bubble you row">{!! nl2br($message->message) !!}</div><br/>
                            @endif
                        @endforeach
                    </div>

                <hr>
                <form id="messageform" action="{{ URL::to("departments/conversation/$department->id/$user->id/send") }}" method="post" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            {{ csrf_field() }}
                            @if(Auth::user()->role=='writer')
                            <input type="hidden" name="sender" value="1">
                                @else
                                <input type="hidden" name="sender" value="0">
                            @endif
                            <input type="hidden" name="client_id" value="{{ $user->id }}">
                            <textarea required id="newmessage" name="message" class="form-control" placeholder="Compose new Message"></textarea>
                            <button type="submit" onclick="return sendMessage();" class="btn btn-default pull-right"><i class="fa fa-mail-forward"></i>Send</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        getMessages();
        function scrollDown(){
            $("#div1").animate({ scrollTop: $('#div1')[0].scrollHeight}, 1000);
        }
        scrollDown();
        function getMessages(){
            $.get('{{ URL::to("departments/conversation/$department->id/$user->id") }}',function(response){
                $(".chat").html('');
                var messages = response;
                for(var i =0;i<messages.length;i++){
                    var message = messages[i];
                    if(message.sender==0){
                        $(".chat").append('<div class="bubble you">'+message.message+'</div>');
                    }else{
                        $(".chat").append('<div class="bubble me">'+message.message+'</div>');
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
            var message = $("#newmessage").val();
            $.get(url,data,function(response){
                $("#newmessage").val('');
                $(".chat").append('<div class="bubble you">'+message+'</div>');
                getMessages();
            });
            scrollDown();
            return false;
        }
    </script>
@endsection

