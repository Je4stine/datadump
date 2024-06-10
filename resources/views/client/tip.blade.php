 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Writer Tip</div>
        </div>
        <div class="panel-body">
            <div class="col-md-8">
                <div class="card section">
                    <div class="card-header">Optional Writer Tip</div>
                    <div class="panel-body">
                        <p>
                            Hi {{ Auth::user()->name }},<br/>
                            If the writer did a good job, You can optionally tip the writer as a way of motivating our writers and
                            encouraging them to do a good work. Your writer will really be happy.
                            <div class="row"></div>
                        </p>
                        <div class="row"></div>
                        <form method="post" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-md-2">Amount</label>
                                <div class="col-md-2">
                                    <select name="currency" class="form-control">
                                        @foreach($currencies as $currency)
                                            <option {{ $currency->usd_rate==1 ? "Selected":"" }} value="{{ $currency->abbrev }}">{{ $currency->abbrev }}</option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="amount" required class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">&nbsp;</label>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-paypal"></i> Tip Writer</button>
                                </div>
                            </div>
                        </form>
                        <a class="btn btn-warning btn-sm" href="{{ URL::to('stud/order/'.''.$order->id.'#o_files') }}"><i class="fa fa-arrow-left"></i> Next Time</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection