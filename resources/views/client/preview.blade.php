 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    @include('client.includes.register')
    <?php
            $deadline = \Carbon\Carbon::createFromTimestamp(strtotime($order->deadline));
        $repo = new \App\Repositories\OrderRepository();
            $cost = $repo->calculateCost($order);
            ?>
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Confirm Order Details are Ok.</div>
        </div>
        <div class="panel-body">
            {{--<div class="alert alert-default col-md-5">--}}
                {{--<div class="card section">--}}
                    {{--<div class="panel-body">--}}
                        {{--<ul style="font-size: large;" class="fa-ul">--}}
                            {{--@foreach($website->punchlines as $punchline)--}}
                                {{--<li><i class="fa-li fa fa-check-square"></i> &nbsp;{{ $punchline->assurance }} </li>--}}
                            {{--@endforeach--}}
                        {{--</ul>--}}
                        {{--<br/>--}}
                        {{--@if($website->promo_image)--}}
                            {{--<img height="200" src="{{ URL::to($website->promo_image) }}">--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="alert alert-default col-md-13">
                <div class="card section">
                    <div class="panel-body">
                <table align="centre" class="table">
                    <tr>
                        <th>&nbsp;</th>
                        <th style="font-size:100%;" colspan="3">
                            <strong>Order Details</strong>
                            <div class="pull-right">
                                <a onclick="goBack();" class="btn btn-warning btn-sm" href="{{ URL::to("stud/new") }}"><i class="fa fa-arrow-left"></i> Back</a>
                                @if(@Auth::user()->role == 'admin')
                                    <a onclick="" class="btn btn-success btn-sm" href="{{ URL::to("order/create_order") }}"><i class="fa fa-check"></i> Complete</a>
                                @elseif($website->admin_quote)
                                 <a onclick="return checkLogin();" class="btn btn-info btn-sm" href="{{ URL::to("stud/paylater/$order->id") }}"><i class="fa fa-check"></i> Place Order</a>
                                @else
                                <a onclick="return checkLogin();" class="btn btn-success btn-sm" href="{{ URL::to("stud/checkout/$order->id") }}"><i class="fa fa-paypal"></i> Checkout</a>
                                @endif
                            </div>
                        </th>
                    </tr>
                    @if(!$website->admin_quote)
                    @if(!$order->partial)
                    <tr id="pricediv" style="font-size:150%;color:red;">
                        <td><strong>Total Cost</strong></td>
                        <td><span id="ttl"><?php echo number_format($order->amount*$order->currency->usd_rate,2).' '.$order->currency->abbrev; ?></span></td>
                        <td><strong>Promotion Code</strong></td>
                        <td>
                            <small id="responsi" style="color:red;"></small><input id="promotion" type="text" class="form-control" style="width:100%;background-color:yellow;color:blue;font-size:large;" name="promotion"><a onclick="return useCode();" class="label label-success"><span class="glyphicon glyphicon-ok"></span> Use Code</a>
                        </td>
                    </tr>
                    @else
                    <tr class="alert alert-info">
                        <th>Cost</th>
                        <td colspan="3">
                            <span style="font-size:large;font-weight:bolder;" class="">Deposit Amount: {{ number_format((($order->amount*$order->currency->usd_rate)*0.3),2).' '.$order->currency->abbrev }}</span>
                            <p>
                            The copy of your completed paper will be sent to you; after which the remaining <strong>70%</strong> will be paid.
                            </p>
                        </td>
                    </tr>
                    @endif
                    @endif
                    <input id="final_total" type="hidden" name="total" value="<?php echo $order->amount; ?>">
                    <tr>
                        <td><strong>Order Topic</strong></td>
                        <td colspan="3"><?php echo $order->topic; ?></td>

                    </tr>
                    <tr></tr>
                    <tr>
                        <td><strong>Preferred WriterID<small>(optional)</small></strong></td>
                        <td colspan="3">
                            <small id="preffered_status"></small>
                            <input type="text" onchange="return setPreferred();" name="writer_id" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Subject</strong></td>
                        <td>{{ $order->subject->label }}</td>
                        <td><strong>Doc. Type</strong></td>
                        <td>{{ $order->document->label }}</td>
                    </tr>
                    <tr>
                        <td><strong>Language</strong></td>
                        <td><?php echo $order->language->label; ?></td>
                        <td><strong>Style</strong></td>
                        <td>{{ $order->style->label }}</td>
                    </tr>
                    <tr>
                        <td><strong>Academic Level</strong></td>
                        <td>{{ $order->academic->level }}</td>
                        <td><strong>Sources</strong></td>
                        <td>{{ (int)$order->sources }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pages</strong></td>
                        <td>{{ $order->pages }}</td>
                        <td><strong>Spacing</strong></td>
                        <td><?php
                            if($order->spacing==1){
                                echo "Single Spaced";
                            }else{
                                echo "Double Spaced";
                            }?></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>Under Preview</td>
                        <td><strong>Deadline</strong></td>
                        <td>{{  date('M d, Y H:i',strtotime($deadline)) }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="3"><strong>Order Instructions</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4">{{ $order->instructions }}</td>
                    </tr>
                </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        checkLogin();
        function checkLogin(){
            @if(!Auth::user())
        $("#register_login").modal({
                backdrop: 'static',
                keyboard: true
            });
            return false;
            @endif
       }


        function useCode(){
            var code = $("#promotion").val();
            var order_id = "{{ $order->id }}";
            var url = "{{ URL::to('promotions/search') }}";
            $.get(url,{code:code,order_id:order_id},function(data){
                var response = JSON.parse(data);
                $("#responsi").html('processing.. ');
                if(response.status){
                    $("#responsi").html('');
                    var total = parseInt($("#ttl").text());
                    var dis = 100-parseInt(response.percent);
                    var newtot = dis/100*total;
                    $("#final_total").val(newtot);
                    $("#pricediv").html('<td><strong>Total Cost</strong></td><td colspan="3" style="color:green;font-size:large;">Success! You have been awarded  <span style="color:red;">'+response.percent+'%</span> promotion on your order total.New cost is <span style="color:red;">$'+newtot.toFixed(2)+'</span> from <span style="color:red;">$'+total+'</span></td>')

                }else{
                    $("#responsi").html(response.error);
              }
            });
        }

        function goBack() {
            window.history.back();
        }

        function setPreferred(){
            $("#preffered_status").html('<span style="color:green;">Processing...</span>');
            var id = $("input[name='writer_id']").val();
            var data = {writer_id:id};
            var url = '{{ URL::to('stud/preferred_writer') }}';
            $.get(url,data,function(response){
                if(response=='true'){
                    $("#preffered_status").html('<span style="color:green;">Writer Found<i class="fa fa-check"></i></span>');
                }else{
                    $("#preffered_status").html('<span style="color:red;">Writer not found!<i class="fa fa-times"></i></span>');
                    $("input[name='writer_id']").val('');
                }
            });
        }
    </script>
@endsection