<?php
$writerCategory = Auth::user()->writerCategory;
$cpp = $writerCategory->cpp;
$decrease_percent = $writerCategory->deadline;
$category_id = $writerCategory->id;
        $bidmapper = $order->bidMapper;
if(isset($assign)){
    $deadline = \Carbon\Carbon::createFromTimestamp(strtotime($assign->deadline))->diffForHumans();
}   else {
    if($order->status=='1'){
        $assign = $order->assigns()->where([
            ['user_id','like',Auth::user()->id],
            ['status','<',4]
        ])->first();
        $b_deadline = \Carbon\Carbon::createFromTimestamp(strtotime($assign->deadline));
    }elseif($order->is_manual){
        $c_deadline = \Carbon\Carbon::createFromTimestamp(strtotime($order->deadline));
        $decrease_percent = 100-$decrease_percent;
        $decrease_percent = $decrease_percent/100;
        $new_hours = $c_deadline->diffInHours()*$decrease_percent;
        $b_deadline = \Carbon\Carbon::now()->addHours($new_hours);
    }

    else{
        $b_deadline = \Carbon\Carbon::createFromTimestamp(strtotime($bidmapper->deadline));

        $c_deadline = \Carbon\Carbon::createFromTimestamp(strtotime($order->deadline));
        if($bidmapper->deadline == '0000-00-00 00:00:00'){
            $decrease_percent = 100-$decrease_percent;
            $decrease_percent = $decrease_percent/100;
            $new_hours = $c_deadline->diffInHours()*$decrease_percent;
            $b_deadline = \Carbon\Carbon::now()->addHours($new_hours);
        }
        $now = date('y-m-d H:i:s');
    }
    $deadline = $b_deadline->diffForHumans();
    if(isset($assign)){
        $deadline = \Carbon\Carbon::createFromTimestamp(strtotime($assign->deadline))->diffForHumans();
    }
}



?>
<div id="o_order" class="tab-pane fade in active">
    @if(isset($assign))
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
        @endif
    <table align="centre" class="table table-bordered">
        <tr>
                <td colspan="4"><p style="font-size: large; font-weight: bold;">#{{ $order->id}}-{{ $order->topic }}</p></td>
        </tr>
  @if($order->is_manual == 0)
        <tr>
            <td class="titlecolumn">Type of Paper</td>
            <td>{{ $order->document->label }}</td>
            <td class="titlecolumn">English Style</td>
            <td>{{ @$order->language->label }}</td>

        </tr>
        <tr>
            <td class="titlecolumn">Subject Area</td>
            <td>{{ $order->subject->label }}</td>
            <th class="titlecolumn">Writer Category</th>
            <td>{{ @$order->writerCategory->name }}</td>
        </tr>
        <tr>
            <td class="titlecolumn">Academic Level</td>
            <td>{{ ucwords(@$order->academic->level) }}</td>
            <td class="titlecolumn">Sources</td>
            <td>{{ $order->sources }}</td>


        </tr>
        <tr>
            <td class="titlecolumn">Number of Pages</td>
            <?php $multiply = $order->spacing==2 ? 1:2  ?>
            <td>{{ $order->pages.' page(s) / '.$multiply*275*$order->pages.' Words' }}</td>
            <td class="titlecolumn">Referencing Style</td>
            <td>{{ @$order->style->label }}</td>
        </tr>
        <tr>
            <td class="titlecolumn">Spacing</td>
            <td><?php
                if($order->spacing==1){
                    echo "Single Spaced";
                }else{
                    echo "Double Spaced";
                }?></td>
            <td class="titlecolumn">Deadline</td>
            <td>{!! $deadline !!}<br/>
                ({{ $assign->deadline }})
            </td>
        </tr>
            <tr>
                <th class="titlecolumn">Slides</th>
                <td>{{ $order->slides }}</td>
                <th class="titlecolumn">Charts</th>
                <td>{{ $order->charts }}</td>
            </tr>
        @endif
        @if($order->is_manual)
            <tr>
                <th class="titlecolumn">Order Number</th>
                <td>{{ $order->order_number }}</td>
                <th class="titlecolumn">Pages</th>
                <td>{{ $order->pages }}</td>
            </tr>
            <tr>
                <th class="titlecolumn">Deadliner</th>
                <td>{{ $order->deadline }}</td>
                <td class="titlecolumn">Status</td>
                <td>
                    @if(!$order->is_manual)
                        @if($order->status>=1 && $order->status != 4 && $order->status != 6)
                            Working
                        @elseif($order->status==0)
                            On Hold
                        @else
                            Closed
                        @endif
                    @else
                        @if($order->manual_status>=1 && $order->manual_status != 4 && $order->manual_status != 6)
                            Working
                        @elseif($order->status == 10)
                            Pending Assignment
                        @else
                            Closed
                        @endif
                    @endif
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="4"><strong>Order Instructions</strong></td>
        </tr>
        <tr>
            <td colspan="4">{!! nl2br($order->instructions) !!}</td>
        </tr>

    </table>
</div>
<style type="text/css">
    .titlecolumn {
        background: whitesmoke;
        white-space: nowrap;
        text-align: right;
        font-weight: bold;
        width: 5%;
    }
</style>

