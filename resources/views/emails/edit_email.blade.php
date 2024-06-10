 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                {{ $email->name }}
            </div>
        </div>
        <div class="panel-body">
            <form action="" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="">
                <div class="form-group">
                    <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Send</button>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-1">Subject</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="subject" placeholder="Email Subject" required>
                    </div>                 
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-1">Users</label>
                    <div class="col-md-4">
                        <input id="email_users" type="text" class="form-control" name="users">
                    </div>
                </div>
                <div class="row"></div>
                <textarea name="template"><?php echo $email->template ?></textarea>
            </form>
        </div>
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
        function setTemplate(id,template){
            alert(template);
            $("input[name='id']").val(id);
            $("textarea[name='template']").html(template);
        }

        $(function() {

            var members =  $('#email_users').magicSuggest({
                valueField: 'id',
                data:<?php echo json_encode($all_users) ?>,
                displayField: 'name'
            });
            members.setValue([<?php echo implode(',',$ids) ?>]);

        });
    </script>
@endsection
