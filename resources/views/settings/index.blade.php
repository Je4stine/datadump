 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Order Settings</div>
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#order_rates">Rates</a></li>
                <li><a data-toggle="tab" href="#order_categories">Categories</a></li>
            </ul>
            <div class="tab-content">
               @include('settings.rates')
               @include('settings.categories')
            </div>
        </div>
    </div>
    </div>
@endsection