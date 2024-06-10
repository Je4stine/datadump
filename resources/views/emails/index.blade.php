 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">{{ $website->name }} Email Templates <a onclick="return setEmail('','','');" href="#add_email_modal" class="pull-right btn btn-success" data-toggle="modal"><i class="fa fa-plus"></i> Add Email</a> </div>
        </div>
        <div class="panel-body">
            <table class="table table-condensed table-bordered">
                <tr>
                    <th>#</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>&nbsp;</th>
                </tr>
                @foreach($emails as $email)
                    <tr>
                        <td>{{ $email->id }}</td>
                        <td>{{ $email->action }}</td>
                        <td><?php echo $email->description ?></td>
                        <td>
                            <a onclick="" class="btn btn-success btn-xs" href="{{ URL::to("websites/emails/template/$email->id") }}"><i class="fa fa-edit"></i> Template</a>
                            <a onclick="return setEmail('{{ $email->id }}','{{ $email->action }}','{{ $email->description }}');" class="btn btn-success btn-xs" href="#add_email_modal" data-toggle="modal"><i class="fa fa-edit"></i> </a>
                            <a href="{{ URL::to("websites/emails/delete/$email->id") }}" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    @endforeach
            </table>
            {{ $emails->links() }}
        </div>
    </div>
@include('emails.add_modal')
@endsection
