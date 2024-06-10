<?php
/**
 * Created by PhpStorm.
 * User: DANTE
 * Date: 9/29/2016
 * Time: 10:56 AM
 */
?>


    <style xmlns="http://www.w3.org/1999/html">
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

        #textarea,#text{
            border-radius: 7px;
        }
        #recently_sent{
            border-radius: 7px;
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
            {{--<button class="btn btn-default pull-left"><i class="fa fa-newspaper-o"></i>--}}
            {{--<b>Articles:</b> {{ $articles->total()}} </button>--}}

            <div id="editArticle" class="article-form col-xs-10">
               <form action="{{ action('ArticleController@storeDraft') }}" method="post" id="article_form">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $article->id }}">
                <input type="hidden" name="status" value="{{ $article->status }}">
                <div class="form-group col-xs-10">
                    <label for="title" class="control-label ">Title</label>
                    <div class="control-label">
                        <input class="col-md-4 form-control" type="text" name="title" value="{{$article->title }}" required >
                    </div>
                </div>
                <div class="form-group col-xs-12">
                    <label class="control-label "> Article</label>
                    <div class="form-group">
                                    <textarea id="textarea" rows="6" cols="30" required name="article_content"
                                              class="form-control">{!! $article->content !!}</textarea>
                        <span id="" class="alert-danger">
                                        <i><strong id="error-msg"></strong></i>
                                    </span>
                        <span id="" class="alert-success">
                                        <i><strong id="success-msg"></strong></i>
                                    </span>

                    </div>

                </div>
                {{--<div class="form-group col-xs-12">--}}
                {{--<label for="meta-description" class="control-label">Meta-description</label>--}}
                {{--<div class="control-label ">--}}
                {{--<textarea class="col-md-6" rows="4"  name="meta_description" required id="text" ></textarea>--}}
                {{--</div>--}}
                {{--</div>--}}


                <div class="form-group col-xs-12">

                    <div class="pull-right">
                        {{--<button type="submit" id="message_form_send" name="submit_new"--}}
                                {{--class="btn btn-success btn-xs" ><i--}}
                                    {{--class="fa fa-send"></i> Submit--}}
                        {{--</button>--}}
                        <button type="submit" id="article_form_save_draft" onclick="return tinyMCESave()"
                                class="btn btn-info btn-xs" ><i
                                    class="fa fa-save"></i> Save
                        </button>
                        <button id="message_form_discard" name="submit_discard"
                                class="btn btn-danger btn-xs" onclick="return discard();" ><i
                                    class="fa fa-times"></i> Discard
                        </button>

                        {{--<a href="{{ URL::to('articles/submit/'.$article->id) }}" id="article_submit" name="article_submit"--}}
                           {{--class="btn btn-success btn-xs" ><i--}}
                                    {{--class="fa fa-tick"></i> submit--}}
                        {{--</a>--}}

                        {{--<a href="{{ URL::to('articles/'.$article->id) }}" type="button" id="delete-article-{{ $article->id }}" name="article_delete"--}}
                           {{--class="btn btn-danger btn-xs"><i--}}
                                    {{--class="fa fa-trash"></i> delete--}}
                        {{--</a>.--}}

                    </div>
                </div>
               </form>
            </div>
        </div>
    </div>

    @include('client.articles.jscript')