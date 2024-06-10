<?php
/**
 * Created by PhpStorm.
 * User: DANTE
 * Date: 9/28/2016
 * Time: 1:16 AM
 */ ?>



    <style>
        .table-striped > tbody > tr:nth-child(odd) > td,
        .table-striped > tbody > tr:nth-child(odd) > th {
            background-color: #EEEEE0;
        }

        #panelbody {
            position: relative;
            margin: 0;
            overflow: auto;
            height: 500px;
        }


    </style>



                    @foreach($articles as $draft_article)
                        <div class="x_content">
                            <div class="dashboard-widget-content">

                                <ul class="list-unstyled timeline widget">
                                    <li>
                                        <div class="block">
                                            <div class="block_content">
                                                <h3 class="sub-title">{{ $draft_article->meta_description }}</h3>
                                                <form method="post">
                                                {{ csrf_field() }}
                                                <h2 class="title">
                                                    <a>{{ $draft_article->title }}</a> <div class="byline pull-right">
                                                        <span><time class="timeago" datetime="{{ $draft_article->created_at }}"> </time></span>
                                                    </div>
                                                </h2>

                                                <div> {!! $draft_article->content !!}</div>

                                                </form>

                                            </div>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ URL::to('articles/edit/'.$draft_article->id) }}" id="article_edit" name="article_edit"
                                                    class="btn btn-primary btn-xs"><i
                                                        class="fa fa-edit"></i> edit
                                            </a>
                                            <a href="{{ URL::to('articles/submit/'.$draft_article->id) }}" id="article_submit" name="article_submit"
                                                    class="btn btn-success btn-xs" ><i
                                                        class="fa fa-tick"></i> submit
                                            </a>

                                            {{--<a href="{{ URL::to('articles/'.$draft_article->id) }}" type="button" id="delete-article-{{ $draft_article->id }}" name="article_delete"--}}
                                                    {{--class="btn btn-danger btn-xs"><i--}}
                                                        {{--class="fa fa-trash"></i> delete--}}
                                            {{--</a>--}}

                                            <button class=" btn btn-danger btn-xs" type="button" data-toggle="modal" data-target="#confirmDelete">
                                                <i class="fa fa-trash"></i> delete
                                            </button>

                                            {{--<button id="article_save" name="article_save"--}}
                                                    {{--class="btn btn-primary btn-sm" onclick="return articleSave();" ><i--}}
                                                        {{--class="fa fa-save"></i> save--}}
                                            {{--</button>--}}

                                        </div>
                                    </li>


                                </ul>
                            </div>
                        </div>
                            <div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <a class="btn btn-danger btn-xs pull-right" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Delete Parmanently</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="{{ URL::to('articles/'.$draft_article->id) }}" type="button" id="delete-article-{{ $draft_article->id }}" name="article_delete"
                       class="btn btn-danger"><i
                                class="fa fa-trash"></i> delete
                    </a>
                </div>

            </div>
        </div>
    </div>
                    @endforeach

{!! $articles->links() !!}
    <!-- Confirm Delete Modal Dialog -->





    <script type="text/javascript">
<!-- timeago link-->
    jQuery(document).ready(function() {
        $("time.timeago").timeago();
    });

    <!-- "more"-link button to display rest of the text-->
    $(".excerpt").shorten({
        "showChars" : 120,
        "moreText"	: "See More",
    });
    </script>


