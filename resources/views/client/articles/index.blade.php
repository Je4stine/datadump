@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            My Article Performance
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
            <?php 
            $slug = 'stud';
            if(Auth::user()->role == 'writer')
                $slug = 'writer';


            ?>
                <li class="{{ $tab=='approved' ? 'active':'' }}">
                    <a href="{{ URL::to("$slug?tab=approved") }}">Approved <i class="badge">{{ $approved_count }}</i></a>
                    
                </li>
                 <li class="{{ $tab=='drafts' ? 'active':'' }}">
                    <a href="{{ URL::to("$slug?tab=drafts") }}">Drafts <i class="badge">{{ $drafts_count }}</i></a>
                    
                </li>
                <li class="{{ $tab=='pending' ? 'active':'' }}">
                    <a href="{{ URL::to("$slug?tab=pending") }}">Pending  <i class="badge">{{ $pending_count }}</i></a>
                   
                </li>
                <li class="{{ $tab=='new' ? 'active':'' }}">
                    <a href="{{ URL::to("$slug?tab=new") }}">New Article</a>
                </li>
                @if($tab == 'view')
                <li class="active">
                    <a href="{{ URL::to("$slug/article/$article->id") }}">View Article#{{ $article->id }}</a>
                </li>
                @endif
            </ul>
            <div class="tab-content">
            {{ $tab }}
            @if($tab == 'edit' || $tab == 'new')
             @include('client.articles.tabs.'.$tab)
             @elseif($tab == 'view')
             @include('client.articles.view')
            @else
                @include('client.articles.tabs.approved')
                @endif
            </div>
        </div>
    </div>
@endsection