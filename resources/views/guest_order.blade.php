 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Add New Order</div>
        </div>
        <div class="panel-body">
            <form method="post" action="" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-md-3">Topic</label>
                    <div class="col-md-5">
                        <input required type="text" name="topic" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Subject</label>
                    <div class="col-md-5">
                        <select required name="subject" class="form-control">
                            <option value="">Select..</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->label }}.</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Document</label>
                    <div class="col-md-5">
                        <select required name="document" class="form-control">
                            <option value="">Select..</option>
                            @foreach($docs as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Academic Level</label>
                    <div class="col-md-5">
                        <select name="academic_level" required class="form-control">
                            <option value="">Select</option>
                            <option value="high_school">High School</option>
                            <option value="under_graduate">Undergraduate</option>
                            <option value="masters">Masters</option>
                            <option value="phd">Ph.D</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Pages</label>
                    <div class="col-md-2">
                        <input type="number" required value="1" class="form-control" name="pages">
                    </div>
                    <label class="control-label col-md-1">Sources</label>
                    <div class="col-md-2">
                        <input type="number" required value="0" class="form-control" name="sources">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Style</label>
                    <div class="col-md-2">
                        <select name="style" class="form-control">
                            <option>APA</option>
                            <option>MLA</option>
                            <option>Harvard</option>
                            <option>Chicago</option>
                        </select>
                    </div>
                    <label class="control-label col-md-1">Language</label>
                    <div class="col-md-2">
                        <select name="language" class="form-control">
                            <option>English (U.S)</option>
                            <option>English (U.K)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Urgency</label>
                    <div class="col-md-2">
                        <select required name="urgency" class="form-control">
                            <option value="">Select..</option>
                            @foreach($urgencies as $urgency)
                                <option value="{{ $urgency->id }}">{{ $urgency->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="control-label col-md-1">Spacing</label>
                    <div class="col-md-2">
                        <select name="spacing" class="form-control">
                            <option value="2">Double</option>
                            <option value="1">Single</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Instructions</label>
                    <div class="col-md-5">
                        <textarea required class="form-control" name="instructions"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">&nbsp;</label>
                    <div class="col-md-5">
                        <a class="btn btn-warning" href="{{ URL::to('/order') }}"><i class="fa fa-times"></i> Cancel</a>&nbsp;
                        <button onclick="" type="submit" class="btn btn-success"><i class="fa fa-check"></i> Preview</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">

    </script>
@endsection