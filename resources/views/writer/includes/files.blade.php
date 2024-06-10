<div id="o_files" class="tab-pane fade">
    <h3>Files</h3>
    <div class="row">
        <div class="col-sm-11">
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

            {{--<button onclick="$('#fileform').slideToggle()" class="btn btn-primary">Add File</button>--}}
            <div class="row"></div>
            <div id="fileform" class="col-md-8" style="display:none;">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <h3>New Order File</h3>
                        <form class="form-horizontal" enctype="multipart/form-data" action="{{ URL::to('stud/order/').'/'.$order->id }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-2">File</label>
                                <div class="col-md-6">
                                    <input type="file" name="file" reaquired  class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2">Type</label>
                                <div class="col-md-6">
                                    <select name="filefor" class="form-control">
                                        <option>Order File</option>
                                        <option>Revision Material</option>
                                        <option>Reference File</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2">&nbsp;</label>
                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-info">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                <a href="{{ url("order/$order->id/files") }}" class="btn btn-primary pull-right">Download All</a>

                <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Type</th>
                    <th>Size</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->files as $file)
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
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>