<div class="col-md-12">
    <form class="form-horizontal" method="post" action="{{ url("manual/new") }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-md-2">Topic</label>
            <div class="col-md-8">
                <input type="text" name="topic" required class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2">Order Number</label>
            <div class="col-md-3">
                <input type="text" name="order_number" required class="form-control">
            </div>
            {{--<label class="control-label col-md-2">Pages</label>--}}
            {{--<div class="col-md-3">--}}
            {{--<input type="text" name="pages" required class="form-control">--}}
            {{--</div>--}}
        </div>
        <div class="form-group">
            <label class="control-label col-md-2">Pages</label>
            <div class="col-md-3">
                <input type="number" name="pages" required class="form-control">
            </div>
            <label class="control-label col-md-2">Deadline</label>
            <div class="col-md-3">
                <input type="text" name="deadline" required class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2">Additional Materials</label>
            <div class="col-md-8" id="filesform">
                <input type="file" class="form-control" name="files[]">
            </div>
            <div class="row">
                <button type="button" onclick="addFiles();" class="btn btn-xs btn-info"><i class="zmdi zmdi-plus"></i> ADD</button>
            </div>
            <script type="text/javascript">
                function addFiles(){
                    $("#filesform").append('<br/><input type="file" class="form-control" name="files[]">');
                    return false;
                }
            </script>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Paper Instructions</label>
            <div class="col-md-8">
                <textarea rows="10" name="instructions" class="form-control"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">&nbsp;</label>
            <div class="col-md-8">
                <button type="submit" class="btn btn-success form-control">Submit</button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    tinymce.init({
        selector: 'textarea',
        height: 300,
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });
</script>