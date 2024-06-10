 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                Return Order <strong>#{{ $order->id." ".$order->topic }} </strong>to Revision
            </div>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-5">Reason for Revision</label>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <textarea name="message" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Deadline</label>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <input name="deadline" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Revision Files</label>
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
                        <button type="submit" class="btn btn-success" value="Submit">Request Revision</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function addFiles(){
            $("#files").append('<br/><input type="file" class="form-control" name="files[]">');
        }
    </script>
@endsection