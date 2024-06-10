{{--Modal for updating order price--}}
<div id="pricemodal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <a class="btn btn-danger pull-right" class="close" data-dismiss="modal">&times;</a>
                <h4 class="modal-title"><label>Get Prediction</label></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{ URL::to("order/changeprice") }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="hidden" name="predictionid" id="gameid">
                        <label class="control-label col-md-5">Get prediction via: </label>
                        <input type="radio" required name="via" value="sms"> Sms <i style="color: green;">Ksh. {{ $sys_settings->sms_rate }}</i><br/>
                        <input type="radio" required name="via" value="online"> Web <i style="color: green;">Ksh. {{ $sys_settings->online_rate }}</i><br/>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5">&nbsp;</label>
                        <button class="btn btn-success"><i class="fa fa-check"></i> Proceed</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>

<div id="assign_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <a class="btn btn-danger pull-right" class="close" data-dismiss="modal">&times;</a>
                <h4 class="modal-title"><label>Get Prediction</label></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{ URL::to("order/changeprice") }}">
                    <div class="form-group">
                        <label class="control-label col-md-3">Writer</label>
                        <div class="form-group">
                            <input type="text" name="writer_id" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Amount(Total)</label>
                        <div class="form-group">
                            <input type="text" name="amount" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Deadline</label>
                        <div class="form-group">
                            <input type="text" name="deadline" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5">&nbsp;</label>
                        <button class="btn btn-success"><i class="fa fa-check"></i> Proceed</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>