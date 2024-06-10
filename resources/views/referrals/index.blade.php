@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('title')
    Referral Management
@endsection
@section('content')

@include('includes.auto_tabs',[
    'tabs'=>['earnings','config'],
    'tabs_folder'=>'referrals.tabs.',
    'base_url'=>'referrals'
])
@endsection
