@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
            $user = Auth::user();
            ?>
    <div class="card section">
        <div class="card-header">Order Payment</div>
        <div class="panel-body">
            <div class="col-md-5">
                <table class="table table-bordered">
                    <tr>
                        <th>Order</th>
                        <td>{{ $order->id.' '.$order->topic }}</td>
                    </tr>
                    <tr>
                        <th>Pages</th>
                        <td>{{ $order->pages }}</td>
                    </tr>
                    <tr>
                        <th>Order Total</th>
                        <td>${{ number_format($order->amount,2) }}</td>
                    </tr>
                    <tr>
                        <th>Account Balance</th>
                        <td>${{ number_format($user->getBalance(),2) }}</td>
                    </tr>
                    @if($order->amount<=$user->getBalance())
                        <tr>
                            <th>New Balance</th>
                            <td>
                                ${{ number_format($user->getBalance()-$order->amount,2) }}
                            </td>
                        </tr>
                        @endif

                </table>
            </div>
            <div class="col-md-7">
                <h3>Choose your payment method</h3>
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#paypal_direct"><i class="fa fa-paypal"></i> Paypal</a>
                    </li>
                    {{--<li>--}}
                        {{--<a data-toggle="tab" href="#payza_pay"><i class="fa fa-money"></i> Payza</a>--}}
                    {{--</li>--}}
                    <li>
                        <a data-toggle="tab" href="#acc_balance"><i class="fa fa-google-wallet"></i> Account Balance</a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#loyalty_points"><i class="fa fa-coffee"></i> Loyalty Points</a>
                    </li>
                </ul>
                <div style="" class="tab-content">
                    <div id="paypal_direct" class="tab-pane fade active in">
                        <h4>Paypal Payment</h4>
                        <form method="post" action="{{ URL::to("stud/pay/$order->id") }}" class="form-horizontal">
                            {{ csrf_field() }}
                            <img height="120" src="{{ URL::to("img/paypal-logo-1024x617.jpg") }}">
                            <button class="btn btn-success"><i class="fa fa-paypal"></i> Checkout</button>
                        </form>
                    </div>
                    <div id="payza_pay" class="tab-pane fade in">
                        <h4>Payza Payment</h4>
                        <form id="payzaForm" method="post" action="https://secure.payza.com/checkout" >

                            <input type="hidden" name="ap_merchant" value="profsamz@gmail.com"/>
                            <input type="hidden" name="ap_quantity" value="1"/>
                            <input type="hidden" name="ap_purchasetype" value="service"/>
                            <input type="hidden" name="ap_itemname" value="{{ $order->topic }}"/>
                            <input type="hidden" name="ap_amount" value="{{ round($order->amount,2) }}"/>
                            <input type="hidden" name="ap_itemcode" value="{{ $order->id }}"/>
                            <input type="hidden" name="ap_currency" value="USD"/>

                            <input type="image" name="ap_image" src="https://secure.payza.com/PayNow/25B6637E3C724F688BC29D4DAADCDD30c0en.gif"/>
                            <input type="hidden" name="ap_returnurl" value="{{ URL::to("stud") }}"/>
                            <input type="hidden" name="ap_cancelurl" value="{{ URL::to("stud/pay/$order->id") }}"/>
                        </form>
                    </div>
                    <div id="acc_balance" class="tab-pane fade in">
                        <form class="form-horizontal" method="post" action="{{ URL::to("stud/pay/$order->id") }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="form-group" style="display:none;">
                                <label for="amount" class="control-label col-md-3">Total</label>
                                <div class="col-md-2">
                                    <input type="hidden" value="{{ $order->amount }}" onchange="convertTotal();" name="amount" class="form-control">
                                </div>
                                <label for="cpp" class="control-label col-md-1">CPP</label>
                                <div class="col-md-2">
                                    <input onchange="convertCpp();" type="hidden" value="" name="cpp" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-7">
                                    @if(Auth::user()->getBalance()<$order->amount)
                                        <div class="alert alert-warning">
                                            Your account balance is insufficient. Please top up your account to pay for the order
                                            <div class="row"></div>
                                            <a href="#top_up_modal" data-toggle="modal" class="btn btn-success"><i class="fa fa-plus"></i> Top Up</a>
                                        </div>
                                    @else
                                        <button type="submit" class="btn btn-success"><i class="fa fa-thumbs-up"></i> Pay Now</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="loyalty_points" class="tab-pane fade in">
                        <h4>Loyalty Points</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>Your Points</th>
                                <td>{{ $user->getPoints() }}</td>
                            </tr>
                            <tr>
                                <th>Redeem Rate</th>
                                <td>{{ $website->getPointPay() }} Points = 1USD</td>
                            </tr>
                            <tr>
                                <th>Required Points</th>
                                <td>{{ $required_points = round($order->amount*$website->getPointPay(),0) }}</td>
                            </tr>
                            @if($required_points > $user->getPoints())
                                <?php
                                $referral_link  = URL::to("stud/new?referred_by=$user->id");
                                ?>
                                <tr>
                                    <td colspan="2">
                                        <strong>Your points are not sufficient to pay for this order</strong><br/>
                                        <p>Earn <strong>+</strong>{{ $www->getReferralPoints() }} more points by referring a friend to us.
                                            <br/><strong><i class="fa fa-info"></i> Tip</strong> Share your referral url to your friends in social media to get more points
                                        <p> <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ $referral_link }}"><i class="fa fa-facebook fa-2x"></i> </a>&nbsp;
                                            <a target="_blank" href="https://twitter.com/home?status={{ $referral_link }}"><i class="fa fa-twitter fa-2x"></i> </a>&nbsp;
                                            <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url={{ $referral_link }}&title=Get%20Academi%20Help&summary=&source="><i class="fa fa-linkedin fa-2x"></i> </a>&nbsp;
                                            <a target="_blank" href="https://plus.google.com/share?url={{ $referral_link }}"><i class="fa fa-google-plus fa-2x"></i> </a>&nbsp;
                                            <a target="_blank" href="mailto:?&subject=Get Online Academic Assistance&body=Hey,%20Get%20High%20quality%20academic%20assignment%20and%20research%20help%20%0A%3Ca%20href=%22{{ $referral_link }}%22%3E{{ $referral_link }}%3C/a%3E"><i class="fa fa-envelope fa-2x"></i> </a>&nbsp;
                                        </p>
                                        </p>
                                        <a class="btn btn-success" data-toggle="modal" href="#faq_modal">More Tips <i class="fa fa-question"></i> </a>
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="2" align="center">
                                        <form class="form-horizontal" method="post" action="{{ URL::to("stud/pay/$order->id") }}">
                                            {{ csrf_field() }}
                                            {{ method_field('PATCH') }}
                                            <input type="hidden" name="points" value="{{ $required_points }}">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-thumbs-up"></i> Redeem & Pay</button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                         </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function convertCpp(){
            var cpp = $("input[name='cpp']").val();
            var pages = '{{ $order->pages }}';
            cpp = parseFloat(cpp);
            var pages = parseFloat(pages);
            var total = cpp*pages;
            total = total.toFixed(2);
            $("input[name='cpp']").val(cpp.toFixed(2));
            $("input[name='amount']").val(total);
        }

        function convertTotal(){
            var total = $("input[name='amount']").val();
            var pages = '{{ $order->pages }}';
            pages = parseFloat(pages);
            total = parseFloat(total);
            var cpp = total/pages;
            cpp = cpp.toFixed(2);
            $("input[name='amount']").val(total.toFixed(2));
            $("input[name='cpp']").val(cpp);
        }
        @if(isset($_GET['pay']))
        @if($_GET['pay']=='payza')
        jQuery("#payzaForm").submit();
        @endif
        @endif

    </script>
    @include('client.modals')
@endsection