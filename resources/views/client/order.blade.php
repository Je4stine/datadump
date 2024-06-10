 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
    $now = date('y-m-d H:i:s');
    $deadline = \Carbon\Carbon::createFromTimestamp(strtotime($order->deadline));
    $assigns = $order->assigns()->get();
            $assign_ids = [];

     foreach($assigns as $assign){
         $assign_ids[] = $assign->id;
     }
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
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                <strong>Order Topic: </strong>{{ $order->topic }}
            </div>
        </div>
        <div class="panel-body">
            <h2>Order Details</h2>
            <div role="tabpanel">
                <ul class="tab-nav" role="tablist">
                    <li class="active">
                        <a href="#o_order" aria-controls="o_order" role="tab" data-toggle="tab">Order</a>
                    </li>
                    <li>
                        <a href="#o_files" aria-controls="o_files" role="tab" data-toggle="tab">Files <span class="badge">{{ $order->files()->where([
                    ['allow_client','=',1]
                ])->count()+\App\File::whereIn('assign_id',$assign_ids)->where([
                    ['allow_client','=',1]
                ])->count() }}</span></a>
                    </li>
                    <li>
                        <a href="#o_messages" aria-controls="o_messages" role="tab" data-toggle="tab">Messages <span class="badge">{{ $order->messages()->where([
                ['seen','=',0],
                ['user_id','!=',Auth::user()->id]
                ])->count() }}</span></a>
                    </li>
                    @if($has_milestones == 1)
                        <li>
                            <a href="#progressive_milestones" aria-controls="progressive_milestones" role="tab" data-toggle="tab">Milestones</a>
                        </li>
                    @endif
                </ul>

                <div class="tab-content">
                    @include('client.includes.order')
                    {{--@include('client.includes.bids')--}}
                    @include('client.includes.messages')
                    @include('client.includes.files')
                    @if($has_milestones)
                        @include('client.includes.milestones')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection