<?php
Route::group(['prefix' => 'websites'], function () {
    Route::post('/{website}/logo','WebsiteController@uploadLogo');
    Route::post('/{website}/promo_image','WebsiteController@uploadPromo');
    Route::post('/{website}/punchline','WebsiteController@addPunchline');
    Route::get('/','WebsiteController@index');
    Route::get('/add','WebsiteController@addWebsite');
    Route::post('/add','WebsiteController@addWebsite');
    Route::get('edit/{website}','WebsiteController@editWebsite');
    Route::get('view/{website}','WebsiteController@viewWebsite');
    Route::get('punchline/{assurance}/delete','WebsiteController@deletePunchline');

    Route::group(['prefix' => 'emails'], function () {
        Route::get('/{website}','EmailController@index');
        Route::post('/{website}/add','EmailController@addEmail');
        Route::get('/template/{email}','EmailController@editTemplate');
        Route::post('/template/{email}','EmailController@saveTemplate');
        Route::get('/delete/{email}','EmailController@deleteEmail');
        Route::get('/send','EmailController@sendEmails');
    });
});