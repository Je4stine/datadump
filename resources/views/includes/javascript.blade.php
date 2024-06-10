<div class="modal fade" role="dialog" id="status_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Action Status<a data-dismiss="modal" class="pull-right btn-danger btn">&times;</a></div>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" id="delete_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" style="left:40%">
                    <button class="btn btn-danger pull-right" data-dismiss="modal">&times;</button>
                    <h4>Are you sure?</h4>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal ajax-post" id="delete_form" action="" method="post">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="delete_id">
                    <input type="hidden" name="delete_model">
                    <div class="form-group">
                        <label class="control-label col-md-5">&nbsp;</label>
                        <div class="col-md-5">
                            <button data-dismiss="modal" class="btn btn-danger">NO</button>
                            <button type="submit" class="btn btn-success">YES</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" id="run_action_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" style="left:40%">
                    <button class="btn btn-danger pull-right" data-dismiss="modal">&times;</button>
                    <h4>Are you sure?</h4>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal ajax-post" id="run_action_form" action="" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="action_element_id">
                    <div class="form-group">
                        <label class="control-label col-md-5">&nbsp;</label>
                        <div class="col-md-5">
                            <button data-dismiss="modal" class="btn btn-danger">NO</button>
                            <button type="submit" class="btn btn-success">YES</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .autocomplete-suggestions{
        background-color: beige;
    }
