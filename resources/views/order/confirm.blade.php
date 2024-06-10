 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Confirm Completed Order <strong>#{{ $order->id }} - {{ $order->topic }}</strong></div>
        </div>
        <div class="panel-body">
            <div style="font-size: large;" class="alert alert-success"><strong><i class="fa fa-info fa-2x"></i> Confirm Completed Order</strong><br/>
                <p>
                    Confirming the order will release all the writer files to be visible to the client.
                    Please make sure that there is no any plagiarism or personalized messages in the submitted papers
               </p>
                {{--<strong>Please Rate the Writer According to Performance</strong>--}}
                <div class="row"></div>
            <form class="form-horizontal" method="post" action="{{ URL::to("order/$order->id/confirm/$assign->id") }}">
                {{ csrf_field() }}
                <input name="_method" value="put" type="hidden">
               <!-- <div class="form-group">
                    <label class="control-label col-md-3">Comments</label>
                    <div class="col-md-5">
                        <textarea name="comments" class="form-control">{{ $assign->comments }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="input-2" class="control-label col-md-3">Rate Writer</label>
                    <div class="col-md-5">
                        <input id="input-2" value="<?php echo $assign->rating;?>" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1">
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="control-label col-md-3 pull-le">&nbsp;</label>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $('#inrrput-2').rating({displayOnly: true, step: 0.5});
    </script>
@endsection