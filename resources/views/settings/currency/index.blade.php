 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Currencies <a href="{{ URL::to("currency/add") }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> ADD</a></div>
        </div>
        <div class="panel-body">
            <table id="rates_table" class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Abbrev</th>
                    <th>USD Rate</th>
                    <th>Action</th>
                </tr>

                @foreach($currencies as $currency)
                    <?php
                    ?>
                    <tr class="tabular">
                        <td>{{ $currency->id }}</td>
                        <td>{{ $currency->name  }}</td>
                        <td>{{ $currency->abbrev  }}</td>
                        <td>{{ $currency->usd_rate  }}</td>
                        <td>
                            <a class="btn btn-info btn-xs" onclick="" href="{{ URL::to("currency/add?id=$currency->id") }}"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?');" href="{{ URL::to("currency/delete/$currency->id") }}"><i class="fa fa-delete"></i> Delete</a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $currencies->links() }}
        </div>
    </div>
    </div>
@endsection
