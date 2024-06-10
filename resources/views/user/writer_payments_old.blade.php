 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">{{ $user->name }} Payments</div>
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
                    $data[$year]["$month"]['late'] = $late;
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
                    <p onclick="$('#early<?php echo $year.$no ?>lt').slideToggle('slow');" class="month-pay" style=""><?php echo $month ?> 1st - 3rd </p>
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
                        $early_for = $year.'_'.$month.'_early';
                        $early_paid = $user->payments()->where('payment_for','LIKE',$early_for)->sum('amount');
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
                    <div onclick="" class="month-pay" style="width:75%;background-color:#57AD68;">
                        Orders: <span style="color:white"><?php echo $order_count; ?></span>
                        Bonus:<span style="color:white"> <?php echo number_format($bonus,2);  ?></span>
                        Payment:<span style="color:white"><?php echo number_format($total,2);  ?></span>
                        Total Earning: <span style="color:white"><?php echo number_format($bonus+$total,2);  ?></span>
                        Paid: <span style="color:white"><?php echo number_format($early_paid,2);  ?></span>
                        </span> Remaining: <span style="color:white"><?php echo number_format(($bonus+$total)-$early_paid,2)  ?></span>
                        @if(($bonus+$total)-$early_paid == 0 )
                            Status: <span style="color:white"><i style="" class="fa fa-check"></i> Settled</span>
                            @else
                        <form id="{{ $early_for }}" onsubmit="return submitPayment('{{ $early_for }}');" method="post" action="{{ URL::to("user/payout") }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="amount" value="{{ number_format(($bonus+$total)-$early_paid,2) }}">
                            <input type="hidden" name="payment_for" value="{{ $early_for }}">
                            <button class="btn btn-info pull-right">Pay Now({{ number_format(($bonus+$total)-$early_paid,2) }})</button>
                        </form>
                            @endif
                    </div>
                </div>
            </div>
            <div class="panel panel-success">
                <div class="panel-body">
                    <p onclick="$('#late<?php echo $year.$no ?>lt').slideToggle('slow');" class="month-pay" style=""><?php echo @$month ?> 15th - 18th</p>
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
                        $late_for = $year.'_'.$month.'_late';
                        $late_paid = $user->payments()->where('payment_for','LIKE',$late_for)->sum('amount');
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
                    <div onclick="" class="month-pay" style="width:75%;background-color:#57AD68;">
                        Orders: <span style="color:white"><?php echo $order_count; ?>
                        </span>Bonus:<span style="color:white"> <?php echo number_format($bonus,2);  ?></span>
                        Payment:<span style="color:white"><?php echo number_format($total,2);  ?>
                        </span> Total Earning: <span style="color:white"><?php echo number_format($bonus+$total,2);  ?></span>
                        </span> Paid: <span style="color:white"><?php echo number_format($late_paid,2);  ?></span>
                        </span> Remaining: <span style="color:white"><?php echo number_format(($bonus+$total)-$late_paid,2)  ?></span>
                        @if(($bonus+$total)-$late_paid == 0 )
                            Status: <span style="color:white"><i style="" class="fa fa-check"></i> Settled</span>
                        @else
                        <form id="{{ $late_for }}" onsubmit="return submitPayment('{{ $late_for }}');" method="post" action="{{ URL::to("user/payout") }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="amount" value="{{  number_format(($bonus+$total)-$late_paid,2)  }}">
                            <input type="hidden" name="payment_for" value="{{ $late_for }}">
                            <button class="btn btn-info pull-right">Pay Now({{ number_format(($bonus+$total)-$late_paid,2) }})</button>
                        </form>
                            @endif
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
            <?php

            }
            }
            ?>
        </div>
    </div>
    <div id="payment_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="btn btn-danger pull-right" class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title"><label>Pay {{ $user->name  }}</label></h4>
                </div>
                <div class="modal-body">
                    <form id="payment_form" class="form-horizontal" method="post" action="{{ URL::to("user/payout") }}">
                        {{ csrf_field() }}
                        <input type="hidden" id="pay_user_id" name="user_id">
                        <input type="hidden" id="pay_payment_for" name="payment_for">
                        <div class="form-group">
                            <label class="control-label col-md-3">Amount(USD)</label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" id="pay_amount" name="amount">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Method</label>
                            <div class="col-md-6">
                               <select onchange="changeMode()" class="form-control" name="via">
                                   <option value="paypal">Paypal</option>
                                   <option value="manual">Manual</option>
                               </select>
                            </div>
                        </div>
                        <div style="display: none;" id="manual_group" class="form-group">
                            <label class="control-label col-md-3">Reference</label>
                            <div class="col-md-6">
                               <input type="text" name="reference" class="form-control">
                            </div>
                        </div>
                        <div style="" id="" class="form-group">
                            <label class="control-label col-md-3">&nbsp;</label>
                            <div class="col-md-6">
                               <input type="submit" class="btn btn-success" value="Pay Now">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function submitPayment(id){
           var data = $("#"+id).serializeObject();
            console.log(data.user_id);
            $("#pay_user_id").val(data.user_id);
            $("#pay_amount").val(data.amount);
            $("#pay_payment_for").val(data.payment_for);
            $("#payment_modal").modal('show');
            return false;
        }

        function changeMode(){
            var selected = $("select[name='via']").val();
            if(selected=='manual'){
                $("#manual_group").slideDown();
            }else{
             $("#manual_group").slideUp();
            }
        }


        (function($){
            $.fn.serializeObject = function(){

                var self = this,
                        json = {},
                        push_counters = {},
                        patterns = {
                            "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                            "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                            "push":     /^$/,
                            "fixed":    /^\d+$/,
                            "named":    /^[a-zA-Z0-9_]+$/
                        };


                this.build = function(base, key, value){
                    base[key] = value;
                    return base;
                };

                this.push_counter = function(key){
                    if(push_counters[key] === undefined){
                        push_counters[key] = 0;
                    }
                    return push_counters[key]++;
                };

                $.each($(this).serializeArray(), function(){

                    // skip invalid keys
                    if(!patterns.validate.test(this.name)){
                        return;
                    }

                    var k,
                            keys = this.name.match(patterns.key),
                            merge = this.value,
                            reverse_key = this.name;

                    while((k = keys.pop()) !== undefined){

                        // adjust reverse_key
                        reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                        // push
                        if(k.match(patterns.push)){
                            merge = self.build([], self.push_counter(reverse_key), merge);
                        }

                        // fixed
                        else if(k.match(patterns.fixed)){
                            merge = self.build([], k, merge);
                        }

                        // named
                        else if(k.match(patterns.named)){
                            merge = self.build({}, k, merge);
                        }
                    }

                    json = $.extend(true, json, merge);
                });

                return json;
            };
        })(jQuery);
    </script>

@endsection