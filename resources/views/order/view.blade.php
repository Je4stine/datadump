 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                <strong>Order Topic: </strong>{{ $order->topic }}
                <div class="dropdown pull-right">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks"> Action </i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="{{ URL::to("order/edit/$order->id/") }}"><i class="fa fa-edit"></i> Edit</a>
                        </li>
                        @if($assign_id = @$order->assigns()->where('status',0)->get()[0]->id)
                            <li><a href="{{ URL::to("order/$order->id/room/$assign_id") }}"><i class="fa fa-users"></i> Room</a>
                            </li>
                            @endif

                    </ul>
                </div>
            </div>
        </div>
        <?php
        $features = [];
        if($order->add_features){
            $features = \App\AdditionalFeature::whereIn('id',json_decode($order->add_features))->get();
        }
        $has_milestones = 0;
        foreach($features as $feature){
            $f_name = $feature->name;
            similar_text(strtolower($f_name),'progressive delivery',$percent);
            if($percent>80){
                $has_milestones = 1;
            }
        }
        ?>
        <div class="panel-body">
            <h2>Order Details</h2>
            <ul class="tab-nav" role="tablist">
                <li class="active"><a href="#o_order" aria-controls="o_order" role="tab" data-toggle="tab">Order</a></li>
                <li><a href="#o_files" aria-controls="o_files" role="tab" data-toggle="tab">Files<span class="badge">{{ count($order->files) }}</span></a></li>
                @if(!$order->is_manual)
                    <li><a href="#o_bids" aria-controls="o_bids" role="tab" data-toggle="tab">Bids<span class="badge">{{ count($order->bids) }}</span></a></li>

                    <li><a onclick="markRead();" href="#o_messages" aria-controls="o_messages" role="tab" data-toggle="tab">Messages<span class="badge">{{ $order->messages()->where([
                ['seen','=',0],
                ['sender','=',0]
                ])->count() }}</span></a></li>
                    @else
                    <li>
                    <li><a href="#o_bids" aria-controls="o_bids" role="tab" data-toggle="tab">Bids<span class="badge">{{ count($order->bids) }}</span></a></li>
                    </li>
                @endif
           </ul>

            <div class="tab-content">
                @include('order.includes.order')
                @include('order.includes.bids')
            @if(!$order->is_manual)
                @include('order.includes.messages')
                @endif
                @include('order.includes.files')
                {{--@include('order.includes.client')--}}
                @if($has_milestones == 1)
                    @include('order.includes.milestones')
                @endif
            </div>

        </div>
    </div>
@endsection