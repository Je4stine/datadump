 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Payouts <a href="{{ URL::to("payments/execute") }}" class="btn btn-info pull-right"><i class="fa fa-recycle"></i> Reload</a> </div>
        </div>
        <div class="panel-body">
            <table class="table">
               <tr>
                   <th>Writer#</th>
                   <th>Amount</th>
                   <th>State</th>
                   <th>Reference</th>
                   <th>Method</th>
                   <th>Date</th>
                   <th>Action</th>
               </tr>
                @foreach($payouts as $txn)
                    <tr>
                        <td>{{ $txn->email.'(#'.$txn->user_id.')' }}</td>
                        <td>{{ $txn->amount }}</td>
                        <td>{{ $txn->state }}</td>
                        <td>{{ $txn->transaction_reference }}</td>
                        <td>{{ $txn->method }}</td>
                        <td>{{ date('Y M d, H:i',strtotime($txn->created_at)) }}</td>
                        <td>
                            @if(strtolower($txn->state) == 'pending' && $txn->method == 'manual')
                                <a onclick="processPayment({{ $txn->id }},'{{ $txn->email.'(#'.$txn->user_id.')' }}')" class="btn btn-success">Process</a>
                                <a onclick="cancelPayment({{ $txn->id }})" class="btn btn-danger">Cancel</a>
                            @elseif($txn->state == 'CREATED' && $txn->method == 'paypal')
                                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey={{ $txn->pay_key }}" class="btn btn-success btn-sm"><i class="fa fa-paypal"></i> Pay Now</a>
                                <a onclick="cancelPayment({{ $txn->id }})" class="btn btn-danger">Cancel</a>
                           @endif
                            <a href="{{ URL::to("user/view/writer/$txn->user_id") }}" class="btn btn-success btn-sm"><i class="fa fa-user"></i> Writer</a>
                        </td>
                    </tr>
                    @endforeach
            </table>
            {{ $payouts->links() }}
        </div>
    </div>

    <div id="payment_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="btn btn-danger pull-right" class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title"><label>Pay <span id="user_details"></span></label></h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">Please pay writer in a different method like Bank, Mobile payment etc. Enter the transaction code here for reference</div>
                    <form id="payment_form" class="form-horizontal ajax-post" method="post" action="{{ URL::to("payments/payouts") }}">
                        {{ csrf_field() }}
                        <input type="hidden" id="txn_id" name="txn_id">
                        <div style="" id="manual_group" class="form-group">
                            <label class="control-label col-md-3">Reference</label>
                            <div class="col-md-6">
                                <input required type="text" name="reference" class="form-control">
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
    <div id="cancel_payment_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="btn btn-danger pull-right" class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title"><label>Payment Request Removal</label></h4>
                </div>
                <div class="modal-body">
                    <form id="payment_form" class="form-horizontal ajax-post" method="post" action="{{ URL::to("payments/payouts") }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="cancel_txn_id">
                        <div style="" id="manual_group" class="form-group">
                            <label class="control-label col-md-3">Message to Writer</label>
                            <div class="col-md-8">
                                <textarea rows="5" class="form-control" name="reason">Admin has Canceled/Removed your payment request. Please re submit your request or wait until the next payment period. {{ "\n" }} Sorry for any inconvenience caused  </textarea>
                            </div>
                        </div>
                        <div style="" id="" class="form-group">
                            <label class="control-label col-md-3">&nbsp;</label>
                            <div class="col-md-8">
                                <input type="submit" class="btn btn-success" value="Cancel">
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

        function processPayment(id,name){
            $("input[name='txn_id']").val(id);
            $("#user_details").html(name);
            $("#payment_modal").modal('show');
        }

        function cancelPayment(id){
            $("input[name='cancel_txn_id']").val(id);
            $("#cancel_payment_modal").modal('show');
        }

        function changeMode(){
            var selected = $("select[name='via']").val();
            if(selected=='manual'){
                $("#manual_group").slideDown();
            }else{
                $("#manual_group").slideUp();
            }
        }
    </script>
@endsection