</style>
<script type="text/javascript">
    var current_url = window.location.href;
    (function(window,undefined){
        History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
            var State = History.getState(); // Note: We are using History.getState() instead of event.state
            if(State.url != current_url){
                var url = window.location.href;
                ajaxLoad(url);
            }
        });

    })(window);
 var form = null;
    jQuery(document).on('click','.fg-line',function(){
        $(this).closest(".form-group").find('.help-block').remove();
            $(this).closest(".form-group").removeClass('has-error');
    });
    jQuery(document).on('click','.load-page',function(){
        console.log($(this));
        jQuery(".loading-img").show();
        jQuery(".ma-backdrop").trigger('click');
        jQuery(".profile-info").slideUp();
         var url = $(this).attr('href');

            var status = 0;
        var material_active = $('input[name="material_page_loaded"]').val();
        $.get( url,null )
            .done(function( response ) {
                jQuery(".loading-img").hide();
                current_url = url;
                if(response.redirect){
                    if(material_active == 1){
                        setTimeout(function(){
                            ajaxLoad(response.redirect);
                        },1300);
                    }else{
                        window.location.href = response.redirect;
                    }

                }
                $(".system-container").html(response);
                var title = $(".system-title").html();
                History.pushState({state:1}, 'Order Panel', url);
                prepareAjaxUpload();
                addPageClasses();
                getDataCount();
                $('input[name="deadline"]').datetimepicker();
                return false;
            })
            .fail(function(response){
                window.location.href = url;
            });
        return false;

    });
    jQuery(document).on('submit','.ajax-post',function(){
          var form = $(this);
            showLoading();
            this.form = form;
            $(".fg-line").removeClass('has-error');
            var url = $(this).attr('action');
            var data = $(this).serialize();
            var material_active = $('input[name="material_page_loaded"]').val();
            $.post(url,data).done(function(response,status){
                endLoading();
                removeError();
                if(response.redirect){
                    if(material_active == 1){
                        setTimeout(function(){
                            ajaxLoad(response.redirect);
                        },1300);
                    }else{
                        window.location.href = response.redirect;
                    }

                }
                else if(response.reload){
                    if(material_active == 1){
                        setTimeout(function(){
                            ajaxLoad(window.location.href);
                        },1300);
                    }else{
                        window.location.href = response.redirect;
                    }
                }else if(response.force_redirect){
                    setTimeout(function(){
                        window.location.href= response.force_redirect;
                    },1300);
                }
                else{
                    runAfterSubmit(response);
                }
            })
                    .fail(function(xhr,status,error){
                        if(xhr.status == 422){
                            form.find('.alert_status').remove();
                            var response = xhr.responseJSON;
                            for(field in response){
                                $("input[name='"+field+"']").closest(".form-group").addClass('has-error');
                                $("input[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                                $("input[name='"+field+"']").closest(".form-group").append('<small class="help-block">'+response[field]+'</small>');

                                $("select[name='"+field+"']").closest(".form-group").addClass('has-error');
                                $("select[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                                $("select[name='"+field+"']").closest(".form-group").append('<small class="help-block">'+response[field]+'</small>');

                                $("textarea[name='"+field+"']").closest(".form-group").addClass('has-error');
                                $("textarea[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                                $("textarea[name='"+field+"']").closest(".form-group").append('<small class="help-block">'+response[field]+'</small>');
                            }
                        }else{
                            form.find('#form-exception').remove();
                            form.find('.alert_status').remove();
                            form.prepend('<div id="form-exception" class="alert alert-danger"><strong>'+xhr.status+'</strong> '+error+'<br/>('+url+')</div>');
                            removeError();
                        }

                    });
            return false;
    });
    jQuery(document).on('submit','.ajax-get',function(){
          var form = $(this);
            showLoading();
            this.form = form;
            $(".fg-line").removeClass('has-error');
            var url = $(this).attr('action');
            var data = $(this).serialize();
            var material_active = $('input[name="material_page_loaded"]').val();
            $.get(url,data).done(function(response,status){
                endLoading();
                removeError();
                if(response.redirect){
                    if(material_active == 1){
                        setTimeout(function(){
                            ajaxLoad(response.redirect);
                        },1300);
                    }else{
                        window.location.href = response.redirect;
                    }

                }else if(response.force_redirect){
                    setTimeout(function(){
                        window.location.href= response.force_redirect;
                    },1300);
                }
                else{
                    runAfterSubmit(response);
                }
            })
                    .fail(function(xhr,status,error){
                        if(xhr.status == 422){
                            form.find('.alert_status').remove();
                            var response = xhr.responseJSON;
                            for(field in response){
                                $("input[name='"+field+"']").closest(".form-group").addClass('has-error');
                                $("input[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                                $("input[name='"+field+"']").closest(".form-group").append('<small class="help-block">'+response[field]+'</small>');

                                $("select[name='"+field+"']").closest(".form-group").addClass('has-error');
                                $("select[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                                $("select[name='"+field+"']").closest(".form-group").append('<small class="help-block">'+response[field]+'</small>');

                                $("textarea[name='"+field+"']").closest(".form-group").addClass('has-error');
                                $("textarea[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                                $("textarea[name='"+field+"']").closest(".form-group").append('<small class="help-block">'+response[field]+'</small>');
                            }
                        }else{
                            form.find('#form-exception').remove();
                            form.find('.alert_status').remove();
                            form.prepend('<div id="form-exception" class="alert alert-danger"><strong>'+xhr.status+'</strong> '+error+'<br/>('+url+')</div>');
                            removeError();
                        }

                    });
            return false;
    });

    function ajaxLoad(url){
        jQuery(".loading-img").show();
        jQuery(".loading-img").show();
        var material_active = $('input[name="material_page_loaded"]').val();
            $.get(url,null,function(response){
                jQuery(".loading-img").show();
                $(".system-container").html(response);
                current_url = url;
                if(response.redirect){
                    if(material_active == 1){
                        setTimeout(function(){
                            ajaxLoad(response.redirect);
                        },1300);
                    }else{
                        window.location.href = response.redirect;
                    }

                }
                var title = $(".system-title").html();

            });
            var url = window.location.href
        History.pushState({state:1}, 'Order Panel', url);
        prepareAjaxUpload();
        addPageClasses();
        getDataCount();
        autoFillAllSelects();
        $('input[name="deadline"]').datetimepicker();
           return false;
    }
    function softError(element,reponse){

    }
    function removeError(){
        setTimeout(function(){
            $("#form-exception").fadeOut();
            $("#form-success").fadeOut();
            $(".alert_status").fadeOut();
        },1200);

    }

    function resetField(field,placeholder){
        setTimeout(function(){
            $("input[name='"+field+"']").attr('placeholder',placeholder);
            // $("input[name='"+field+"']").closest('fg-line').removeClass('has-error');
        },1300);
    }

    function hardError(element,response){

    }

    function validationErrors(form,errors){
        for(field in errors){
            alert(errors[field]);
        }
    }

    function showLoading(){
        $(".alert_status").remove();
        $('.ajax-post').not(".persistent-modal .modal-body").prepend('<div id="" class="alert alert-success alert_status"><img style="" class="loading_img" src="{{ URL::to("img/ajax-loader.gif") }}"></div>');
    }
    function endLoading(data){
        $(".alert_status").html('Success!');
        setTimeout(function(){
            $(".modal").not(".persistent-modal").modal('hide');
            $(".alert_status").slideUp();
//            $("#principal_file_modal").modal('show');
        },800);
    }


    function autofillForm(data){
        for(key in data){
            var in_type = $('input[name="'+key+'"]').attr('type');
            if(in_type != 'file'){
                $('input[name="'+key+'"]').val(data[key]);
                $('input[name="'+key+'"]').click();
                $('textarea[name="'+key+'"]').val(data[key]);
                $('textarea[name="'+key+'"]').click();
                $('select[name="'+key+'"]').val(data[key]);
                $('select[name="'+key+'"]').click();
            }
        }
    }
 jQuery(document).on('click','.delete-btn',function(){
     var url = $(this).attr('href');
     deleteItem(url,null);
     return false;
 });
    function deleteItem(url,id,model){
//        $("#delete_modal").modal('show');
        $("input[name='delete_id']").val(id);
        $("input[name='delete_model']").val(model);
        $("#delete_form").attr('action',url);
        if(id)
            $("#delete_form").attr('action',url+'/'+id);

        swal({
            title: "Are you sure?",
            text: "A deleted Item can never be recovered!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm){
            if (isConfirm) {
                var url = $("#delete_form").attr('action');
                var data = $("#delete_form").serialize();
                $.post(url,data)
                        .done(function(response){
                            swal("Deleted!", "Item Deleted Successfully", "success");
                            if(response.redirect){
                                setTimeout(function(){
                                    ajaxLoad(response.redirect);
                                },1300);

                            }else{
                                runAfterSubmit(response);
                            }
                        })
                        .fail(function(xhr,status,response){
                            swal("Error!", response, "error");
                        });

            } else {
                swal("Cancelled", "Your Item is safe :)", "error");
            }
        });

    }

    function addPageClasses(){
        $(".pagination a").each(function(){
            $(this).addClass("load-page")
        });
    }

    function runPlainRequest(url,id){
        if(id != undefined){
            var url = url+'/'+id;
        }
        $("#run_action_modal").modal('show');
        $("input[name='action_element_id']").val(id);
        $("#run_action_form").attr('action',url);
    }

    function reloadCsrf(){
    }

    function getEditItem(url,id,modal){
        var url = url+'/'+id;
        $.get(url,null,function(response){
            autofillForm(response);
            $("#"+modal).modal('show');
        });
    }

    function resetForm(id){
        $("."+id).find("input[type=text],textarea,select").val("");
        $("input[name='id']").val('');
//        runAfterReset();
    }

    function autoFillSelect(name,url){
        $.get(url,null,function(response){
            for(var i =0;i<response.length;i++){
                if(response[i].name){
                    $("select[name='"+name+"']").append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
                }
                if(response[i].bank_name){
                    $("select[name='"+name+"']").append('<option value="'+response[i].id+'">'+response[i].bank_name+'</option>');
                }
            }
            setTimeout(function(){
                $(".chosen-select").chosen({disable_search_threshold: 10});
                $(".chosen-select").trigger("chosen:updated");
                $(".chosen-container").width('100%');
            },1000)
        });
    }
    function setSelectData(name,data){

            for(var i =0;i<data.length;i++){
                if(data[i].name){
                    $("select[name='"+name+"']").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
                }

            }
            setTimeout(function(){
                $(".chosen-select").chosen({disable_search_threshold: 10});
                $(".chosen-select").trigger("chosen:updated");
                $(".chosen-container").width('100%');
            },1000)

    }

    function prepareEdit(element,modal){
        var data = $(element).data('model');
        autofillForm(data);
        $("#"+modal).modal('show');
    }

    function setAutoComplete(name,url){
        var formatted = [];
       $.get(url,null,function(response){
            for( var i=0;i<response.length;i++){
                var single = {value:response[i].name,data:response[i].name};
                formatted.push(single);
            }
            $("input[name='"+name+"']").autocomplete({
                lookup: formatted
            });
            console.log(formatted);
       });
    }
 $(document).ready(function() {
     prepareAjaxUpload();

 });
 function prepareAjaxUpload(){
     autoFillAllSelects();
     var form_url = $(".file-form").attr('action');
     var options = {
         target:        '#output1',   // target element(s) to be updated with server response
         beforeSubmit:  showRequest,  // pre-submit callback
         success:       fileUploadFinish,  // post-submit callback
         dataType:  'json',
         error:endWithError

     };

     $('.file-form').ajaxForm(options);
 }
 // pre-submit callback
 function showRequest(formData, jqForm, options) {
     $(".alert_status").remove();
     $('.file-form').prepend('<div id="" class="alert alert-success alert_status"><img style="" class="loading_img" src="{{ URL::to("img/ajax-loader.gif") }}"></div>');
 }

 function fileUploadFinish(response){
     if(response.id || response.image){
         endLoading();
         runAfterSubmit(response);
     }else if(response.redirect){
         endLoading(response);
         setTimeout(function(){
             ajaxLoad(response.redirect);
         },1300);

     }else{
         endWithMinorErrors(response);
     }
 }
 function endWithError(xhr){
     var error = xhr.statusText;
     response = xhr.responseText;
     response = JSON.parse(response);
     if(xhr.status == 422){
         $('.alert_status').remove();
         for(field in response){
             $("input[name='"+field+"']").closest(".form-group").addClass('has-error');
             $("input[name='"+field+"']").closest(".form-group").find('.help-block').remove();
             $("input[name='"+field+"']").closest(".form-group").append('<small class="help-block">'+response[field]+'</small>');

             $("select[name='"+field+"']").closest(".form-group").addClass('has-error');
             $("select[name='"+field+"']").closest(".form-group").find('.help-block').remove();
             $("select[name='"+field+"']").closest(".form-group").append('<small class="help-block">'+response[field]+'</small>');

             $("textarea[name='"+field+"']").closest(".form-group").addClass('has-error');
             $("textarea[name='"+field+"']").closest(".form-group").find('.help-block').remove();
             $("textarea[name='"+field+"']").closest(".form-group").append('<small class="help-block">'+response[field]+'</small>');
         }
     }else{
         $(".alert_status").remove();
         $('.file-form').prepend('<div id="" class="alert alert-danger alert_status"><strong>'+xhr.status+'</strong> '+error+'</div>');
         removeError();
     }
 }
    jQuery(document).ready(function(){
        $("input[name='start_time']").attr('data-mask','00:00:00');
        $("input[name='start_time']").addClass('input-mask');
        setInterval(function(){
//            getNotifications();
        },20000);
        getDataCount();
    });
    function getNotifications(){

        var url = '{{ url('notifications') }}';
        jQuery.get(url,null,function(notifications){
            $(".notice_body").html('');
            $(".notice_count").html(notifications.length);
            for(var i=0;i<notifications.length;i++){
                var notification = notifications[i];
                $(".notice_body").append('<a class="load-page list-group-item media" href="'+notification.data.action+'">'+
                        '<div class="media-body">'+
                        '<small class="lgi-text">'+notification.data.message+'</small>'+
               '</div></a>');
            }
        });
    }
    function readNotications(){
        var url = '{{ url("notifications/read-all") }}';
        jQuery.get(url);
        $(".notice_count").html(0);
    }

    function autoFillAllSelects(){
        var url = '{{ url(@Auth::user()->role.'/'.'autofill/data') }}';
        var data = [];
        $(".auto-fetch-select").each(function(){
            data.push($(this).attr('name')+':'+$(this).data("model"));
        });
       if(data.length > 0){
           $.get(url,{models:data},function(response){
                for(key in response){
                    setSelectData(key,response[key]);
                }
           });
       }
    }

    function deleteModel(id,model){
        var url = '{{ url(@Auth::user()->role.'/'.'delete/model') }}';
        return deleteItem(url,id,model);

    }

</script>
<style type="text/css">
    .delete{
        color:red;
    }
</style>