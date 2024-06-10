<?php
Route::get('/article-analytics','ArticleController@processAnalytics');
 Route::group(['prefix'=>'articles'], function (){
        Route::get('/','ArticleController@index');
        Route::get('/new','ArticleController@newArticle');
        Route::get('/drafts','ArticleController@getDraft');
        Route::get('/pending','ArticleController@getPending');
        Route::get('/approved','ArticleController@getApproved');
        Route::get('/disapproved','ArticleController@getDisapproved');
        Route::POST('/store-draft','ArticleController@storeDraft');
        Route::get('/{article}', 'ArticleController@destroy');
        Route::get('/edit/{article}', 'ArticleController@editArticle');
         Route::get('/edit/writer/{article}', 'ArticleController@editArticle');
        Route::get('/submit/{article}', 'ArticleController@submitArticle');
        Route::get('/approve/{article}','ArticleController@approve');
        Route::get('/disapprove/{article}','ArticleController@disapprove');
    });