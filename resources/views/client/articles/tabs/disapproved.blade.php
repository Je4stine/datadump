<?php
/**
 * Created by PhpStorm.
 * User: DANTE
 * Date: 9/28/2016
 * Time: 1:16 AM
 */ ?>

@extends('layouts.gentella')
@section('title')
    My Articles
@endsection
@section('content')

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



    <div class="card section">
        <div class="card-header">
            My Articles
        </div>
        <div class="panel-body">
            <button class="btn btn-default pull-left" onclick="get_editor_content()"><i class="fa fa-newspaper-o"></i>
                <b>Articles:</b> {{ $disapproved_articles->total()}} </button>

            <div class="col-md-8">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Disapproved Articles <small>Sessions</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    @foreach($disapproved_articles as $disapproved_article)
                        <div class="x_content">
                            <div class="dashboard-widget-content">

                                <ul class="list-unstyled timeline widget">
                                    <li>
                                        <div class="block">
                                            <div class="block_content">
                                                {{ Form::open(array('method'=>'post'))}}
                                                {{ csrf_field() }}
                                                <h2 class="title">
                                                    <a>{{ $disapproved_article->title }}</a> <div class="byline pull-right">
                                                        <span><time class="timeago" datetime="{{ $disapproved_article->created_at }}"> </time></span>
                                                    </div>
                                                </h2>

                                                <div> {!! $disapproved_article->content !!}</div>

                                                <!-- Confirm Delete Modal Dialog -->

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
                                                                <a href="{{ URL::to('articles/'.$disapproved_article->id) }}" type="button" name="article_delete"
                                                                   class="btn btn-danger"><i
                                                                            class="fa fa-trash"></i> delete
                                                                </a>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                {{ Form::close() }}

                                            </div>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ URL::to('articles/edit/'.$disapproved_article->id) }}" id="article_edit" name="article_edit"
                                               class="btn btn-primary btn-xs"><i
                                                        class="fa fa-edit"></i> edit
                                            </a>
                                            <a href="{{ URL::to('articles/submit/'.$disapproved_article->id) }}" id="article_submit" name="article_submit"
                                               class="btn btn-success btn-xs" ><i
                                                        class="fa fa-tick"></i> submit
                                            </a>

                                            {{--<a href="{{ URL::to('articles/'.$disapproved_article->id) }}" type="button" id="delete-article-{{ $disapproved_article->id }}" name="article_delete"--}}
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
                    @endforeach
                </div>
            </div>


        </div>
    </div>



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


@endsection