@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            My Article Performance
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li class="{{ $tab=='published' ? 'active':'' }}">
                    <a href="{{ URL::to("order/articles?tab=published") }}">Published</a>
                </li>
                <li class="{{ $tab=='pending' ? 'active':'' }}">
                    <a href="{{ URL::to("order/articles?tab=pending") }}">Pending</a>
                </li>
                @if($tab == 'view')
                    <li class="active">
                        <a href="{{ URL::to("order/articles/$article->id") }}">View Article#{{ $article->id }}</a>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                @if($tab == 'pending' || $tab == 'published')
                                @include('order.articles.tabs.pending')
                    @else
                    @include('order.articles.tabs.'.$tab)
                @endif
            </div>
        </div>
    </div>
@endsection