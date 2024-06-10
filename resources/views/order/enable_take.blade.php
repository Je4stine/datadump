 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
            $order = $bidmapper->order;
            ?>
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                Enable Order <strong>{{ $bidmapper->order->topic.'('.$order->pages.' Page(s))' }}</strong> For Taking
            </div>
        </div>
        <div class="panel-body">
           <div class="col-md-6 col-md-offset-2">
               <table class="table table-bordered">
                   <tr>
                       <th>Topic</th>
                       <th>{{ $order->topic }}</th>
                   </tr>
                   <tr>
                       <th>Pages</th>
                       <th>{{ $order->pages }}</th>
                   </tr>
                   <tr>
                       <th>Order Total</th>
                       <th>{{ $order->amount }}</th>
                   </tr>
                   <tr>
                       <th>Client Deadline</th>
                       <th>{{ date("Y M d, H:i",strtotime($order->deadline)) }}</th>
                   </tr>
               </table>
               <form class="form-horizontal" method="post" action="{{ URL::to("order/enable_take/$bidmapper->id") }}">
                   {{ csrf_field() }}
                   <div class="form-group">
                       <label for="amount" class="control-label col-md-3">Allowed Writers</label>
                       <div class="col-md-4">
                           @foreach($writer_categories as $writer_category)
                            <input type="checkbox" name="writer_categories[]" value="{{ $writer_category->id }}" {{ @in_array($writer_category->id,json_decode($bidmapper->allow_take)) ? "checked":"" }}>{{ $writer_category->name }}<br/>
                           @endforeach
                       </div>
                   </div>
                   <div class="form-group">
                       <label for="amount" class="control-label col-md-3">Total</label>
                       <div class="col-md-2">
                           <input type="text" value="{{ $bidmapper->take_amount }}" onchange="convertTotal();" name="take_amount" class="form-control">
                       </div>
                       <label for="cpp" class="control-label col-md-1">CPP</label>
                       <div class="col-md-2">
                           <input onchange="convertCpp();" type="text" value="" name="cpp" class="form-control">
                       </div>
                   </div>
                   <div class="form-group">
                       <label class="control-label col-md-3">&nbsp;</label>
                       <div class="col-md-4">
                           <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Enable</button>
                       </div>
                   </div>
               </form>
           </div>
        </div>
    </div>
    <script type="text/javascript">
        function convertCpp(){
            var cpp = $("input[name='cpp']").val();
            var pages = '{{ $order->pages }}';
            cpp = parseFloat(cpp);
            var pages = parseFloat(pages);
            var total = cpp*pages;
            total = total.toFixed(2);
            $("input[name='cpp']").val(cpp.toFixed(2));
            $("input[name='take_amount']").val(total);
        }

        function convertTotal(){
            var total = $("input[name='take_amount']").val();
            var pages = '{{ $order->pages }}';
            pages = parseFloat(pages);
            total = parseFloat(total);
            var cpp = total/pages;
            cpp = cpp.toFixed(2);
            $("input[name='take_amount']").val(total.toFixed(2));
            $("input[name='cpp']").val(cpp);
        }
    </script>
@endsection