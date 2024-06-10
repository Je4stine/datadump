 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
    $now = date('y-m-d H:i:s');
    if($order->bidmapper){
      $deadline = $order->bidmapper->deadline;
            $deadline = \Carbon\Carbon::createFromTimestamp(strtotime($deadline));
            $remaining = $deadline->diffForHumans();  
    }
    
    $has_milestones = 0;
    $features = [];
    if($order->add_features){
        $features = \App\AdditionalFeature::whereIn('id',json_decode($order->add_features))->get();
    }
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
            <ul class="nav nav-tabs">
                <li class="active"><a class="btn btn-info" data-toggle="tab" href="#o_order">Order</a></li>
                {{--<li><a class="btn btn-success" data-toggle="tab" href="#o_bids">Bids<span class="badge">{{ count($order->bids) }}</span> </a></li>--}}
                <li><a class="btn btn-primary" data-toggle="tab" href="#o_files">Files<span class="badge">{{ count($order->files) }}</span></a></li>
                @if($has_milestones == 1)
                    <li>
                        <a class="btn btn-success" data-toggle="tab" href="#progressive_milestones">Parts <span class="badge">{{ $order->progressiveMilestones()->count() }}</span> </a>
                    </li>
                @endif
            </ul>


            <div class="tab-content">
                @include('writer.includes.order')
                @include('writer.includes.files')
                @if($has_milestones == 1)
                    @include('writer.includes.milestones')
                @endif
            </div>
        </div>
    </div>
@endsection