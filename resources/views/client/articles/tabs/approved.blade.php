<table class="table">
    <tr>
        <th>ID</th>
        <th>Article</th>
        <th>On</th>
        <th>Action</th>
    </tr>
    @foreach($articles as $article)
        <tr>
            <td>{{ $article->id }}</td>
            <td>
                <a href="{{ URL::to("stud/article/$article->id") }}"><strong>{{ $article->title }}</strong></a>
                <p>{!! strip_tags(substr($article->content,0,150)) !!}...</p>
            </td>
            <td>{{ date('d,M Y',strtotime($article->created_at)) }}</td>
            <td>
                <a href="{{ URL::to("stud/article/$article->id") }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> View</a>
                @if($article->status != 2)
                @endif
                 @if(!$article->isApproved())
                                 <a onclick="deleteItem('{{ URL::to("stud/article") }}',{{ $article->id }})" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Delete</a>

    
                                            <a href="{{ URL::to('articles/edit/'.$article->id) }}" id="article_edit" name="article_edit"
                                                    class="btn btn-primary btn-xs"><i
                                                        class="fa fa-edit"></i> edit
                                            </a>
    @endif
       @if($article->isDraft())
     <a href="{{ URL::to('articles/submit/'.$article->id) }}" id="article_submit" name="article_submit"
                                                    class="btn btn-success btn-xs" ><i
                                                        class="fa fa-tick"></i> submit
                                            </a>
                <a onclick="deleteItem('{{ URL::to("stud/article") }}',{{ $article->id }})" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Delete</a>

    @endif
            </td>
        </tr>
    @endforeach
</table>
{!! $articles->links() !!}


