@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
<?php
    $now = date('y-m-d H:i:s');
    $deadline = $assign->deadline;
    $today = date_create($now);
    $end = date_create($deadline);
    $diff = date_diff($today,$end);
    if($today>$end){
        $remaining = 'Late By: <br/><span style="color: red;">'.$diff->d.' Day(s) '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
    }else{

        $remaining = '<span style="color: darkgreen;">'.$diff->d.' Day(s) '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
    }
        ?>
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                Chat Room for Order <strong>#{{ $order->id.' - '.$order->topic }}</strong> and Writer <strong>#{{ $assign->user->id.'-'.$assign->user->name }}</strong>

            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-5">
                <div class="card section">
                    <div class="card-header">
                        <div class="panel-title">
                        Order Assignment Details
                        <div class="dropdown pull-right">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-tasks"> Action </i>  <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                    <li><a href="{{ URL::to("/order/$order->id/confirm/$assign->id") }}"><i class="fa fa-thumbs-up"></i> Confirm</a> </li>
                                    <li><a href="{{ URL::to("/order/$order->id/revision/$assign->id") }}"><i class="fa fa-adjust"></i> Request Revision</a></li>
                                    <li><a href="{{ URL::to("/order/$order->id/setpending/$assign->id") }}"><i class="fa fa-thumbs-up"></i> Set Pending</a> </li>
                                    @if(Auth::user()->isAllowedTo('fine_writer')) <li><a href="{{ URL::to("/order/fine/$assign->id") }}"><i class="fa fa-money"></i> Fine</a></li>@endif
                                    <li><a href="{{ URL::to("/order/$order->id/extend/$assign->id") }}"><i class="fa fa-adjust"></i> Adjust</a> </li>
                                    <li><a href="{{ URL::to("/order/$order->id/cancel/$assign->id") }}"><i style="color: red;" class="fa fa-times"></i> Cancel</a> </li>
                            </ul>
                            <!-- /.dropdown-user -->
                        </div>
                            </div>
                    </div>
                    <div class="panel-body">
                        <?php
                            $fines = $assign->fines()->sum('amount');

                            ?>
                           @if($assign->status < 3)
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <form id="progress_form" class="ajax-post">
                                    <?php
                                        $progress = $assign->progress;
                                    if(!$progress){
                                        $progress = $assign->progress()->updateOrCreate(['assign_id'=>$assign->id],[
                                                'progress'=>0
                                        ]);
                                    }

                                        ?>
                                    Order Progress: <span id="current_progress">{{ (int)$progress->percent }}</span>% Done
                                    <input onchange="return setRange();" type="range" name="progress" value="{{ (int)$progress->percent }}">
                                </form>
                                <script type="text/javascript">
                                    function setRange(){
                                        var val = $("input[name='progress']").val();
                                        var current = $("#current_progress").text();
                                        val = parseFloat(val);
                                        current = parseFloat(current);

                                            var url = '{{ URL::to("/order/assign/$assign->id/progress") }}?progress='+val;
                                           runPlainRequest(url);
                                    }

                                    function runAfterSubmit(response){
                                        if(response.percent){
                                            $("#current_progress").text(response.percent);
                                        }
                                    }
                                </script>
                            </div>
                        </div>
                            @endif

                        <table class="table table-bordered">
                            <tr>
                                <th>Writer</th>
                                <td>{{ $assign->user->name }}<a class="pull-right label label-info" href="{{ URL::to("user/view/writer/".$assign->user->id) }}"><i class="fa fa-eye"></i> View</a> </td>
                            </tr>
                            <tr>
                                <th>Assigned On</th>
                                <td>{{ date('d M Y, h:i a',strtotime($assign->created_at)) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Amount</th>
                                            <th>Bonus</th>
                                            <th>Fine</th>
                                            <th>Total</th>
                                        </tr>
                                        <tr>
                                            <td>{{ @number_format($assign->amount,2) }}</td>
                                            <td>{{ @number_format($assign->bonus,2) }}</td>
                                            <td>{{ @number_format($fines,2) }} <a href="#fines_modal" data-toggle="modal"><i class="fa fa-eye"></i> </a> </td>
                                            <td>{{ @number_format(($assign->amount+$assign->bonus)-$fines,2) }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                @if($order->status==4 && $assign->status==4)
                                    <td>Completed</td>
                                    @elseif($order->status==3 && $assign->status==3)
                                    <td>Pending</td>
                                    @else
                                    <td><?php echo $remaining ?></td>
                                    @endif
                            </tr>
                            <tr>
                                <th>Order</th>
                                <td>{{ $order->topic }}<a class="pull-right label label-info" href="{{ URL::to("/order/$order->id") }}"><i class="fa fa-eye"></i> View</a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            @if(count($order->progressiveMilestones)>0)
                <div class="col-md-7 panel-default">
                    <div class="card-header">
                        Order Progress Milestones
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Pages</th>
                                <th>Status</th>
                                <th>Due</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            $no =1;
                            $order_paid = $order->payments()->sum('amount');
                            ?>
                            @foreach($miles = $order->progressiveMilestones()->orderBy('id','asc')->get() as $milestone)
                                <?php
                                $paid = 0;
                                if($milestone->amount<=$order_paid){
                                    $paid = 1;
                                    $to_pay = $milestone->amount-$order_paid;
                                    $order_paid-=$milestone->amount;

                                }else{
                                    $to_pay  = $milestone->amount;
                                }
                                ?>
                                <tr>
                                    <td>{{ $milestone->id }}</td>
                                    <td>{{ $milestone->pages }}</td>
                                    <td>
                                        @if($paid==0)
                                            On Hold
                                        @elseif($milestone->status==1)
                                            <i class="fa fa-check green"> Done</i>
                                        @elseif($milestone->status == 2)
                                            <i class="fa fa-warning red">Revision</i>
                                            @else
                                            Working
                                        @endif
                                    </td>
                                    <td>
                                        @if($milestone->status==1)
                                            Done
                                            @else
                                        {{ \Carbon\Carbon::createFromTimestamp(strtotime($milestone->deadline))->diffForHumans() }}
                                   @endif
                                    </td>
                                    <td>
                                        @if($paid==0)
                                            Part Not Paid
                                        @elseif($milestone->status==1)
                                            <button onclick="runPlainRequest('{{ URL::to("order/revise-milestone/$assign->id") }}',{{ $milestone->id }})" class="btn btn-warning"><i class="fa fa-recycle"></i> Return Revision</button>
                                        @elseif($milestone->status == 2)
                                            <button onclick="runPlainRequest('{{ URL::to("order/complete-milestone") }}',{{ $milestone->id }})" class="btn btn-success btn-sm">Mark Completed</button>
                                        @else
                                            <button onclick="runPlainRequest('{{ URL::to("order/complete-milestone") }}',{{ $milestone->id }})" class="btn btn-success btn-sm">Mark Completed</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </thead>
                        </table>
                    </div>
                </div>
                @endif
            <div class="col-md-7">
                <div class="card section">
                    <div class="card-header">
                        <div class="panel-title">Messages                        <a href="#i_message_modal" data-toggle="modal" class="btn btn-success pull-right"><i class="fa fa-plus"></i> New Message</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="div1" class="row" style="max-height: 400px; overflow-y: scroll">
                        <div class="chat">
                            @foreach($msgs = $assign->messages()->orderBy('id','desc')->get() as $message)
                                @if($message->sender==0)
                                    <hr/>
                                    <div class="bubble me row">
                                        <?php echo $message->message ?>
                                        <br/>
                                         <strong style="color:blue;"> <i>Admin</i></strong>
                                         <br/>
                                        <small><strong>{{ $message->created_at }}</strong></small>
                                    </div><br/>
                                    @else
                                    <hr/>
                                    <div class="bubble you row">
                                        <?php echo $message->message ?>
                                        <br/>
                                            <strong style="color:blue;"><i>Writer</i></strong>
                                        <br/>
                                        <small><strong>{{ $message->created_at }}</strong></small>
                                    </div><br/>
                                    @endif
                                @endforeach
                        </div>
                            </div>
                        <hr>
                    </div>
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
                                    <div class="col-md-12">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="sender" value="0">
                                        <input type="hidden" name="client_id" value="{{ $order->user->id }}">
                                        <textarea required id="newmessage" name="message" class="form-control" placeholder="Compose new Message"></textarea>
                                        <button type="submit" class="btn btn-default pull-right"><i class="fa fa-mail-forward"></i>Send</button>
                                    </div>
                                </div>
                            </form>                     </div>
                    </div>
                </div>
            </div>
            <div class="row"></div>
            <div class="card section col-md-6">
                <div class="panel card-header">Order Files <a onclick=" $('#files').slideToggle('slow'); return false; " class="pull-right" style="text-decoration: none;"><i class="fa fa-toggle-down fa-2x"></i> </a></div>
                <div class="panel-body" id="files" style="">
                    <?php
                    $images = array(
                            'pdf'=>'http://cdn1.iconfinder.com/data/icons/CrystalClear/128x128/mimetypes/pdf.png',
                            'doc'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Word.png',
                            'docx'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Word.png',
                            'ppt'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20PowerPoint.png',
                            'csv'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Excel.png',
                            'xls'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Excel.png',
                            'xlsx'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Excel.png',
                            'txt'=>'http://cdn1.iconfinder.com/data/icons/CrystalClear/128x128/mimetypes/txt2.png',
                            'zip'=>'http://www.softnuke.com/wp-content/uploads/2012/10/winrar1.png'
                    );
                    ?>
                    @if(Auth::user()->isAllowedTo('upload_file'))
                    <form class="form-horizontal" method="post" action="{{ URL::to("order/$order->id/room/$assign->id") }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-md-2">File</label>
                            <div class="col-md-8">
                                <div id="filesform">
                                    <input type="file" class="form-control" name="files[]">
                                </div>
                                <a onclick="return addFiles();" href="#"><i class="fa fa-plus fa-lg"></i></a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Type</label>
                            <div class="col-md-8">
                                <div id="filesform">
                                    <select name="type" class="form-control">
                                        <option>Final copy</option>
                                        <option>Draft</option>
                                        <option>Reference Material</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">&nbsp;</label>
                            <div class="col-md-5">
                                <input type="submit" class="btn btn-info" value="Submit">
                            </div>
                        </div>
                    </form>
                        @endif
                        <script type="text/javascript">
                            function addFiles(){
                               $("#filesform").append('<br/><input type="file" class="form-control" name="files[]">');
                            return false;
                            }
                        </script>
                    <table class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Date</th>
                            @if(Auth::user()->isAllowedTo('allow_file'))
                            <th>Allow Client?</th>
                                @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files as $file)
                            <?php
                            $image = @$images[$file->file_type];
                            if(!$image){
                            $image = "http://cdn1.iconfinder.com/data/icons/CrystalClear/128x128/mimetypes/txt2.png";
                            }
                            ?>
                            <tr>
                                <td>{{ $file->id }}</td>
                                <td><a target="_blank" href="{{ URL::to('/order/download/').'/'.$file->id }}"><img height="20px;" src="{{ $image  }}">{{ $file->filename }}</a> </td>
                                <td>{{ $file->file_for }}</td>
                                <td>{{ number_format($file->filesize/1024,2) }} KB</td>
                                <td>{{ $file->created_at }}</td>
                                 @if(Auth::user()->isAllowedTo('allow_file'))
                                <td>
                                    <input {{ $file->allow_client ? "checked":"" }} onclick="return allowFile('{{ $file->id }}')" type="checkbox" class="checkbox">
                                </td>
                                 @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            @if(count($assign->revisionMessages))
            <div class="col-md-6">
                <div class="card section">
                    <div class="card-header">
                        <div class="panel-title">Revision Instructions <a onclick=" $('#revbody').slideToggle('slow'); return false;" class="pull-right" style="text-decoration: none;"><i class="fa fa-toggle-down fa-2x"></i> </a> </div>
                    </div>
                    <div class="panel-body" id="revbody" style="">
                        <div class="card section">
                            <div class="panel-body">
                                @foreach($assign->revisionMessages as $message)
                                    <p class="alert alert-warning ">{{ $message->message }}<br/>
                                        <small>{{ date('d M Y, h:i a',strtotime($message->created_at)) }}</small>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </div>
        @endif
            </div>
        </div>
    </div>
    <script type="text/javascript">
//        setInterval(function(){
//            getMessages();
//        },5000);
        function scrollDown(){
//            $("#div1").animate({ scrollTop: $('#div1')[0].scrollHeight}, 1000);
        }
        scrollDown();
        function getMessages(){
            var count = "{{ count($assign->messages) }}";
            $.get('{{ URL::to("/messages/$order->id/room/$assign->id/messages") }}',{count:count},function(response){
                $(".chat").html('');
                var messages = JSON.parse(response);
                for(var i =0;i<messages.length;i++){
                    var message = messages[i];
                    if(message.sender==0){
                        $(".chat").append('<div class="bubble you">'+message.message+'<br/><small><strong>'+message.created_at+'</strong></div>');
                    }else{
                        $(".chat").append('<div class="bubble me">'+message.message+'<br/><small><strong>'+message.created_at+'</strong></div>');
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
            $.post(url,data,function(response){
                $("#newmessage").val('');
                $(".chat").append('<div class="bubble you">'+message+'</div>');
                getMessages();
            });
            scrollDown();
            return false;
        }
        function allowFile(id){
            var url = '{{ URL::to('order/allowfile') }}'+'/'+id;
            $.get(url,null,function(response){
                var status = JSON.parse(response);
                if(status.success){
                    return true;
                }else{
                    return false;
                }

            });
        }
    </script>
    <div id="fines_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        Fines <button data-dismiss="modal" class="btn btn-danger pull-right"><i class="fa fa-times"></i> </button></div>
                    </div>
                <div class="modal-body">
                    <h4>Writer Fines</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th>On</th>
                            @if(Auth::user()->isAllowedTo('fine_writer'))
                            <th>Action</th>
                                @endif
                        </tr>
                        @foreach($assign->fines as $fine)
                            <tr>
                                <td>{{ $fine->id }}</td>
                                <td>{{ number_format($fine->amount,2) }}</td>
                                <td>{{ $fine->reason }}</td>
                                <td>{{ $fine->created_at }}</td>
                                @if(Auth::user()->isAllowedTo('fine_writer'))
                                <td>
                                    <a onclick="return confirm('Are you sure?');" href="{{ URL::to("fines/remove/$fine->id") }}" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> Remove</a>
                                    <a onclick="return editFine({{$fine->id}},'{{ @number_format($fine->amount,2) }}','{{ $fine->reason }}')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                                    @endif
                            </tr>
                            @endforeach
                        <tr id="fine_form" style="display: none;">

                            <form method="post" action="{{ URL::to('fines/update') }}" class="form-horizontal col-md-3">
                                {{ csrf_field() }}
                                <td><input class="form-control" type="hidden" name="fine_id"> </td>
                                <td><input class="form-control" type="text" required name="fine_amount"> </td>
                                <td><textarea class="form-control" name="fine_reason" required></textarea> </td>
                                <td>&nbsp;</td>
                                <td>
                                    <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i> Save</button>
                                    <a onclick="return cancelEdit();" class="btn btn-warning btn-sm">Cancel</a>
                                </td>
                            </form>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function editFine(id,amount,reason){
            $("#fine_form").slideUp();
            $("input[name='fine_id']").val(id);
            $("input[name='fine_amount']").val(amount);
            $("textarea[name='fine_reason']").val(reason);
            $("#fine_form").slideDown('slow');
            return false;
        }
        function cancelEdit(){
            $("#fine_form").slideUp();
          return false;
        }
    </script>
@endsection