 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Payments</div>
        </div>
        <div class="panel-body">
            @include('writer.acc_payments')
            <?php
    $pay_obj = array();
    $total_earning = 0;
    $payment_struct = array();
    $years = array();
    $months = array();
    $early = array();
    $late = array();
    $data = array();
    foreach($assigns as $payment){
   $real_date = \Carbon\Carbon::createFromTimestamp(strtotime($payment->updated_at));
                $real_date = $real_date->addMonth();
                $submit = $real_date->toDateTimeString();
                $submit = strtotime($submit);
                $year = date("Y",$submit);
                $month = date("M",$submit);
                $day = date("d",$submit);
        if(!in_array($year, $years)){
            $years[]=$year;
        }
        if($day<15){
            $early[]=$payment;
            $data[$year][$month]['early'] = $early;
        }else{
            $late[]=$payment;
            $data[$year][$month]['late'] = $late;
        }
        $total = (double)$payment->amount;
        $bonus = (double)$payment->bonus;
        $earning = $total+$bonus;
        $total_earning+=$earning;
    }
            $month_names = array("January","February","March","April","May","June","July","August","September","October","November","December");
            ?>
                <p class="period">Payment Period</p>
                <?php
                foreach($data as $year=>$months){
                ?>
                <div class="label_years"><?php echo $year; ?></div>

                    <?php
                        $months = array_reverse($months);
                    $no = 0;
                    foreach($months as $month=>$orders){
                    $no++;
                    ?>
                    <div class="panel panel-success">
                        <div class="panel-body">
                <p onclick="$('#early<?php echo $year.$no ?>lt').slideToggle('slow');" class="month-pay" style=""><?php echo $month ?> 1st - 3rd</p>
                            <table id="late<?php echo $year.$no ?>lt" style="display:  gnone;" class="table closable table-condensed">
                                <tr class="" style="">
                                    <th>Order</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Bonus</th>
                                    <th>Fine</th>
                                    <th>Earnings</th>
                                </tr>
                                <?php
                                $order_count = 0;
                                $total = 0;
                                $bonus = 0;
                                if(isset($orders['early'])){

                                foreach($orders['early'] as $assign){
                                $order = $assign->order;
                                $order_count++;
                                $bonus+=$assign->bonus;
                                $cost = $assign->amount;
                                $total+=$cost;
                                $total-=$assign->fines()->sum('amount');
                                ?>
                                <tr class="" style="">
                                    <td>  <a target="_blank" href="{{ URL::to("order/$order->id") }}">#{{ $order->id." ".$order->topic }}</a> </td>
                                    <td>  {{ number_format((double)$assign->amount,2) }}</td>
                                    <td>
                                        @if($assign->status == 7)
                                            <i style="color: red;" class="fa fa-times"></i> Cancelled
                                        @else
                                            <i style="color: green;" class="fa fa-check"></i> Completed
                                        @endif
                                    </td>
                                    <td>  {{  @number_format($assign->bonus,2) }} </td>
                                    <td>  {{ @number_format($assign->fines()->sum('amount'),2) }} </td>
                                    <td>  {{ @number_format(($assign->amount+$assign->bonus)-$assign->fines()->sum('amount'),2) }} </td>
                                </tr>
                                <?php
                                }
                                }
                                ?>
                            </table>
                <p onclick="" class="month-pay" style="width:75%;background-color:#57AD68;">Orders: <span style="color:white"><?php echo $order_count; ?> </span>Bonus:<span style="color:white"> <?php echo number_format($bonus,2);  ?></span> Payment:<span style="color:white"><?php echo number_format($total,2);  ?></span> Total Earning: <span style="color:white"><?php echo number_format($bonus+$total,2);  ?></span> </p>  </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-body">
            <p onclick="$('#late<?php echo $year.$no ?>lt').slideToggle('slow');" class="month-pay" style=""><?php echo $month ?> 15th - 18th</p>
            <table id="late<?php echo $year.$no ?>lt" style="display:  gnone;" class="table closable table-condensed">
                <tr class="" style="">
                    <th>Order</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Bonus</th>
                    <th>Fine</th>
                    <th>Earnings</th>
                </tr>
                <?php
                $order_count = 0;
                $total = 0;
                $bonus = 0;
                if(isset($orders['late'])){

                foreach($orders['late'] as $assign){
                $order = $assign->order;
                $order_count++;
                $bonus+=$assign->bonus;
                $cost = $assign->amount;
                $total+=$cost;
                $total-=$assign->fines()->sum('amount');
                ?>
                <tr class="" style="">
                    <td>  <a target="_blank" href="{{ URL::to("order/$order->id") }}">#{{ $order->id." ".$order->topic }}</a> </td>
                    <td>  {{ @number_format((double)$assign->amount,2) }}</td>
                    <td>
                        @if($assign->status == 7)
                            <i style="color: red;" class="fa fa-times"></i> Cancelled
                        @else
                            <i style="color: green;" class="fa fa-check"></i> Completed
                        @endif
                    </td>
                    <td>  {{  @number_format($assign->bonus,2) }} </td>
                    <td>  {{ @number_format($assign->fines()->sum('amount'),2) }} </td>
                    <td>  {{ @number_format(($assign->amount+$assign->bonus)-$assign->fines()->sum('amount'),2) }} </td>
                </tr>
                <?php
                }
                }
                ?>
            </table>
            <p onclick="" class="month-pay" style="width:75%;background-color:#57AD68;">Orders: <span style="color:white"><?php echo $order_count; ?> </span>Bonus:<span style="color:white"> <?php echo number_format($bonus,2);  ?></span> Payment:<span style="color:white"><?php echo number_format($total,2);  ?></span> Total Earning: <span style="color:white"><?php echo number_format($bonus+$total,2);  ?></span> </p>
        </div>
    </div>
    <style type="text/css">
        .month-pay{
            position: relative;
            display: block;
            background: #57AD68;
            padding: 7px 0px 7px 10px;
            color: #333;
            font: bold 14px Arial, Helvetica, sans-serif;
            margin: 10px 0px 0px 10px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            -khtml-border-radius: 3px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .table-th{
            border: 10px;
            height:30px;
            color:#000000;
            background-color:#ff5050
        }
        .table-td{
            margin: 10px 0px 0px 10px;
            background: #FFF;
            height: 30px;
            color: #333;
            text-align: center;
            line-height: 30px;
            border-bottom: 1px solid #e6e6e6;
            border-left: 1px solid #e6e6e6;
            font: normal 12px Arial, Helvetica, sans-serif;
            vertical-align: middle !important;
        }
        .label_years {
            cursor: pointer;
            color: #2b91d5;
            font: bold 24px Arial, Helvetica, sans-serif;
            display: block;
            position: relative;
            padding: 0px 0px 5px 14px;
        }
        .period{
            color: #555;
            font: bold 14px Arial, Helvetica, sans-serif;
            padding: 0;
            margin: 0;
        }
    </style>
    <script type="text/javascript">
        function openTable(id){
            // alert(id);
            // $(".closable").slideUp('fast');
            // $("#"+id).slideToggle('slow');
        }
    </script>
    <?php

    }
    }
            ?>
        </div>
    </div>
@endsection