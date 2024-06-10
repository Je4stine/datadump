 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                Dispute Order: <strong>#{{ $order->id." ".$order->topic }} </strong>to Revision
            </div>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-5">Reason for Dispute:</label>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <textarea name="reason" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Preferred Action<small> (Please let us know what we can do to resolve order issue)</small></label>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <select id="action" required onchange="checkSelected();" name="action" class="form-control">
                            <option value="">Select..</option>
                            <option value="Revision">Return to revision</option>
                            <option value="Change Writer">Change Writer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div id="other_action" style="display: none;" class="form-group">
                    <div class="col-md-5">
                        <input type="text" placeholder="Please specify action to be taken" name="other" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Attach Files</label>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <div id="files">
                            <input type="file" class="form-control" name="files[]">
                        </div>
                        <a onclick="return addFiles();" href="#"><i class="fa fa-plus fa-lg"></i></a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <input type="submit" class="btn btn-info" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function checkSelected(){
            var action = $("#action").val();
            if(action=='other'){
                $("#other_action").slideDown();
            }else{
                $("#other_action").slideUp();
            }
        }
        function addFiles(){
            $("#files").append('<br/><input type="file" class="form-control" name="files[]">');
        }
    </script>
@endsection