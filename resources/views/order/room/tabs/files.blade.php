<div role="tabpanel" class="tab-pane" id="room_files">

<?php
$images = array(
    'pdf'=>'http://cdn1.iconfinder.com/data/icons/CrystalClear/128x128/mimetypes/pdf.png',
    'doc'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Word.png',
    'docx'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Word.png',
    'ppt'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20PowerPoint.png',
    'csv'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Excel.png',
    'xls'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Excel.png',
    'xlsx'=>'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Excel.png',
    'txt'=>'http://cdn1.iconfinder.com/data/icons/CrystalClear/128x128/mimetypes/txt2.png',
    'zip'=>'http://www.softnuke.com/wp-content/uploads/2012/10/winrar1.png'
);
?>
@if(Auth::user()->isAllowedTo('upload_file'))

    <form class="form-horizontal" method="post" action="{{ URL::to("order/$order->id/room/$assign->id") }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-md-2">File</label>
            <div class="col-md-8">
                <div id="filesform">
                    <input type="file" class="form-control" name="files[]">
                </div>
                <a onclick="return addFiles();" href="#"><i class="fa fa-plus fa-lg"></i></a>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2">Type</label>
            <div class="col-md-8">
                <div id="filesform">
                    <select name="type" class="form-control">
                        <option>Reference Material</option>
                        <option>Final copy</option>
                        <option>Draft</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2">&nbsp;</label>
            <div class="col-md-5">
                <button type="submit" class="btn btn-info">Upload</button>
            </div>
        </div>
    </form>
@endif
<script type="text/javascript">
    function addFiles(){
        $("#filesform").append('<br/><input type="file" class="form-control" name="files[]">');
        return false;
    }
</script>
    <a href="{{ url("order/$order->id/files?assign_id=$assign->id") }}" class="btn btn-primary pull-right">Download All</a>

    <table class="table table-bordered table-condensed">
    <thead>
    <tr>
    <div role="tabpanel" class="tab-pane" id="order_details">
    <th>#</th>
        <th>File Name</th>
        <th>Type</th>
        <th>Size</th>
        <th>Date</th>
        @if(Auth::user()->isAllowedTo('allow_file'))
            <th>Allow Client?</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($files as $file)
        <?php
        $image = @$images[$file->file_type];
        if(!$image){
        $image = "http://cdn1.iconfinder.com/data/icons/CrystalClear/128x128/mimetypes/txt2.png";
        }
        ?>
        <tr>
            <td>{{ $file->id }}</td>
            <td><a target="_blank" href="{{ URL::to('/order/download/').'/'.$file->id }}"><img height="20px;" src="{{ $image  }}">{{ $file->filename }}</a> </td>
            <td>{{ $file->file_for }}</td>
            <td>{{ @number_format($file->filesize/1024,2) }} KB</td>
            <td>{{ $file->created_at }}</td>
            @if(Auth::user()->isAllowedTo('allow_file'))
                <td>
                    <input {{ $file->allow_client ? "checked":"" }} onclick="return allowFile('{{ $file->id }}')" type="checkbox" class="checkbox">
                </td>
            @endif
        </tr>
    @endforeach

    </tbody>
</table>
</div>
<script type="text/javascript">
    function allowFile(id){
            var url = '{{ URL::to('order/allowfile') }}'+'/'+id;
            $.get(url,null,function(response){
                var status = JSON.parse(response);
                if(status.success){
                    return true;
                }else{
                    return false;
                }

            });
        }
</script>