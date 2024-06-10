 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                {{ $email->name }}
                <a href="#emails_vars" data-toggle="modal" class="btn btn-info pull-right">View Variables</a>

            </div>
        </div>
        <div class="panel-body">
            <form action="{{ URL::to("websites/emails/template/$email->id") }}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="">
                <textarea name="template"><?php echo $email->template ?></textarea>
                <div class="form-group">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        tinymce.init({
            selector: 'textarea',
            height: 500,
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
    </script>
@endsection

 <div style="display: none;" class="modal fade" id="emails_vars" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <a data-dismiss="modal" class="btn btn-danger pull-right modal-closer">&times;</a>
                 <h5>Replacable Variables</h5>
             </div>
             <div class="modal-body">
                <table class="table table-condensed table-bordered">
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                    <tr>
                        <th colspan="2">User Vars</th>
                    </tr>
                    <tr>
                        <th>{name}</th>
                        <td>User Name</td>
                    </tr>
                    <tr>
                        <th>{email}</th>
                        <td>User Email</td>
                    </tr>
                    <tr>
                        <th>{phone}</th>
                        <td>User Phone</td>
                    </tr>
                    <tr>
                        <th>{website}</th>
                        <td>User Website name</td>
                    </tr>
                    <tr>
                        <th colspan="2">Order Vars</th>
                    </tr>
                    <tr>
                        <th>{topic}</th>
                        <td>Order Topic</td>
                    </tr>
                    <tr>
                        <th>{order_id}</th>
                        <td>Order ID/Number</td>
                    </tr>
                    <tr>
                        <th>{amount}</th>
                        <td>Order Amount</td>
                    </tr>
                    <tr>
                        <th>{deadline}</th>
                        <td>Order Deadline</td>
                    </tr>
                    <tr>
                        <th>Assign Vars</th>
                    </tr>
                    <tr>
                        <th>{fine_amount}</th>
                        <td>Writer Fine Amount</td>
                    </tr>
                    <tr>
                        <th>{fine_reason}</th>
                        <td>Writer Fine Reason</td>
                    </tr>
                    <tr>
                        <th>{assign_amount}</th>
                        <td>Writer Order Assignment Amount</td>
                    </tr>
                    <tr>
                        <th>{assign_deadline}</th>
                        <td>Writer Assign Deadline</td>
                    </tr>
                    <tr>
                        <th>{assign_bonus}</th>
                        <td>Writer Assign Bonus</td>
                    </tr>
                    <tr>
                        <th>Links</th>
                    </tr>

                    <tr>
                        <th>{new_order_link}</th>
                        <td>Link for placing a new order</td>
                    </tr>
                    <tr>
                        <th>{e_wallet_link}</th>
                        <td>Client link to e-wallet</td>
                    </tr>
                    <tr>
                        <th>{affiliate_link}</th>
                        <td>Client Link to Affiliate Program</td>
                    </tr>
                    <tr>
                        <th>{inbox_link}</th>
                        <td>Link to Inbox</td>
                    </tr>
                    <tr>
                        <th>{view_order_link}</th>
                        <td>Client View Order</td>
                    </tr>
                    <tr>
                        <th>{message_reply_link}</th>
                        <td>Client View & Reply Message link</td>
                    </tr>
                </table>
             </div>
             <div class="modal-footer">
             </div>
         </div>
     </div>
 </div>