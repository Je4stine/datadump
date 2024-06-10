@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    @if($website->wallet == 1)
        @include('client.e_wallet')
    @endif
    @include('client.modals')
@endsection