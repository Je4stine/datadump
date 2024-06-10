 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Additional Features <a href="#feature_modal" data-toggle="modal" class="btn btn-success pull-right"><i class="fa fa-plus"></i> ADD</a></div>
        </div>
        <div class="panel-body">
            <table id="rates_table" class="table table-bordered table-striped">
                <tr class="tabular">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>

                @foreach($features as $feature)
                    <?php
                    ?>
                    <tr class="tabular">
                        <td>{{ $feature->id }}</td>
                        <td>{{ $feature->name }}</td>
                        <td>{{ $feature->inc_type == 'percent' ? $feature->amount.'%':'$'.$feature->amount }}</td>
                        <td>
                            <a class="btn btn-info btn-xs" data-toggle="modal" href="#feature_modal" onclick="editFeature({{ $feature }})"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?');" href="{{ URL::to("additional-features/delete/$feature->id") }}"><i class="fa fa-delete"></i> Delete</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="modal fade" id="feature_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <a data-dismiss="modal" class="btn btn-danger pull-right">&times;</a>
                        <h4>Additional Feature Form</h4>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal ajax-post" method="post" action="{{ URL::to('additional-features') }}">
                        <input type="hidden" name="id">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-md-3">Name</label>
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Description</label>
                            <div class="col-md-6">
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div style="" class="form-group">
                            <label class="control-label col-md-3">Charge Type</label>
                            <div class="col-md-6">
                                <select class="form-control" name="inc_type">
                                    <option value="percent">Percent</option>
                                    <option value="money">Fixed</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Amount</label>
                            <div class="col-md-6">
                                <input type="number" value="" name="amount" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">&nbsp;</label>
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-success" value="Save">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function editFeature(feature){
            autofillForm(feature);
        }

        function autofillForm(data){
            for(key in data){
                var in_type = $('input[name="'+key+'"]').attr('type');
                if(in_type != 'file'){
                    $('input[name="'+key+'"]').val(data[key]);
                    $('textarea[name="'+key+'"]').val(data[key]);
                    $('select[name="'+key+'"]').val(data[key]);
                }
            }
        }
    </script>
@endsection
