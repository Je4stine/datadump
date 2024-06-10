<?php
$referral_link  = URL::to("stud/new?referred_by=$user->id");
?>
@if($user->website->wallet == 1)
<div class="card section">
    <div class="card-header">
        <div class="panel-title">E-Wallet Overview</div>
     </div>
        <div class="panel-body">
            <div class="col-md-offset-1 col-md-10">
                <div class="form-group">
                    <label class="control-label col-md-2">Referral Link:</label>
                    <div class="col-md-8">
                        <p style="color:green;padding:5px;border:solid;border-width:0.2px;border-color:#00dd00;" name="link">{{ $referral_link }}</p>
                    </div>
                </div>
                <div class="row"></div>
                <script type="text/javascript">
                    function resetLink(){
                        $("input[name='link']").val('{{ $referral_link }}');
                    }
                </script>
                <div class="row tile_count">
                    @if(Auth::user()->role != 'admin')
                    <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-anchor"></i> Total Orders</span>
                        <div class="count green">{{ $user->orders->count() }}</div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-heart"></i> <span class="client_active"></span> </i> Active</span>
                    </div>
                    @endif
                    <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-google-wallet"></i> E-Wallet Balance</span>
                        <div class="count">${{ number_format($user->getBalance(),2) }}</div>
                        @if(Auth::user()->role == 'admin')
                        <span class="count_bottom"><a data-toggle="modal" href="#admin_top_up_modal" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> Top Up</a></span>
                        @else
                          <span class="count_bottom"><a data-toggle="modal" href="#top_up_modal" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> Top Up</a></span>
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-building"></i> Loyalty Points</span>
                        <div class="count">{{ $user->getPoints() }}</div>
                        @if(Auth::user()->role == 'admin')
                        <span class="count_bottom"><a data-toggle="modal" href="#add_points_modal" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> Add Points</a></span>
                       @else
                        <span class="count_bottom"><a data-toggle="modal" href="#redeem_modal" class="btn btn-xs btn-info"><i class="fa fa-coffee"></i> Redeem</a></span>
                      @endif
                    </div>
                    @if(Auth::user()->role != 'admin')
                    <div class="row"></div>
                    <div class="row"></div>
                    <hr/>
                    <div class="col-md-8 col-xs-6">
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
                    </div>
                        @endif
                </div>
        </div>
    </div>
    @if(Auth::user()->role == 'admin')
        <div id="add_points_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="pull-right btn btn-danger" data-dismiss="modal">&times;</button>
                        Add Points
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal ajax-post" method="post" action="{{ URL::to("user/view/$user->role/$user->id") }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="form-group">
                                <label class="control-label col-md-3">Amount</label>
                                <div class="col-md-8">
                                    <input type="number" min="1" value="" class="form-control" name="points">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Reason</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="reason">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">&nbsp;</label>
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="admin_top_up_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="pull-right btn btn-danger" data-dismiss="modal">&times;</button>
                        Top Up Account
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal ajax-post" method="post" action="{{ URL::to("user/view/$user->role/$user->id") }}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="form-group">
                                <label class="control-label col-md-3">Amount</label>
                                <div class="col-md-6">
                                    <input type="text" required value="" class="form-control" name="amount">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Method</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="via">
                                        <option value="paypal">Paypal</option>
                                        <option value="invoice">Invoice</option>
                                        <option value="manual">manual</option>
                                        <option value="bank">Bank Transfer</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Reference</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="reference">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">&nbsp;</label>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif