
<div class="modal fade" role="dialog" id="redeem_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Redeem Points<a data-dismiss="modal" class="pull-right btn-danger btn">&times;</a></div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal ajax-post" action="{{ URL::to('stud/redeem-bonus') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-4">Points</label>
                        <div class="col-md-5">
                            <input type="number" max="{{ $user->getPoints() }}" value="{{ $user->getPoints() }}" name="points" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Amount(USD)</label>
                        <div class="col-md-5">
                            <input type="text" disabled name="redeem_amount" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">&nbsp;</label>
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-success"><i class="fa fa-download"></i> Redeem</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" id="null_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Action Status<a data-dismiss="modal" class="pull-right btn-danger btn">&times;</a></div>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" role="dialog" id="faq_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><h4>Earning Points</h4><a data-dismiss="modal" class="pull-right btn-danger btn">&times;</a></div>
            </div>
            <div class="modal-body">

                <ol>
                    <li><strong>By Order payments</strong><p>
                            For each <strong>${{ number_format($www->getPointPay(),2) }}</strong> you pay for an order, you earn <strong>+{{ 1 }}</strong> more point(s)
                        </p>
                    </li>
                    <li><strong>By Referrals</strong><p>
                            For each student you refer and places an order you get <strong>+{{ round($www->getReferralPoints(),0) }}</strong> more point(s)
                        </p>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" id="top_up_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <button class="btn btn-danger pull-right" data-dismiss="modal">&times;</button>
                    Account Top Up
                </div>
            </div>
            <div class="modal-body">
                <form onsubmit="return validateForm();" class="form-horizontal" method="post" action="{{ URL::to("stud/top-up") }}">
                    <div class="form-group amt">
                        <label class="control-label col-md-3">Amount(USD)</label>
                        <input type="hidden" name="order_id" value="{{ @$order->id }}">
                        <div class="col-md-6">
                            {{ csrf_field() }}
                            <input onkeyup="validateForm();" type="text" value="{{ @$order->amount ? round($order->amount,2):100 }}" class="form-control" name="amount">
                            <span class="error" style="color:red;" id="amt_error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Pay Via</label>
                        <div class="cc-selector-2 col-md-6">
                            <select name="via" class="form-control">
                                <option value="paypal">Paypal</option>
                            </select>
                        </div>
                        <!--   <div class="cc-selector-2 col-md-3">
                              <input  id="mastercard2" type="radio" name="via" value="skrill" />
                              <label class="drinkcard-cc skrill"for="mastercard2"></label>
                          </div> -->
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-6">
                            <button class="btn btn-success" type="submit">Proceed</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    setRedeemAmount();
    function setRedeemAmount(){
        var rate = '{{ $user->website->getRedeemRate() }}';
        var points = $("input[name='points']").val();
        rate = parseFloat(rate);
        points = parseFloat(points);
        var amount  = points/rate;
        $("input[name='redeem_amount']").val(amount.toFixed(2));
    }
</script>