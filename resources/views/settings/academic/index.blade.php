 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                All Academic Levels
                <a href="#general_adjust_modal" data-toggle="modal" class="btn btn-success">Adjust Rates</a>
                <a href="{{ URL::to('settings/academic/add') }}" class="btn btn-info pull-right"><i class="fa fa-plus"></i> Add New</a> </div>
        </div>
        <div class="panel-body">
            <table class="table table-condensed table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Level</th>
                    <th>Rates</th>
                    <th>Action</th>
                </tr>

                @foreach($academics as $academic)
                    <tr>
                        <td>{{ $academic->id }}</td>
                        <td>{{ $academic->level }}</td>
                        <td>
                            <table class="table table-bordered">
                                <tr>
                                    <th>#</th>
                                    <th>Hours</th>
                                    <th>Label</th>
                                    <th>cpp</th>
                                    <th>cpc</th>
                                    <th>cppt</th>
                                    <th>
                                        <button onclick="return showRow('{{ $academic->id }}');" class="label label-info"><i class="fa fa-plus"></i> Add</button>
                                    </th>
                                </tr>
                                <?php $no  = 0;  ?>
                                @foreach($rates = $academic->rates()->where('deleted','=',0)->get() as $rate)
                                   <?php $no++; ?>
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $rate->hours }}</td>
                                        <td>{{ $rate->label }}</td>
                                        <td>${{ $rate->cost }}</td>
                                        <td>${{ $rate->chart }}</td>
                                        <td>${{ $rate->ppt }}</td>
                                        <td>
                                            <a onclick="return editRate('{{ $academic->id }}','{{ $rate->id }}','{{ $rate->hours }}','{{ $rate->label }}','{{ $rate->cost }}','{{ $rate->chart }}','{{ $rate->ppt }}');" href="#"><i class="fa fa-edit"></i></a> |
                                            <a onclick="return confirm('Are you sure?');" style="color: red;" href="{{ URL::to("settings/academic/rates/delete/$rate->id") }}"><i class="fa fa-remove"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                <tr style="display: none;" id="rate_row{{ $academic->id }}">
                                <form method="post" action="{{ URL::to("settings/academic/$academic->id/add_rate") }}" id="rate_form{{ $academic->id }}">
                                   {{ csrf_field() }}
                                    <td><input id="id_{{ $academic->id }}" type="hidden" name="id"> </td>
                                    <td><input id="hours_{{ $academic->id }}" class="form-control" type="text" name="hours" value=""></td>
                                    <td><input id="label_{{ $academic->id }}" class="form-control" type="text" name="label"> </td>
                                    <td><input id="cost_{{ $academic->id }}" class="form-control" type="text" name="cost"> </td>
                                    <td><input id="chart_{{ $academic->id }}" class="form-control" type="text" name="chart"> </td>
                                    <td><input id="ppt_{{ $academic->id }}" class="form-control" type="text" name="ppt"> </td>
                                    <td><button class="label label-success" type="submit"><i class="fa fa-check"></i> </button> <button onclick="return hideRow('{{ $academic->id }}');" class="label label-danger"><i class="fa fa-remove"></i> </button> </td>
                                </form>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <a href="{{ URL::to("settings/academic/edit/$academic->id") }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit</a>
                            <a onclick="return confirm('Are you sure?');" href="{{ URL::to("settings/academic/delete/$academic->id") }}" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i> Delete</a>
                        </td>
                    </tr>
                    @endforeach
            </table>
            {{--{{ $academics->links() }}--}}
        </div>
    </div>
    </div>

    <script type="text/javascript">
        function hideRow(id){
            $("#rate_row"+id).slideUp();
            $("#id_"+id).val('');
            $("#hours_"+id).val('');
            $("#label_"+id).val('');
            $("#cost_"+id).val('');
            $("#chart_"+id).val('');
            $("#ppt_"+id).val('');
            return false;
        }
        function showRow(id){
            $("#rate_row"+id).slideDown('slow');
            $("#id_"+id).val('');
            $("#hours_"+id).val('');
            $("#label_"+id).val('');
            $("#cost_"+id).val('');
            $("#chart_"+id).val('');
            $("#ppt_"+id).val('');
            return false;
        }
        function editRate(rowid,id,hours,label,cost,chart,ppt){
            $("#rate_row"+rowid).slideDown('slow');
            $("#id_"+rowid).val(id);
            $("#hours_"+rowid).val(hours);
            $("#label_"+rowid).val(label);
            $("#cost_"+rowid).val(cost);
            $("#chart_"+rowid).val(chart);
            $("#ppt_"+rowid).val(ppt);
            return false;
        }
    </script>
    <div class="modal fade" id="general_adjust_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        Adjust All Rates
                        <a data-dismiss="modal" class="btn btn-danger pull-right">&times;</a>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal ajax-post" method="post" action="{{ URL::to("settings/academic/adjust-all") }}">
                        <div class="form-group">
                            {{ csrf_field() }}
                            <label class="control-label col-md-2">Method</label>
                            <div class="col-md-6">
                                <select name="type" class="form-control">
                                    <option value="percent">Percentage</option>
                                    <option value="money">Money</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Amount</label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="amount" max="100" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">&nbsp;</label>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection