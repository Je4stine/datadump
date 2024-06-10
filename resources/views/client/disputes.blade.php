 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Orders under Dispute</div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>Order ID</th>
                    <th>Reason</th>
                    <th>Files</th>
                    <th>Pref. Action</th>
                    <th>Status</th>
                </tr>

                @foreach($disputes as $dispute)
                    <?php
                    ?>
                    <tr class="tabular">
                        <td>{{ $dispute->order_id }}<a class="btn btn-info btn-xs" href="{{ URL::to("stud/order/$dispute->order_id") }}"><i class="fa fa-eye"></i> View</a> </td>
                        <td>{{ $dispute->reason  }}</td>
                        <td>{{ count(json_decode($dispute->files)) }}</td>
                        <td>{{ $dispute->action }}</td>
                        <th>
                            @if($dispute->status)
                                <i style="color:green" class="fa fa-check"></i>(Resolved)
                            @else
                                <i style="color: darkorange" class="fa fa-warning"></i> (Pending)
                            @endif
                        </th>
                    </tr>
                    <div class="row"></div>
                    <div class="well well-lg col-md-12 gridular" style="padding-top: 10px;padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-sm7"><strong>Order: </strong>#{{ $dispute->order_id }}-<a class="btn btn-info btn-xs" href="{{ URL::to("stud/order/$dispute->order_id") }}"><i class="fa fa-eye"></i> View</a> </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Reason: </strong>{{ $dispute->reason  }}</div>
                            <div class="col-sm-3"><strong>Files: </strong>{{ count(json_decode($dispute->files))  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Pref. Action: </strong>{{ $dispute->action }}</div>
                            <div class="col-sm-2"><strong>Status: </strong> @if($dispute->status)
                                    <i style="color:green" class="fa fa-check"></i>(Resolved)
                                @else
                                    <i style="color: darkorange" class="fa fa-warning"></i> (Pending)
                                @endif</div>
                        </div>

                    </div>

                @endforeach
            </table>
            {{ $disputes->links()  }}
        </div>
    </div>
@endsection