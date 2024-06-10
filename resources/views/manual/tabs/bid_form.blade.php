<form class="form-horizontal" method="post" action="{{ url("manual/bid/$order->id") }}">
    <div class="alert alert-info">
        Order # {{ $order->id }}
    </div>
    <div class="form-group">
        {{ csrf_field() }}
        <label class="col-md-2 control-label">Amount</label>
        <div class="col-md-10">
            <input type="text" value="{{ $amount }}" name="amount" required class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">Message</label>
        <div class="col-md-10">
            <textarea name="message" class="form-control" required rows="4">{!! $message !!}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">&nbsp;</label>
        <div class="col-md-5">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
    </div>
</form>