 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">Writer: <span style="color: green;">{{ $user->name }}</span> Profile</div>
        <div class="panel-body">
            <div class="col-md-5">
                <div class="card section">
                    <div class="card-header">Personal Details</div>
                    <div class="panel-body">
                        <div class="profile_pic">

                            <img src="@if($user->image) {{ URL::to($user->image) }} @else {{ URL::to('images/img.png') }} @endif" alt="..." class="img-circle profile_img">
                        </div>
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>E-mail</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ ucwords($user->role) }}</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{ ucwords($user->country) }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ ucwords($user->phone) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row"></div>
                <div class="card-header">
                    <div class="panel-title">Payments</div>
                </div>
                <div class="panel-body">
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
                        $real_date = new DateTime($payment->updated_at);
                        $real_date->add(new DateInterval("P1M"));
                        $submit = strtotime($real_date->format("Y-m-d H:i:s"));
                        $year = date("Y",$submit);
                        $month = date("m",$submit);
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
                    <p></h1>
                        <?php
                        $months = array_reverse($months);
                        $no = 0;
                        foreach($months as $month=>$orders){
                        $no++;
                        ?>
                        <div class="panel panel-success">
                            <div class="panel-body">
                    <p onclick="$('#early<?php echo $year.$no ?>lt').slideToggle('slow');" class="month-pay" style=""><?php echo $month_names[$month-1] ?> 1st - 3rd</p>
                    <table id="early<?php echo $year.$no ?>lt" style="display: eone; " class="table closable table-condensed">
                        <tr class="" style="">
                            <th>Order</th>
                            <th>Amount</th>
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
                        ?>
                        <tr class="" style="">
                            <td>  <a target="_blank" href="{{ URL::to("writer/order/$order->id") }}">#{{ $order->id." ".$order->topic }}</a> </td>
                            <td>  {{ number_format((double)$assign->amount,2) }}</td>
                            <td>  {{  number_format($assign->bonus,2) }} </td>
                            <td>  {{ number_format($assign->fine,2) }} </td>
                            <td>  {{ number_format(($assign->amount+$assign->bonus)-$assign->fine,2) }} </td>
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
                <p onclick="$('#late<?php echo $year.$no ?>lt').slideToggle('slow');" class="month-pay" style=""><?php echo $month_names[$month-1] ?> 15th - 18th</p>
                <table id="late<?php echo $year.$no ?>lt" style="display: ntone;" class="table closable table-condensed">
                    <tr class="" style="">
                        <th>Order</th>
                        <th>Amount</th>
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
                    ?>
                    <tr class="" style="">
                        <td>  <a target="_blank" href="{{ URL::to("writer/order/$order->id") }}">#{{ $order->id." ".$order->topic }}</a> </td>
                        <td>  {{ number_format((double)$assign->amount,2) }}</td>
                        <td>  {{  number_format($assign->bonus,2) }} </td>
                        <td>  {{ number_format($assign->fine,2) }} </td>
                        <td>  {{ number_format(($assign->amount+$assign->bonus)-$assign->fine,2) }} </td>
                    </tr>
                    <?php
                    }
                    }
                    ?>
                </table>
                    <p onclick="" class="month-pay" style="width:75%;background-color:#57AD68;">Orders: <span style="color:white"><?php echo $order_count; ?> </span>Bonus:<span style="color:white"> <?php echo number_format($bonus,2);  ?></span> Payment:<span style="color:white"><?php echo number_format($total,2);  ?></span> Total Earning: <span style="color:white"><?php echo number_format($bonus+$total,2);  ?></span> </p>
                <?php
            }
                        }
            ?>
            </div>
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
@endsection