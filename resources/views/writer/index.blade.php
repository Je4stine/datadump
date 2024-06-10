@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
    if(!isset($tab))
        $tab = 'available';
    ?>
    <div class="card">
        <div class="card-header">All Orders
        </div>
        <div class="card-body">
            <ul class="tab-nav" role="tablist">
                <li class="{{ $tab=='available' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("writer") }}">Available <span class="writer_dashboard"></span></a>
                </li>
                <li class="{{ $tab=='bids' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("writer/bids") }}">My Bids <span class="writer_bids"></span></a>
                </li>
                <li class="{{ $tab=='active' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("writer/active") }}">Current <span class="writer_active"></span></a>
                </li>
                <li class="{{ $tab=='revision' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("writer/revision") }}">Revision <span class="writer_revision"></span></a>
                </li>
                <li class="{{ $tab=='completed' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("writer/completed") }}">Finished <span class="writer_completed"></span></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="section">
                    @include("writer.tabs.".$tab)
                </div>
            </div>
        </div>
    </div>

@endsection