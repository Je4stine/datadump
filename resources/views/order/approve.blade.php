 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Confirm Completed Order <strong>#{{ $order->id }} - {{ $order->topic }}</strong></div>
        </div>
        <div class="panel-body">
            <div style="" class="">
                <div class="row"></div>
                <form class="form-horizontal" method="post" action="{{ URL::to("order/approve/$order->id") }}">
                    <div class="form-group">
                        <div class="col-md-3 col-md-offset-3">
                            <strong>Confirm Completed Order</strong><br/>
                            <p>Please Rate our Writer According to how he did your essay. It will enable us know more about our writers</p>
                        </div>
                    </div>
                    {{ csrf_field() }}
                    <input name="_method" value="put" type="hidden">
                <div class="form-group">
                    <label for="input-2" class="control-label col-md-3">Rate Writer</label>
                    <div class="col-md-5">
                        <input id="input-2" value="" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1">
                    </div>
                </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Comments</label>
                        <div class="col-md-5">
                            <textarea name="comments" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 pull-le">&nbsp;</label>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>
        <script type="text/javascript">
            $('#inrrput-2').rating({displayOnly: true, step: 0.5});
        </script>
@endsection