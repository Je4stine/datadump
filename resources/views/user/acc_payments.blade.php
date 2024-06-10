    <div class="card section">
        <div class="card-header">
            <div class="panel-title">My Payments</div>
        </div>
        <div class="panel-body">

            <div class="col-md-5">
                <div class="card section">
                    <div class="card-header">
                        <div class="panel-title">Payment Overview</div>
                    </div>
                    <div class="panel-body">
                        @if(isset($_GET['manual']))
                            <p class="alert alert-info">You are viewing PrimeServe order payments</p>
                            <a class="btn btn-info load-page" href="{{ url("user/$user->id/payments") }}">Normal Payments</a>

                        @else
                            <p class="alert alert-info">You are viewing Normal order payments</p>
                            <a class="btn btn-info load-page" href="{{ url("user/$user->id/payments?manual=1") }}">PrimeServe Payments</a>


                        @endif
                        <table style="font-size: large;font-family: sans-serif, Verdana;" class="table table-">
                            <?php
                                $manual = isset($_GET['manual']) ? 1:0;
                            $total_worked = $user->totalWorked($manual);
                            $withdrawn = $user->totalWithdrawn($manual);
                            $pending = $user->totalPending($manual);
                            $fines = $user->totalFines($manual);
                            $available = $total_worked-($withdrawn+$pending+$fines);
                            ?>
                            <tr>
                                <th>Total Worked<a onclick="showInfo('Total amount worked for,' +
                                 'including amount earned from pending orders,' +
                                  'and completed orders, Withdrawals are not included  ')" class="badge alert-info" href="#" data-toggle="modal"><i class="fa fa-question"></i> </a> </th>
                                <td>${{ number_format($total_worked,2) }}</td>
                            </tr>
                            <tr>
                                <th>Withdrawn<a onclick="showInfo('Total amount that has been withdrawn')" class="badge alert-info" href="#" data-toggle="modal"><i class="fa fa-question"></i> </a></th>
                                <td>${{ number_format($withdrawn,2) }}</td>
                            </tr>
                                <tr>
                                    <th>Fines<a onclick="showInfo('Sum of order fines in Completed and Pending orders')" class="badge alert-info" href="#" data-toggle="modal"><i class="fa fa-question"></i> </a></th>
                                    <td>${{ number_format($fines,2) }}</td>
                                </tr>
                            <tr>
                                <th>Pending<a onclick="showInfo('Payments from Completed orders that client has not yet approved')" class="badge alert-info" href="#" data-toggle="modal"><i class="fa fa-question"></i> </a></th>
                                <td>${{ number_format($pending,2) }}</td>
                            </tr>
                            <tr>
                                <th>Available<a onclick="showInfo('Actual balance in your account that can be withdrawn')" class="badge alert-info" href="#" data-toggle="modal"><i class="fa fa-question"></i> </a></th>
                                <td>${{ number_format($available,2) }}
                                    @if(Auth::user()->id == $user->id)
                                        @if(env('MINIMUM_WITHDRAW',20)>$available)
                                            <a onclick="showInfo('Minimum allowed to withdraw is {{ number_format(env('MINIMUM_WITHDRAW',20),2) }}')" class="badge alert-info" href="#" data-toggle="modal"><i class="fa fa-question"></i> </a>
                                        @else
                                            <?php
                                            $now = \Carbon\Carbon::now();
                                                $day = $now->day;
                                                $day = 15;
                                                $allowed_days = [1,2,3,4,5,15,16,17,18,19,20];
                                                if(in_array($day,$allowed_days)){
                                                    $last_payment = $user->payouts()->orderBy('id')->first();
                                                    $last_time = \Carbon\Carbon::createFromTimestamp(strtotime($last_payment));
                                                    if($now->diffInDays($last_time)>5){
                                                       ?>
                                                <a data-toggle="modal" class="btn btn-success" href="#withdrawal_modal"><i class="fa fa-paypal"></i> Withdraw</a>
                                                     <?php
                                                    }
                                                }
                                                ?>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card section">
                    <div class="card-header">Payment Accounts</div>
                    <div class="panel-body">
                        <table class="table table-condensed">
                            <tr>
                                <th>#</th>
                                <th>Website</th>
                                <th>Email</th>
                                <th>&nbsp;</th>
                            </tr>
                            <?php $no = 1;$saved_websites = []; ?>
                            @foreach($user->paymentAccounts as $account)
                                <?php $saved_websites[]=$account->website;  ?>
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $account->website }}</td>
                                    <td>{{ $account->email }}</td>
                                    <td>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card section">
                    <div class="card-header">
                        Withdrawal History
                    </div>
                    <div class="panel-body">
                        <table class="table table-condesed">
                            <tr>
                                <th>#</th>
                                <th>Reference</th>
                                <th>Amount(USD)</th>
                                <th>State</th>
                                <th>Via</th>
                                <th>Time</th>
                            </tr>
                            <?php
                            $no = 1;
                                $payouts  = $user->payments()->where('is_manual',$manual)->orderBy('id','desc')->paginate(10);
                            ?>
                            @foreach($payouts as $payout)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $payout->transaction_reference }}</td>
                                    <td>{{ $payout->amount }}</td>
                                    <td>{{ $payout->state }}</td>
                                    <td>{{ ucwords($payout->method) }}</td>
                                    <td>{{ date('Y M d, H:i',strtotime($payout->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </table>
                        {{ $payouts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="font-size: large;" class="modal fade" role="dialog" id="info_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><a data-dismiss="modal" class="btn btn-danger pull-right"><i class="fa fa-remove"></i> </a> </div>

                <div class="modal-body" id="info_body">

                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" id="payment_account_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <a data-dismiss="modal" class="btn btn-danger pull-right"><i class="fa fa-remove"></i> </a>
                        <h3>Payment Account Form</h3>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ URL::to("writer/add_payment") }}" method="post">
                            <div class="form-group">
                                <label class="control-label col-md-3">Website</label>
                                {{ csrf_field() }}
                                <div class="col-md-5">
                                    <?php $allowed_websites = ['paypal'];  ?>
                                    <select name="website" required class="form-control">
                                        @foreach($allowed_websites as $website)
                                            @if(!in_array($website,$saved_websites))
                                                <option value="{{ $website }}">{{ ucwords($website) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">E-mail</label>
                                <div class="col-md-5">
                                    <input type="text" required name="email" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">&nbsp;</label>
                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" id="withdrawal_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <a data-dismiss="modal" class="btn btn-danger pull-right"><i class="fa fa-remove"></i> </a>
                        <h3>Withdraw Balance</h3>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" action="{{ URL::to("writer/withdraw") }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-md-3">AMOUNT(USD)</label>
                            <div class="col-md-5">
                                <input name="amount" type="text" class="form-control" disabled value="{{ number_format($available,2) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Via</label>
                            <div class="col-md-5">
                                <select class="form-control" name="via">
                                    <option value="paypal">Paypal</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">&nbsp;</label>
                            <div class="col-md-5">
                                <button class="btn btn-success" type="submit">Withdraw Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function showInfo(info){
            $("#info_body").html('<p>'+info+'</p>');
            $("#info_modal").modal('show');
        }
    </script>

    <style type="text/css">
        .cc-selector input{
            margin:0;padding:0;
            -webkit-appearance:none;
            -moz-appearance:none;
            appearance:none;
        }

        .cc-selector-2 input{
            position:absolute;
            z-index:999;
        }

        .skrill{background-image:url({{ URL::to('img/skrill_all.png') }});}
        .paypal{background-image:url({{ URL::to('img/paypal_all.png') }});}

        .cc-selector-2 input:active +.drinkcard-cc, .cc-selector input:active +.drinkcard-cc{opacity: .9;}
        .cc-selector-2 input:checked +.drinkcard-cc, .cc-selector input:checked +.drinkcard-cc{
            -webkit-filter: none;
            -moz-filter: none;
            filter: none;
        }
        .drinkcard-cc{
            cursor:pointer;
            background-size:contain;
            background-repeat:no-repeat;
            display:inline-block;
            width:200px;height:100px;
            -webkit-transition: all 100ms ease-in;
            -moz-transition: all 100ms ease-in;
            transition: all 100ms ease-in;
            -webkit-filter: brightness(1.8) grayscale(1) opacity(.7);
            -moz-filter: brightness(1.8) grayscale(1) opacity(.7);
            filter: brightness(1.8) grayscale(1) opacity(.7);
        }
        .drinkcard-cc:hover{
            -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
            -moz-filter: brightness(1.2) grayscale(.5) opacity(.9);
            filter: brightness(1.2) grayscale(.5) opacity(.9);
        }


    </style>
