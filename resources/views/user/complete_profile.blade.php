 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Please Complete Your Profile</div>
        </div>
        <div class="panel-body">
            @if($step==0)
            <form enctype="multipart/form-data" action="{{ URL::to('user/complete_profile') }}" class="form-horizontal" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="step" value="{{ $step }}">
                <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp;</label>
                    <div class="col-md-4">
                        <h3>Background Information</h3>
                        <hr/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Your Native Language</label>
                    <div class="col-md-4">
                        <input required type="text" value="{{ old('native_language',$profile->native_language)  }}" name="native_language" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Highest Verifiable Academic Level</label>
                    <div class="col-md-4">
                        <select required class="form-control" name="academic_id">
                        @foreach($academics as $academic)
                            <option value="{{ $academic->id }}"> {{ $academic->level }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Describe Yourself</label>
                    <div class="col-md-4">
                       <textarea required name="about" rows="10" cols="10" class="form-control">{{ old('about',$profile->about) }}</textarea>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('cv') ? 'has-error':'' }}">
                    <label class="col-md-3 control-label">Upload Your CV</label>
                    <div class="col-md-4">
                        <input required type="file" name="cv" class="form-control">
                    </div>
                    <div class="help-block">
                        @if($errors->has('cv'))
                            <strong>{{ $errors->first('cv') }}</strong>
                            @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp;</label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success">Certification &nbsp;<i class="fa fa-arrow-right"></i> </button>
                    </div>
                </div>
            </form>
                @elseif($step == 1)
                <form enctype="multipart/form-data" action="{{ URL::to('user/complete_profile') }}" class="form-horizontal" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="step" value="{{ $step }}">
                    <div class="form-group">
                        <label class="col-md-3 control-label">&nbsp;</label>
                        <div class="col-md-4">
                            <h3>Certification</h3>
                            <hr/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Give you certificate a title</label>
                        <div class="col-md-4">
                           <input required type="text" name="cert_title" class="form-control" value="{{ old('cert_title') }}">
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('cert') ? "has-error":"" }}">
                        <label class="col-md-3 control-label">Upload Certificate</label>
                        <div class="col-md-4">
                            <input required type="file" name="cert" class="form-control">
                            <div class="help-block">
                                @if($errors->has('cert'))
                                    <strong>{{ $errors->first('cert') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">&nbsp;</label>
                        <div class="col-md-4">
                            <a class="btn btn-warning" href="{{ URL::to("user/complete_profile") }}"><i class="fa fa-arrow-left"></i> Back</a> <button type="submit" class="btn btn-success">Sample Essays &nbsp;<i class="fa fa-arrow-right"></i> </button>
                        </div>
                    </div>
                </form>
            @elseif($step == 2)
                <form enctype="multipart/form-data" action="{{ URL::to('user/complete_profile') }}" class="form-horizontal" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="step" value="{{ $step }}">
                    <div class="form-group">
                        <label class="col-md-3 control-label">&nbsp;</label>
                        <div class="col-md-4">
                            <h3>Sample Essays</h3>
                            <hr/>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('title_1') ? 'has-error':'' }}">
                        <label class="col-md-3 control-label">Essay 1 Title</label>
                        <div class="col-md-4">
                            <input required type="text" name="title_1" class="form-control" value="{{ old('title_1',$profile->title_1) }}">
                            <div class="help-block">
                                @if($errors->has('title_1'))
                                    <strong>{{ $errors->first('title_1') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('file_1') ? "has-error":"" }}">
                        <label class="col-md-3 control-label">Upload Essay 1 File</label>
                        <div class="col-md-4">
                            <input required type="file" name="file_1" class="form-control">
                            <div class="help-block">
                                @if($errors->has('file_1'))
                                    <strong>{{ $errors->first('file_1') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('title_2') ? 'has-error':'' }}">
                        <label class="col-md-3 control-label">Essay 2 Title</label>
                        <div class="col-md-4">
                            <input required type="text" name="title_2" class="form-control" value="{{ old('title_2') }}">
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('file_2') ? "has-error":"" }}">
                        <label class="col-md-3 control-label">Upload Essay 2 File</label>
                        <div class="col-md-4">
                            <input required type="file" name="file_2" class="form-control">
                            <div class="help-block">
                                @if($errors->has('file_2'))
                                    <strong>{{ $errors->first('file_2') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">&nbsp;</label>
                        <div class="col-md-4">
                            <a class="btn btn-warning" href="{{ URL::to("user/complete_profile?step=1") }}"><i class="fa fa-arrow-left"></i> Back</a> <button type="submit" class="btn btn-success">Academics&nbsp;<i class="fa fa-arrow-right"></i> </button>
                        </div>
                    </div>
                </form>
            @elseif($step == 3)
                <form id="form_4" style="" class="form-horizontal pull-left" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="step" value="{{ $step }}">
                    <div class="form-group">
                        <label class="col-md-1 control-label">&nbsp;</label>
                        <div class="col-md-10">
                            <h3>Skills and Specialization Fields</h3>
                            <hr/>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('style_ids') ? ' has-error' : '' }}">
                        <label class="col-md-1 control-label">Writing Styles</label>
                        <div class="col-md-10">
                            @if ($errors->has('style_ids'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('style_ids') }}</strong>
                            </span>
                            @endif
                            @foreach($styles as $style)
                                <input type="checkbox" name="style_ids[]" value="{{ $style->id }}"> {{ $style->label }}<br/>
                            @endforeach

                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('subject_ids') ? ' has-error' : '' }}">
                        <label class="col-md-1 control-label">Academic Disciplines</label>
                        <div class="col-md-10">
                            @if ($errors->has('subject_ids'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('subject_ids') }}</strong>
                            </span>
                            @endif
                            <?php $sub_count = 0; ?>
                            @foreach($subjects as $subject)

                                @if($sub_count%4==0)
                                    <div class="row"></div>
                                @endif
                                <div class="col-md-3">
                                    <input class="" type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"> {{ $subject->label }}

                                </div>
                                <?php $sub_count++; ?>
                            @endforeach

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">&nbsp;</label>
                        <div class="col-md-4">
                            <a class="btn btn-warning" href="{{ URL::to("user/complete_profile?step=2") }}"><i class="fa fa-arrow-left"></i> Back</a> <button type="submit" class="btn btn-success">Payment&nbsp;<i class="fa fa-arrow-right"></i> </button>
                        </div>
                    </div>
                </form>
                @elseif($step==4)
                    <form enctype="multipart/form-data" action="{{ URL::to('user/complete_profile') }}" class="form-horizontal" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="step" value="{{ $step }}">
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-4">
                                <h3>Payment and Experience</h3>
                                <hr/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Ever Worked in another Company?</label>
                            <div class="col-md-4">
                                <select id="company_select" onchange="showCompany();" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div id="other_company" style="display: none;" class="form-group">
                            <label class="col-md-3 control-label">Company Name</label>
                            <div class="col-md-4">
                                <input type="" name="other_company" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Paypal Email</label>
                            <div class="col-md-4">
                                <input required type="text" name="payment_terms" class="form-control" value="{{ old('payment_terms') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-4">
                                <a class="btn btn-warning" href="{{ URL::to("user/complete_profile") }}"><i class="fa fa-arrow-left"></i> Back</a> <button type="submit" class="btn btn-success"><i class="fa fa-thumbs-up"></i> Complete &nbsp; </button>
                            </div>
                        </div>
                    </form>
                <script type="text/javascript">
                    function showCompany(){
                        var sel = $("#company_select").val();
                        if(sel==1){
                            $("#other_company").slideDown();
                        }else{
                            $("#other_company").slideUp();
                        }
                    }
                </script>
            @endif
        </div>
    </div>
@endsection