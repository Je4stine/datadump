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
            <ul class="tab-nav" role="tablist">
                <li class="active"><a class="" data-toggle="tab" href="#o_order">Order</a></li>
                <li><a class="" data-toggle="tab" href="#o_files">Files</a></li>
                @if($has_milestones == 1)
                    <li>
                        <a class="" data-toggle="tab" href="#progressive_milestones">Parts <span class="badge">{{ $order->progressiveMilestones()->count() }}</span> </a>
                    </li>
                @endif
                <li><a class="" data-toggle="tab" href="#o_messages">Messages<i class="badge">{{ $assign->messages()->where([
                ['seen',0],
                ['user_id','!=',Auth::user()->id]
                ])->count() }}</i> </a></li>
                @if($assign->revisionMessages()->count())
                    <li><a class="" data-toggle="tab" href="#revision_msgs">Revision Messages</a></li>
                    @endif
                <li><a class="" data-toggle="tab" href="#payment_details">Payments & More..</a></li>
                @if($order->is_manual == 0)
                <li><a class="" data-toggle="tab" href="#client_history">Client History</a></li>
                    @endif

            </ul>


            <div class="tab-content">
                @include('writer.includes.order')
                @if(@$assign != null)
                    @include('writer.includes.room_files')
                    @include('writer.includes.messages')
                @else
                    @include('writer.includes.files')
                @endif
                @if($has_milestones == 1)
                    @include('writer.includes.milestones')
                @endif
                @if($assign->revisionMessages()->count())
                    @include('writer.includes.revision_messages')
                @endif
                @include('writer.includes.assignment_details')
                @include('writer.includes.client_history')
            </div>
        </div>
    </div>
@endsection