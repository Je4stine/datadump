
<div id="revision_msgs" class="tab-pane fade">
    <h3>Revision Messages</h3>
    <div class="col-md-6">
        <div class="card section">
            <div class="card-header">
                <div class="panel-title">Revision Message(s) <a onclick=" $('#revbody').slideToggle('slow'); return false;" class="pull-right" style="text-decoration: none;"><i class="fa fa-toggle-down fa-2x"></i> </a> </div>
            </div>
            <div class="panel-body" id="revbody" style="">
                <div class="card section">
                    <div class="panel-body">
                        @foreach($assign->revisionMessages as $message)
                            <p class="alert alert-warning ">{{ $message->message }}<br/>
                                <small>{{ date('d M Y, h:i a',strtotime($message->created_at)) }}</small>
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

