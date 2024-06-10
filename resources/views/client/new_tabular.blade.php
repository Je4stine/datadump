@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
    $get_data = Request:: all();
    ?>
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Order Details</div>
        </div>
        <div class="panel-body">
            {{--<div style="margin: 0px !important;" class="col-md-3 pull-right row">--}}
                {{--<img style="pading: 0px;margin: 0px;" src="http://www.homeworkmake.com/wp-content/uploads/2016/07/banner-6.png">--}}
            {{--</div>--}}
            <div style="margin: 0px !important;" class="col-md-13">
                <form method="post" action="{{ URL::to('/stud/new') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-3">Academic Level</label>
                        <div class="col-md-9">
                            <?php $no=0;  ?>
                            @foreach($academic_levels as $academic_level)
                                <input onclick="setAcademic({{ $academic_level->id }})" type="radio" id="radio{{ $academic_level->id }}" name="academic_id" value="{{ $academic_level->id }}" {{ @$get_data['academic_id'] ? @$get_data['academic_id']==$academic_level->id ? "checked":"" :$no==0 ? "checked":"" }}>
                                <label for="radio{{ $academic_level->id }}">{{ $academic_level->level }}</label>
                                <?php $no++; ?>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="preview" value="1">
                    <div class="form-group">
                        <label class="control-label col-md-3">Topic</label>
                        <div class="col-md-8">
                            <input required placeholder="Your order topic" type="text" name="topic" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Subject</label>
                        <div class="col-md-8">
                            <select onchange="getOrderCost();" name="subject_id" class="form-control">
                                @foreach($subjects as $subject)
                                    <option {{ @$get_data['subject_id']==$subject->id ? "selected":"" }} value="{{ $subject->id }}">{{ $subject->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Document</label>
                        <div class="col-md-8">
                            <select onchange="getOrderCost();" name="document_id" class="form-control">
                                @foreach($documents as $document)
                                    <option {{ @$get_data['document_id']==$document->id ? "selected":"" }} value="{{ $document->id }}">{{ $document->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Pages</label>
                        <div class="col-md-3">
                            <input onchange="getOrderCost();" type="number" required min="1" value="{{ @$get_data['pages'] ? @$get_data['pages']:"1" }}" class="form-control" name="pages">
                        </div>
                        <label class="control-label col-md-2">Sources</label>
                        <div class="col-md-3">
                            <input type="number" required min="0" value="0" class="form-control" name="sources">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Style</label>
                        <div class="col-md-3">
                            <select onchange="getOrderCost();" name="style_id" class="form-control">
                                @foreach($styles as $style)
                                    <option value="{{ $style->id }}">{{ $style->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="control-label col-md-2">Language</label>
                        <div class="col-md-3">
                            <select name="language_id" onchange="getOrderCost();" class="form-control">
                                @foreach($languages as $language)
                                    <option value="{{ $language->id }}">{{ $language->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Urgency</label>
                        <div class="col-md-3">
                            <select onchange="getOrderCost();" required name="rate_id" class="form-control">
                            </select>
                        </div>
                        <label class="control-label col-md-2">Spacing</label>
                        <div class="col-md-3">
                            <select onchange="getOrderCost();" name="spacing" class="form-control">
                                <option value="2">Double</option>
                                <option value="1">Single</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Writer Category</label>
                        <div class="col-md-9">
                            <?php $no=0; ?>
                            @foreach($writer_categories as $writer_category)
                                <input onclick="getOrderCost()" type="radio" id="radio_wtr{{ $writer_category->id }}" name="writer_category_id" value="{{ $writer_category->id }}" {{ $no==0 ? "checked":"" }}>
                                <label for="radio_wtr{{ $writer_category->id }}">{{ $writer_category->name }}</label>
                                <?php $no++; ?>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Instructions</label>
                        <div class="col-md-8">
                            <textarea required class="form-control" name="instructions"></textarea>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <label class="control-label col-md-3">&nbsp</label>
                        <div class="col-md-8">
                            <p>*Order files will be uploaded in the order page</p>
                        </div>
                    </div>
                    <input type="hidden" name="partial" value="{{ @$get_data['partial'] }}">
                    @if(!$website->admin_quote)
                    @if(!@$get_data['partial'])
                    <div class="form-group" style="">
                        <label class="control-label col-md-3">Total Cost</label>
                        <div class="col-md-8">
                            <div class="col-md-4">
                                <select class="form-control" name="currency_id" id="currency_select" onchange="changeCurrency();">
                                    <?php foreach($currencies as $currency): ?>
                                    <option {{ $currency->usd_rate==1 ? "selected":"" }} value="<?php echo $currency->id ?>"><?php echo $currency->abbrev ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input type="hidden" id="total_price">
                                <input type="text" disabled id="foreign_currency" class="form-control">
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="form-group" style="">
                        <label class="control-label col-md-3">Deposit Amount</label>
                        <div class="col-md-8">
                        <div class="col-md-4">
                                <select class="form-control" name="currency_id" id="currency_select" onchange="changeCurrency();">
                                    <?php foreach($currencies as $currency): ?>
                                    <option {{ $currency->usd_rate==1 ? "selected":"" }} value="<?php echo $currency->id ?>"><?php echo $currency->abbrev ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input type="hidden" id="total_price">
                                <input type="text" disabled id="deposit_amt" class="form-control">
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-8">
                            <a class="btn btn-warning" href="{{ URL::to('/order') }}"><i class="fa fa-times"></i> Cancel</a>&nbsp;
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Preview</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style type="text/css">
        /*
  Hide radio button (the round disc)
  we will use just the label to create pushbutton effect
*/
        input[type=radio] {
            display:none;
            margin:10px;
        }

        /*
          Change the look'n'feel of labels (which are adjacent to radiobuttons).
          Add some margin, padding to label
        */
        input[type=radio] + label {
            display:inline-block;
            margin:-2px;
            padding: 4px 12px;
            background-color: #e7e7e7;
            border-color: #ddd;
        }
        /*
         Change background color for label next to checked radio button
         to make it look like highlighted button
        */
        input[type=radio]:checked + label {
            background-image: none;
            background-color:#d0d0d0;
        }
        div + p {
            color: red;
        }

        input[type=radio] {
            display:none;
        }

        input[type=radio] + label {
            display:inline-block;
            margin:-2px;
            padding: 4px 12px;
            margin-bottom: 0;
            font-size: 14px;
            line-height: 20px;
            color: #333;
            text-align: center;
            text-shadow: 0 1px 1px rgba(255,255,255,0.75);
            vertical-align: middle;
            cursor: pointer;
            background-color: #f5f5f5;
            background-image: -moz-linear-gradient(top,#fff,#e6e6e6);
            background-image: -webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));
            background-image: -webkit-linear-gradient(top,#fff,#e6e6e6);
            background-image: -o-linear-gradient(top,#fff,#e6e6e6);
            background-image: linear-gradient(to bottom,#fff,#e6e6e6);
            background-repeat: repeat-x;
            border: 1px solid #ccc;
            border-color: #e6e6e6 #e6e6e6 #bfbfbf;
            border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
            border-bottom-color: #b3b3b3;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
            -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
            -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
        }

        input[type=radio]:checked + label {
            background-image: none;
            outline: 0;
            -webkit-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
            -moz-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
            background-color:#e0e0e0;
        }
    </style>

    <script type="text/javascript">
        var settings = JSON.parse('<?php echo  str_replace("'","",json_encode($settings)) ?>');
        var checked = $("input[name='academic_id']:checked").val();
        var rate = null;
        var initial_rate = '{{ @$get_data['rate_id'] }}';

        setAcademic(checked);
        if(initial_rate){
            $("input[name='rate_id']").val(initial_rate);
        }
        $(function() {

            $('#subject_field').magicSuggest({
                data:<?php echo  str_replace("'","",json_encode($subjects)) ?>,
                valueField: 'id',
                displayField: 'label',
                maxSelection: 1
            });
            $('#document_field').magicSuggest({
                data:<?php echo  str_replace("'","",json_encode($documents)) ?>,
                valueField: 'id',
                displayField: 'label',
                maxSelection: 1
            });

        });
        function setAcademic(id){
            $("select[name='rate_id']").html('');
            var academics = this.settings.academics;
            var rates = this.settings.rates;
            for(var i =0;i<rates.length;i++){
                var rate = rates[i];
                if(rate.academic_id==id && rate.deleted==0){
                    $("select[name='rate_id']").append('<option value="'+rate.id+'">'+rate.label+'</option>');
                }
            }
            getOrderCost();
        }



        function getRate(){
            var rate_id =  $("select[name='rate_id']").val();
            var rates = this.settings.rates;
            var found = null;
            for(var i=0;i<rates.length;i++){
                var rate = rates[i];
                if(rate.id==rate_id){
                    found = rate;
                    break;
                }
            }
//            console.log(found);
            return found;
        }
        function getOrderCost(){
            var pages = $("input[name='pages']").val();
            var spacing_val = $("select[name='spacing']").val();
            var spacing = 1;
            if(spacing_val=='2'){
                spacing = 1
            }else{
                spacing = 2;
            }
            console.log(spacing);
            var cpp = getRate().cost;
            var flat_rate = cpp*spacing;
            var subject = getSubject();
            var document = getDocument();
            var language = getLanguage();
            var writer = getWriter();
            var style = getStyle();
            var subject_increase = 0;
            var document_increase = 0;

            /**
             * Calculate increment by document type
             */
            if(document.inc_type=='percent'){
                console.log(flat_rate);
                document_increase = flat_rate*((parseFloat(document.amount))/100);
            }else if(document.inc_type=='money'){
                document_increase = parseFloat(document.amount);
            }


            /**
             * Calculate increment by subject
             */
            if(subject.inc_type=='percent'){
                subject_increase = flat_rate*((parseFloat(subject.amount))/100);
            }else if(subject.inc_type=='money'){
                subject_increase = parseFloat(subject.amount);
            }

            /**
             * Calculate increment by style
             */
            if(style.inc_type=='percent'){
                style_increase = flat_rate*((parseFloat(style.amount))/100);
            }else if(style.inc_type=='money'){
                style_increase = parseFloat(style.amount);
            }

            /**
             * Calculate increment by language
             */
            if(language.inc_type=='percent'){
                language_increase = flat_rate*((parseFloat(language.amount))/100);
            }else if(language.inc_type=='money'){
                language_increase = parseFloat(language.amount);
            }

            /**
             * Calculate increment by writer
             */
            if(writer.inc_type=='percent'){
                writer_increase = flat_rate*((parseFloat(writer.amount))/100);
            }else if(writer.inc_type=='money'){
                writer_increase = parseFloat(writer.amount);
            }

            var total_cost = (flat_rate+subject_increase+document_increase+language_increase+style_increase+writer_increase)*pages;

            $("#total_price").val(total_cost.toFixed(2));
            changeCurrency();
        }

        function getSubject(){
            var subject_id = $("select[name='subject_id']").val();
            var subjects = this.settings.subjects;
            var found = null;
            for(var i=0;i<subjects.length;i++){
                var subject = subjects[i];
                if(subject.id==subject_id){
                    found = subject;
                    break;
                }
            }
            return found;
        }
        function getWriter(){
            var id = $("input[name='writer_category_id']:checked").val();
            var writer_categories = this.settings.writer_categories;
            var writer = null;
            for(var i =0; i<writer_categories.length;i++){
                if(writer_categories[i].id==id){
                    writer = writer_categories[i];
                }
            }
            return writer;
        }
        function getDocument(){
            var document_id = $("select[name='document_id']").val();
            var documents = this.settings.documents;
            var found = null;
            for(var i=0;i<documents.length;i++){
                var document = documents[i];
                if(document.id==document_id){
                    found = document;
                    break;
                }
            }
            return found;
        }

        function getLanguage(){
            var language_id = $("select[name='language_id']").val();
            var languages = this.settings.languages;
            var found = null;
            for(var i=0;i<languages.length;i++){
                var language = languages[i];
                if(language.id==language_id){
                    found = language;
                    break;
                }
            }
            return found;
        }

        function getStyle(){
            var style_id = $("select[name='style_id']").val();
            var styles = this.settings.styles;
            var found = null;
            for(var i=0;i<styles.length;i++){
                var style = styles[i];
                if(style.id==style_id){
                    found = style;
                    break;
                }
            }
            return found;
        }
        function changeCurrency(){
            var selected = $("#currency_select").val();
            var original = $("#total_price").val();
            console.log(original,selected);
            var currencies = this.settings.currencies;
            for(i=0;i<currencies.length;i++){
                var currency = currencies[i];
                if(currency.id==selected){
                    var new_amt = parseFloat(original)*parseFloat(currency.usd_rate);
                    $("#foreign_currency").val(new_amt.toFixed(2)+' '+currency.abbrev);
                    $("#deposit_amt").val((new_amt*0.3).toFixed(2)+' '+currency.abbrev);
                }
            }
        }
    </script>
    <style type="text/css">
        .form-group{
            margin-right:0px !important;
            padding-right: 0px !important;
        }
    </style>
@endsection