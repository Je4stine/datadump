@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
    if(!isset($tab))
        $tab = 'new';
    ?>
    <div class="card">
        <div class="card-header">All Orders
            @if(Auth::user()->role == 'admin')
            <a class="btn btn-lg btn-success pull-right load-page" href="{{ url("manual/new") }}"><i class="zmdi zmdi-mail-send"></i> New Assigned</a>
@endif
        </div>
        <div class="card-body">
            <ul class="tab-nav" role="tablist">
                @if(Auth::user()->role == 'admin')
                <li class="{{ $tab=='new' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("manual/unassigned") }}">New</a>
                </li>
                @else

                    <li class="{{ $tab=='new' ? 'active':'' }}">
                        <a class="load-page" href="{{ url("manual/unassigned") }}">Available</a>
                    </li>                    @endif
                <li class="{{ $tab=='assigned' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("manual") }}">Active</a>
                </li>
                <li class="{{ $tab=='revision' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("manual/revision") }}">Revision</a>
                </li>
                <li class="{{ $tab=='pending' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("manual/pending") }}">Pending</a>
                </li>
                <li class="{{ $tab=='approved' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("manual/approved") }}">Approved</a>
                </li>
                @if(Auth::user()->role == 'writer')
                <li class="{{ $tab=='payments' || $tab=='user_payments' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("manual/payments") }}">Payments</a>
                </li>
                    @endif
            </ul>
            <div class="tab-content">
                <div class="section">
                    @if(isset($orders) && count($orders)>0)
                        @include("manual.tabs.".$tab)
                        @else
                        <div class="alert alert-info">No Orders</div>
                        @endif
                </div>
            </div>
        </div>
    </div>

@endsection