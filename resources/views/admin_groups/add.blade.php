 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Admin Group</div>
        </div>
        <div class="panel-body">
           <form class="form-horizontal" method="post">
               {{ csrf_field() }}
               <div class="form-group">
                   <label class="control-label col-md-3">Name</label>
                   <div class="col-md-4">
                       <input required class="form-control" type="text" name="name" value="{{ $admin_group->name }}">
                   </div>
               </div>
               <div class="form-group">
                   <label class="control-label col-md-3">Description</label>
                   <div class="col-md-4">
                       <textarea required name="description" class="form-control">{{ $admin_group->description }}</textarea>
                   </div>
               </div>
               <div class="form-group">
                   <label class="control-label col-md-3">Permissions</label>
                   <div class="col-md-4">
                       <?php $existing = json_decode($admin_group->permissions);  ?>
                       @foreach($admin_menus as $permission)
                           <input {{ in_array($permission->slug,$existing) ? "checked":"" }} name="permissions[]" type="checkbox" value="{{ $permission->slug }}">{{ $permission->name }}<br/>
                           @endforeach
                   </div>
               </div>
               <div class="form-group">
                   <label class="control-label col-md-3">&nbsp;</label>
                   <div class="col-md-4">
                       <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                   </div>
               </div>
           </form>
        </div>
    </div>
@endsection
