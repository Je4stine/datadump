 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                Order Promotions <a class="pull-right btn btn-primary" href="{{ URL::to("promotions/add") }}"><i class="fa fa-plus"></i> Add</a>
            </div>
        </div>
        <div class="panel-body">
            <DIV CLASS="col-md-6">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Discount Code</th>
                    <th>Percentage (%)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @foreach($promotions as $promotion)
                    <tr>
                        <td>{{ $promotion->id }}</td>
                        <td>{{ $promotion->code }}</td>
                        <td>{{ $promotion->percent.'%' }}</td>
                        <td>
                            @if($promotion->status==1)
                                <i class="fa fa-check" style="color:green"></i>
                                @else
                            <i class="fa fa-remove" style="color: red"></i>
                                @endif
                        </td>
                        <td>
                            @if($promotion->status==1)
                                <a href="{{ URL::to("promotions/changestatus/$promotion->id/0") }}" class="label label-warning"><i class="fa fa-thumbs-down"></i> Deactivate</a>
                            @else
                                <a href="{{ URL::to("promotions/changestatus/$promotion->id/1") }}" class="label label-success"><i class="fa fa-thumbs-up"></i> Activate</a>
                            @endif
                            <a onclick="return confirm('Are you sure?')" href="{{ URL::to("promotions/delete/$promotion->id") }}" class="label label-danger"><i class="fa fa-remove"></i> Delete</a>`
                        </td>

                    </tr>
                @endforeach
            </table>
            {{ $promotions->links() }}
                </DIV>
        </div>
    </div>
@endsection