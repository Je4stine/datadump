@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
            if(!isset($tab))
                $tab = 'active';
            ?>
    <div class="card">
        <div class="card-header">My Orders
            <a class="btn btn-lg btn-success pull-right load-page" href="{{ url("stud/new") }}"><i class="zmdi zmdi-mail-send"></i> New Order</a>

        </div>
        <div class="card-body">
            <ul class="tab-nav" role="tablist">
                <li class="{{ $tab=='active' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("stud?tab=active") }}">Active <span class="client_active"></span></a>
                </li>
                <li class="{{ $tab=='pending' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("stud/unpaid") }}">Pending <span class="client_un_payment"></span></a>
                </li>
                <li class="{{ $tab=='completed' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("stud/completed") }}">Completed <span class="client_completed"></span></a>
                </li>
                <li class="{{ $tab=='approved' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("stud/approved") }}">Approved <span class="client_approved"></span></a>
                </li>
                <li class="{{ $tab=='disputes' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("stud/disputes") }}">Resolution Center <span class="client_disputes"></span></a>
                </li>
                <li class="{{ $tab=='archived' ? 'active':'' }}">
                    <a class="load-page" href="{{ url("stud/archived") }}">Archived <span class="client_archived"></span></a>
                </li>

            </ul>
            <div class="tab-content">
                <div class="section">
                    @include("client.tabs.".$tab)
                </div>
            </div>
        </div>
    </div>

@endsection