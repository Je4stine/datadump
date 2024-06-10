 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                {{ $website->name }}
{{--                <a class="btn btn-success pull-right" href="{{ URL::to("websites/emails/$website->id") }}">Emails</a>--}}
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-6">
                <div class="card section">
                    <div class="card-header">
                        Website Details
                    <a class="btn btn-info pull-right btn-xs" onclick="" href="{{ URL::to("websites/edit/$website->id") }}"><i class="fa fa-edit"></i> Edit</a>
                    </div>
                    <div class="panel-body">
                        <table class="table table-condensed table-bordered">
                            <tr>
                                <th>Name</th>
                                <td>{{ $website->name }}</td>
                            </tr>
                            <tr>
                                <th>Home URL</th>
                                <td>{{ $website->home_url }}</td>
                            </tr>
                            <tr>
                                <th>Telephone</th>
                                <td>{{ $website->telephone }}</td>
                            </tr>
                            <tr>
                                <th>E-mail</th>
                                <td>{{ $website->email }}</td>
                            </tr>
                            <tr>
                                <th>Logo</th>
                                <td id="logo">
                                    @if($website->logo)
                                        <img width="150" src="{{ URL::to($website->logo) }}">
                                        @endif
                                        <form enctype="multipart/form-data" class="form-horizontal" method="post" action="{{ URL::to("websites/$website->id/logo") }}">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <input type="file" class="form-control" name="logo">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                            </div>
                                        </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card section">
                    <div class="card-header">
                        Discount Codes <a href="#promo_modal" data-toggle="modal" class="btn btn-info pull-right"><i class="fa fa-plus"></i> Add</a>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tr>
                                <th>#</th>
                                <th>Discount Code</th>
                                <th>%</th>
                                <th>Min</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            @foreach($promotions = $website->promotions as $promotion)
                                <tr>
                                    <td>{{ $promotion->id }}</td>
                                    <td>{{ $promotion->code }}</td>
                                    <td>{{ $promotion->percent.'%' }}</td>
                                    <td>{{ $promotion->min_allowed }}</td>
                                    <td>
                                        @if($promotion->status==1)
                                            <i class="fa fa-check" style="color:green"></i>
                                        @else
                                            <i class="fa fa-remove" style="color: red"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($promotion->status==1)
                                            <a href="{{ URL::to("promotions/changestatus/$promotion->id/0") }}" class="label label-warning"><i class="fa fa-thumbs-down"></i> Deactivate</a>
                                        @else
                                            <a href="{{ URL::to("promotions/changestatus/$promotion->id/1") }}" class="label label-success"><i class="fa fa-thumbs-up"></i> Activate</a>
                                        @endif
                                        <a onclick="return confirm('Are you sure?')" href="{{ URL::to("promotions/delete/$promotion->id") }}" class="label label-danger"><i class="fa fa-remove"></i> Delete</a>`
                                    </td>

                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
        </div>
            @if($website->wallet)
            <div class="col-md-6">
                <div class="card section">
                    <div class="card-header">
                        E-Wallet & Account Settings <a href="#e_wallet_modal" data-toggle="modal" class="btn btn-info pull-right"><i class="fa fa-edit"></i> Edit</a>
                    </div>
                    <div class="panel-body">
                        <table class="table table-condensed table-bordered">
                            <tr>
                                <th>Points Per Referral <a onclick="showInfo('Amount of points to be earned by client for each successful referral')" class="badge alert-info" href="#" data-toggle="modal"><i class="fa fa-question"></i> </th>
                                <td>{{ $website->getReferralPoints() }}</td>
                            </tr>
                            <tr>
                                <th>Point Pay Amount <a onclick="showInfo('Amount to be spend by client in order to earn 1 Point')" class="badge alert-info" href="#" data-toggle="modal"><i class="fa fa-question"></i> </a></th>
                                <td>${{ $website->getPointPay() }}</td>
                            </tr>
                            <tr>
                                <th>Points Redeem Rate <a onclick="showInfo('Number of points to be redeemed to earn 1 USD')" class="badge alert-info" href="#" data-toggle="modal"><i class="fa fa-question"></i> </th>
                                <td>{{ $website->getRedeemRate() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
        </div>
                @endif
    </div>
    </div>
    <div id="e_wallet_modal" class="modal fade" role="dialog">
        <div style=""  class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="btn btn-primary pull-right" class="close" data-dismiss="modal">&times;</button>
                    Promotion Modal
                </div>
                <div class="modal-body">
                    <form action="{{ URL::to("websites/add") }}" method="post" class="form-horizontal ajax-post">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $website->id }}">
                        <div class="form-group">
                            <label class="control-label col-md-4">Referral Points</label>
                            <div class="col-md-6">
                                <input type="number" required value="{{ $website->getReferralPoints() }}" name="points_per_referral" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Point Pay Amount </label>
                            <div class="col-md-6">
                                <input type="text" value="{{ $website->getPointPay() }}" required name="point_pay_amount" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Points Redeem Rate</label>
                            <div class="col-md-6">
                                <input value="{{ $website->getRedeemRate() }}" type="text" required name="redeem_rate" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">&nbsp</label>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="promo_modal" class="modal fade" role="dialog">
        <div style=""  class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="btn btn-primary pull-right" class="close" data-dismiss="modal">&times;</button>
                    Promotion Modal
                </div>
                <div class="modal-body">
                    <form action="{{ URL::to("promotions/add") }}" method="post" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" name="website_id" value="{{ $website->id }}">
                        <div class="form-group">
                            <label class="control-label col-md-4">Discount Code</label>
                            <div class="col-md-6">
                                <input type="text" name="code" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Percentage (%)</label>
                            <div class="col-md-6">
                                <input type="number" name="percent" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Status</label>
                            <div class="col-md-6">
                                <select class="form-control" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Min Allowed</label>
                            <div class="col-md-6">
                                <input type="number" min="0" name="min_allowed" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">&nbsp</label>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection