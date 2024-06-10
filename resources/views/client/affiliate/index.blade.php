@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="row"></div>
    <?php
    $user = Auth::user();

    if(!isset($tab))
        $tab = 'how';
    ?>
    <div class="card">
        <div class="card-header">Referral Program
            <a class="btn btn-lg btn-success pull-right load-page" href="{{ url("stud/new") }}"><i class="zmdi zmdi-mail-send"></i> New Order</a>

        </div>
        <div class="card-body">
            <ul class="tab-nav" role="tablist">
                <li class="{{ $tab == 'how'? 'active':'' }}"><a class="load-page" href="{{ url('stud/affiliate') }}">How it works</a></li>
                <li class="{{ $tab == 'earnings'? 'active':'' }}"><a class="load-page" href="{{ url('stud/affiliate/earnings') }}">My Earning</a></li>
                <li class="{{ $tab == 'gmail'? 'active':'' }}"><a class="load-page" href="{{ url('stud/affiliate/gmail') }}">Email Invites</a></li>
                <li class="{{ $tab == 'support'? 'active':'' }}"><a class="load-page" href="{{ url('stud/affiliate/support') }}">Affiliate Support</a></li>
            </ul>
            <div class="tab-content">
                <div class="section">
                    @include("client.affiliate.tabs.".$tab)
                </div>
            </div>
        </div>
    </div>
    @if(isset($_GET['action']))
        @include('client.affiliate.contacts_modal')
    @endif
@endsection