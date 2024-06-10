<script type="text/javascript">
    
    function discard()
    {
        tinyMCE.triggerSave();
        var text=$("#textarea").val();
        var id=$("input[name='id']").val();
        if(text===""){
            $("#error-msg").text("Field required").show().delay(2000).fadeOut();
//                $("#myElem").show().delay(5000).fadeOut();

            return false;
        }
        else {
            if (id < 1) {
                tinyMCE.activeEditor.setContent('');
                $("input[name='id']").val("");
                return false;
            }
            else {
                var url = "{{ URL::to('articles/delete') }}/" + id;
                var data = $("#article_form").serialize();
                $.get(url, data, function (response) {

                    $("#textarea").val("");
                    $("#success-msg").text("draft message deleted").show().delay(2000).fadeOut();
                });
                return false;
            }
        }
    }

    /*set the minimum height for the new messsage textarea*/
    document.getElementById("textarea").style.minHeight = "100px";


    jQuery(document).ready(function() {
        // $("time.timeago").timeago();
        $('textarea').each(function () {
        this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
    }).on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    //     $(".excerpt").shorten({
    //     "showChars" : 120,
    //     "moreText"  : "See More",
    // });

    tinymce.init({
        selector: '#textarea',
        height:400
    });

    });



    function tinyMCESave() {
        tinyMCE.triggerSave();
    }

</script>