@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('title')
Confirm Completed Order <strong>#{{ $order->id }} - {{ $order->topic }}</strong>
@endsection
@section('content')
            <div style="" class="">
                <div class="row"></div>
                <form class="form-horizontal" method="post" action="{{ URL::to("stud/approve/$order->id") }}">
                    <div class="form-group">
                        <div class="col-md-3 col-md-offset-3">
                            <strong>Confirm Completed Order</strong><br/>
                            <p>Please Rate our Writer According to how your essay was done. It will enable us know more about our writers</p>
                            <p>You will also be able to download your completed paper after approving the order</p>
                        </div>
                    </div>
                    {{ csrf_field() }}
                    <input name="_method" value="put" type="hidden">
                    <div class="form-group">
                        <label for="input-2" class="control-label col-md-3">Please Rate Writer</label>
                        <div class="col-md-5">
                            <div class="alert alert-info">10 Meaning Writer did a very good job, 0 Meaning Writer did an extremely bad job</div>
                            <input type="radio" name="rating" value="10" required> 10<br/>
                            <input type="radio" name="rating" value="9" required> 9<br/>
                            <input type="radio" name="rating" value="8" required> 8<br/>
                            <input type="radio" name="rating" value="7" required> 7<br/>
                            <input type="radio" name="rating" value="6" required> 6<br/>
                            <input type="radio" name="rating" value="5" required> 5<br/>
                            <input type="radio" name="rating" value="4" required> 4<br/>
                            <input type="radio" name="rating" value="3" required> 3<br/>
                            <input type="radio" name="rating" value="2" required> 2<br/>
                            <input type="radio" name="rating" value="1" required> 1<br/>
                            <input type="radio" name="rating" value="0" required> 0<br/>
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

    <script type="text/javascript">
        $('#inrrput-2').rating({displayOnly: true, step: 0.5});
    </script>
@endsection