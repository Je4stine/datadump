 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Website Details</div>
        </div>
        <div class="panel-body">
            <div class="alert alert-danger">These settings are very important and using wrong config might make your wbesite go down</div>
            <form class="form-horizontal" method="post" action="{{ URL::to('websites/add') }}">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $website->id }}">
                <div class="form-group">
                    <label class="control-label col-md-3">Name</label>
                    <div class="col-md-4">
                        <input required type="text" value="{{ $website->name }}" name="name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Home Url</label>
                    <div class="col-md-4">
                        <input value="{{ $website->home_url }}" required type="text" name="home_url" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="telephone" class="control-label col-md-3">Telephone</label>
                    <div class="col-md-4">
                        <input value="{{ $website->telephone }}" required type="text" name="telephone" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="telephone" class="control-label col-md-3">E-mail</label>
                    <div class="col-md-4">
                        <input value="{{ $website->email }}" required type="email" name="email" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="telephone" class="control-label col-md-3">Mail Password</label>
                    <div class="col-md-4">
                        <input value="{{ $website->password }}" required type="text" name="password" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="telephone" class="control-label col-md-3">Mail Host</label>
                    <div class="col-md-4">
                        <input value="{{ $website->host }}" required type="text" name="host" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="telephone" class="control-label col-md-3">Mail Encryption</label>
                    <div class="col-md-4">
                        <input value="{{ $website->encryption }}" type="text" name="encryption" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="telephone" class="control-label col-md-3">Mail Port</label>
                    <div class="col-md-4">
                        <input value="{{ $website->port }}" required type="number" name="port" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Role</label>
                    <div class="col-md-4">
                        <select onchange="checkWriter();" name="role" required class="form-control">
                            <option {{ $website->role=='writer' ? "selected":"" }} value="writer">Writer</option>
                            <option {{ $website->role=='client' ? "selected":"" }} value="client">Client</option>
                            <option {{ $website->role=='admin' ? "selected":"" }} value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div id="late_fine" style="display: none;" class="form-group">
                    <label class="control-label col-md-3">Late Fine(%)</label>
                    <div class="col-md-4">
                        <input type="text" name="fine" value="{{ $website->fine }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Layout</label>
                    <div class="col-md-4">
                        <input type="text" name="layout" value="{{ $website->layout }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Deposit Amount</label>
                    <div class="col-md-4">
                        <input type="number" max="100" min="1" name="deposit" value="{{ $website->deposit }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">&nbsp;</label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Website</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script type="text/javascript">
        function checkWriter(){
            var val = $("select[name='role']").val();
            if(val=='writer'){
                $("#late_fine").slideDown();
            }else{
                $("#late_fine").slideUp();
            }
        }
    </script>
@endsection
