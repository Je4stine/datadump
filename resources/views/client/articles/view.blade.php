
    <div class="col-md-6">
        <div class="card section">
            <div class="card-header">
                <div class="panel-title">{{ $article->title }}</div>
            </div>
            <div class="panel-body">
            @if(!$article->isApproved())
    
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
                {!! $article->content !!}
            </div>
        </div>
    </div>
    
    @if($article->isApproved())
   <!--  <div class="col-md-6">
        <div class="card section">
            <div class="card-header">
                <div class="panel-title"> Hits/Analytics</div>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>IP</th>
                        <th>Date</th>
                        <th>Points</th>
                    </tr>
                    @foreach($stats as $stat)
                        <?php
                            $date = \Carbon\Carbon::createFromTimestamp(strtotime($stat->created_at));
                                ?>
                        <tr>
                            <td>{{ $stat->id }}</td>
                            <td>{{ substr($stat->ip_address,0,7) }}...</td>
                            <td>{{ $date->diffForHumans() }}</td>
                            <td>{{ $stat->points }}</td>
                        </tr>
                        @endforeach
                </table>
                {{ $stats->links() }}
            </div>
        </div>
    </div> -->
    @endif
    <div class="row"></div>
