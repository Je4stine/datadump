<div id="o_files" class="tab-pane fade">
    <h3>Files</h3>
    <div class="row">
        <div class="col-sm-11">
            @if($assign->status != 3 && $assign->status != 4)
                <form class="form-horizontal" method="post" action="{{ URL::to("writer/order/$order->id/room/$assign->id") }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-2">File</label>
                        <div class="col-md-8">
                            <div id="filesform">
                                <input required type="file" class="form-control" name="files[]">
                            </div>
                            <a onclick="return addFiles();" href="#"><i class="fa fa-plus fa-lg"></i></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Type</label>
                        <div class="col-md-8">
                            <div id="filesform">
                                <select name="type" class="form-control">
                                    <option>Final Copy</option>
                                    <option>Draft</option>
                                    <option>Reference Material</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">&nbsp;</label>
                        <div class="col-md-5">
                            <input type="submit" class="btn btn-info" value="Submit">
                        </div>
                    </div>
                </form>
                <script type="text/javascript">
                    function addFiles(){
                        $("#filesform").append('<br/><input required type="file" class="form-control" name="files[]">');
                        return false;
                    }
                </script>

            @endif

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
                <a href="{{ url("order/$order->id/files?assign_id=$assign->id") }}" class="btn btn-primary pull-right">Download All</a>

                    <table class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Date</th>
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
                                <td>{{ number_format($file->filesize/1024,2) }} KB</td>
                                <td>{{ $file->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
        </div>
    </div>
</div>