<div class="modal fade" role="dialog" id="contacts_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    Selected Contacts will be invited
                </div>
            </div>
            <div class="modal-body" style="">
                <form class="form-horizontal" method="post" action="{{ url("stud/affiliate") }}">
                    {{ csrf_field() }}
                    <input type="checkbox" checked id="checkAll">Check All
                    <hr />
                    <div style="max-height: 400px;overflow-y: auto">

                        @foreach($gmail_contacts as $group)
                            <div class="col-md-6">
                                @foreach($group as $contact)
                                    <input checked type="checkbox" name="contacts[]" value="{{ $contact['id'] }}">
                                    {{ $contact['email'] }}<br/>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4">
                            &nbsp;
                        </label>
                        <div class="col-md-6">
                            <input type="submit" class="btn btn-info" value="Invite">
                        </div>
                    </div>
                </form>

                <div class="row"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $.get('{{ url('api/gmail/contacts') }}',function(){

    });
   $(document).ready(function(){
       $("#contacts_modal").modal('show');
   })

    function checkAll(){

    }

    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>