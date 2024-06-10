 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">languages <a href="{{ URL::to("settings/add/language") }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> ADD</a></div>
        </div>
        <div class="panel-body">
            <table id="rates_table" class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>ID</th>
                    <th>Label</th>
                    <th>Inc. Type</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>

                @foreach($languages as $language)
                    <?php
                    ?>
                    <tr class="tabular">
                        <td>{{ $language->id }}</td>
                        <td>{{ $language->label  }}</td>
                        <td>{{ $language->inc_type  }}</td>
                        <td>{{ $language->amount  }}</td>
                        <td>
                            <a class="btn btn-info btn-xs" onclick="" href="{{ URL::to("settings/add/language?id=$language->id") }}"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?');" href="{{ URL::to("settings/delete/language/$language->id") }}"><i class="fa fa-delete"></i> Delete</a>
                        </td>
                    </tr>
                    <div class="row"></div>
                    <div class="well well-lg col-md-12 gridular" language="padding-top: 10px;padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-sm-7"><strong>ID: </strong>{{ $language->id  }}</div>
                            <div class="dropdown pull-right">
                                <a class="btn btn-info btn-xs" onclick="" href="{{ URL::to("settings/add/language?id=$language->id") }}"><i class="fa fa-edit"></i> Edit</a>
                                <a class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?');" href="{{ URL::to("settings/delete/language/$language->id") }}"><i class="fa fa-remove"></i> Delete</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Label: </strong>{{ $language->label  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Inc. Type: </strong>{{ $language->inc_type }}</div>
                            <div class="col-sm-2"><strong>Amount: </strong>{{ $language->amount }}</div>
                        </div>

                    </div>
                @endforeach
            </table>
            {{ $languages->links() }}
        </div>
    </div>
    </div>
@endsection
