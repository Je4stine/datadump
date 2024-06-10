 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">styles <a href="{{ URL::to("writer_categories/add") }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> ADD</a></div>
        </div>
        <div class="panel-body">
            <table id="rates_table" class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Writer Cpp</th>
                    <th>Deadline Adjustment</th>
                    <th>Inc. Type</th>
                    <th>Amount</th>
                    <th>Late Fine(%)</th>
                    <th>Action</th>
                </tr>
                @foreach($writer_categories as $writer_category)
                    <?php
                    ?>
                    <tr class="tabrular">
                        <td>{{ $writer_category->id }}</td>
                        <td>{{ $writer_category->name  }}</td>
                        <td>{{ $writer_category->description  }}</td>
                        <td>{{ $writer_category->cpp  }}</td>
                        <td>{{ $writer_category->deadline  }}%</td>
                        <td>{{ $writer_category->inc_type  }}</td>
                        <td>{{ $writer_category->amount  }}%</td>
                        <td>{{ $writer_category->fine_percent  }}%</td>
                        <td>
                            <a class="btn btn-info btn-xs" onclick="" href="{{ URL::to("writer_categories/add?id=$writer_category->id") }}"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?');" href="{{ URL::to("writer_categories/delete/$writer_category->id") }}"><i class="fa fa-delete"></i> Delete</a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $writer_categories->links() }}
        </div>
    </div>
    </div>
@endsection
