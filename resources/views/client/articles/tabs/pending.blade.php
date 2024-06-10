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




                    @foreach($articles as $pending_article)
                        <div class="x_content">
                            <div class="dashboard-widget-content">

                                <ul class="list-unstyled timeline widget">
                                    <li>
                                        <div class="block">
                                            <div class="block_content">
                                                <h3 class="sub-title">{{ $pending_article->meta_description }}</h3>
                                                <form method="post">
                                                {{ csrf_field() }}
                                                <h2 class="title">
                                                    <a>{{ $pending_article->title }}</a> <div class="byline pull-right">
                                                        <span><time class="timeago" datetime="{{ $pending_article->created_at }}"> </time></span>
                                                    </div>
                                                </h2>

                                                <div> {!! $pending_article->content !!}</div>

                                                </form>

                                            </div>
                                        </div>
                                        <a href="{{ URL::to('articles/edit/'.$pending_article->id) }}" id="article_edit" name="article_edit"
                                           class="btn btn-success btn-xs pull-right"><i
                                                    class="fa fa-edit"></i> edit
                                        </a>
                                    </li>


                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    {!! $articles->links() !!}

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
