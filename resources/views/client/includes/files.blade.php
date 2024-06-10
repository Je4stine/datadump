<?php
$extensions = ['doc','docx'];
?>
    <div role="tabpanel" class="tab-pane" id="o_files">

    @if($order->payments()->sum('amount')<$order->amount && $order->status==4)
        <div class="alert alert-info" style="font-size:medium;">
            Kindly, go through the file preview.
            If you are satisfied, then please Pay the remaining {{ "$".($order->amount-$order->payments()->sum('amount')) }} and approve the order so as to download the MS Word version.
            If you need any changes, then let us know by clicking on Revise. You are entitled to free revisions within 14 days after the completion of the paper.
            Thank you for ordering from us.
            <div class="row"></div>
            <a class="btn btn-success btn-sm" href="{{ URL::to('stud/pay/'.''.$order->id) }}"><i class="fa fa-paypal"></i> Pay Pending({{ @number_format($order->amount-$order->payments()->sum('amount'),2) }})</a>
            <a class="btn btn-danger btn-sm" href="{{ URL::to('stud/dispute/'.''.$order->id) }}"><i class="fa fa-thumbs-down"></i> Revise</a>
        </div>
        @elseif($order->status==4)
        <div class="alert alert-info" style="font-size:medium;">
            Kindly, go through the file preview.
            If you are satisfied, please approve the order so as to download the MS Word version.
            If you need any changes, then let us know by clicking on Revise. You are entitled to free revisions within 14 days after the completion of the paper.
            Thank you for ordering from us.
            <div class="row"></div>
                                        <a class="btn btn-success btn-xs" href="{{ URL::to('stud/approve/'.''.$order->id) }}"><i class="fa fa-thumbs-up"></i> Approve</a>

            <a class="btn btn-danger btn-sm" href="{{ URL::to('stud/dispute/'.''.$order->id) }}"><i class="fa fa-thumbs-down"></i> Revise</a>
        </div>
    @endif
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
            $taken_files = [];
            ?>
            <button onclick="$('#fileform').slideToggle()" class="btn btn-primary">Add File</button>
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
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
                    $taken_files[] = $file->id;

                    ?>
                    <tr>
                        <td>{{ $file->id }}</td>
                        <td>
                            @if($order->partial && $order->amount>$order->payments()->sum('amount') && in_array($file->file_type,$extensions) && $file->user_id != Auth::user()->id)
                            <a target="_blank" href="{{ URL::to('/order/download/').'/'.$file->id.'/preview' }}"><img height="20px;" src="{{ $image  }}">{{ $file->filename }}</a>
                            @elseif($order->status == 4 && $file->file_type != 'pdf' && $file->user_id != Auth::user()->id)
                                <a target="_blank" href="{{ URL::to('/order/download/').'/'.$file->id.'/preview' }}"><img height="20px;" src="{{ $image  }}">{{ $file->filename }}</a>
                            @else
                                <a target="_blank" href="{{ URL::to('/order/download/').'/'.$file->id }}"><img height="20px;" src="{{ $image  }}">{{ $file->filename }}</a>
                            <!-- @endif -->
                        </td>
                        <td>{{ $file->file_for }}</td>
                        <td>{{ @number_format(@$file->filesize/1024,2) }} KB</td>
                    </tr>
                @endforeach
                @foreach(\App\File::whereIn('assign_id',$assign_ids)->whereNotIn('id',$taken_files)->where([
                    ['allow_client','=',1]
                ])->get() as $file)
                    <?php
                    $image = @$images[$file->file_type];
                    if(!$image){
                    $image = "http://cdn1.iconfinder.com/data/icons/CrystalClear/128x128/mimetypes/txt2.png";
                    }

                    ?>
                    <tr>
                        <td>{{ $file->id }}</td>
                        <td>
                            @if($order->partial && $order->amount>$order->payments()->sum('amount') && in_array($file->file_type,$extensions) && $file->user_id != Auth::user()->id)
                            <a target="_blank" href="{{ URL::to('/order/download/').'/'.$file->id.'/preview' }}"><img height="20px;" src="{{ $image  }}">{{ $file->filename }}</a>
                            @elseif($order->status == 4 && $file->file_type != 'pdf' && $file->user_id != Auth::user()->id)
                                <a target="_blank" href="{{ URL::to('/order/download/').'/'.$file->id.'/preview' }}"><img height="20px;" src="{{ $image  }}">{{ $file->filename }}</a>
                            @else
                                <a target="_blank" href="{{ URL::to('/order/download/').'/'.$file->id }}"><img height="20px;" src="{{ $image  }}">{{ $file->filename }}</a>
                            @endif
                        </td>
                        <td>{{ $file->file_for }}</td>
                        <td>{{ @number_format(@$file->filesize/1024,2) }} KB</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>