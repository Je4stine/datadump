<table class="table">
    <tr>
        <th>ID</th>
        <th>Article</th>
        <th>Date Published</th>
        <th>By</th>
        <th>Action</th>
    </tr>
    @foreach($articles as $article)
        <tr>
            <td>{{ $article->id }}</td>
             <td>
                <a href="{{ URL::to("order/articles/$article->id") }}"><strong>{{ $article->title }}</strong></a>
                <p>{!! strip_tags(substr($article->content,0,150)) !!}...</p>
            </td>
            <td>{{ date('d,M Y H:i',strtotime($article->created_at)) }}</td>
            <td><a href="{{ URL::to("user/view/client")."/".$article->user->id }}">{{ $article->user->name }}</a> </td>
            <td>
               <a class="btn btn-primary btn-sm" href="{{ URL::to("order/articles/$article->id") }}"><i class="fa fa-eye"></i> View</a>
               <a class="btn btn-danger btn-sm" onclick="deleteItem('{{ URL::to("order/article/delete") }}',{{ $article->id }})"><i class="fa fa-times"></i> Delete</a>
            </td>
        </tr>
        @endforeach
</table>
{!! $articles->links() !!}
