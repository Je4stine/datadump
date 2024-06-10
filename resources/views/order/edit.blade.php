 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Edit Order#: <strong>{{ $order->id }}</strong></div>
        </div>
        <div class="panel-body">
            <form method="post" action="{{ URL::to('/order/').'/'.$order->id }}" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group">
                    <label class="control-label col-md-3">Topic</label>
                    <div class="col-md-5">
                        <input type="text" required value="{{ $order->topic  }}" name="topic" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Subject</label>
                    <div class="col-md-5">
                        <select name="subject" class="form-control">
                            <option>{{ $order->subject  }}</option>
                            <option>IT</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Document</label>
                    <div class="col-md-5">
                        <select name="document" class="form-control">
                            <option selected>{{ $order->document  }}</option>
                            <option>Essay</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Spacing</label>
                    <div class="col-md-5">
                        <select name="spacing" class="form-control">
                            <option selected value="{{ $order->spacing  }}">{{ $order->spacing  }}</option>
                            <option>Double</option>
                            <option>Single</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Pages</label>
                    <div class="col-md-2">
                        <input required value="{{ $order->pages  }}" type="number" aria-valuenow="0" class="form-control" name="pages">
                    </div>
                    <label class="control-label col-md-1">Sources</label>
                    <div class="col-md-2">
                        <input value="{{ $order->sources  }}" type="number" aria-valuenow="0" class="form-control" name="sources">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Style</label>
                    <div class="col-md-2">
                        <select name="style" class="form-control">
                            <option selected>{{ $order->style->label  }}</option>
                            <option>APA</option>
                            <option>MLA</option>
                            <option>Harvard</option>
                            <option>Chicago</option>
                        </select>
                    </div>
                    <label class="control-label col-md-1">Language</label>
                    <div class="col-md-2">
                        <select name="language" class="form-control">
                            <option selected>{{ $order->language->label  }}</option>
                            <option>English (U.S)</option>
                            <option>English (U.K)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Deadline</label>
                    <div class="col-md-2">
                        <input required value="{{ $order->deadline  }}" type="datetime" class="form-control" name="deadline">
                    </div>
                    <label class="control-label col-md-1">Amount</label>
                    <div class="col-md-2">
                        <input required value="{{ $order->amount  }}" type="text" name="amount" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Instructions</label>
                    <div class="col-md-5">
                        <textarea required class="form-control" name="instructions">{{ $order->instructions  }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">&nbsp;</label>
                    <div class="col-md-5">
                        <a class="btn btn-warning" href="{{ URL::to('/order/').'/'.$order->id }}"><i class="fa fa-times"></i> Cancel</a>&nbsp;
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection