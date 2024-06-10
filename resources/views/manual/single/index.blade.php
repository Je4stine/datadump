@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
    if(!isset($tab))
        $tab = 'assigned';
    ?>
    <div class="card">
        <div class="card-header">All Manual Orders

        </div>
        <div class="card-body">
            <ul class="tab-nav" role="tablist">
                <li class="{{ $tab=='assigned' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("manual") }}">Assigned</a>
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
                <li class="{{ $tab=='payments' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("manual/payments") }}">Payments</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="section">
                    @include("manual.tabs.".$tab)
                </div>
            </div>
        </div>
    </div>
@endsection