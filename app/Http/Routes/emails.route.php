<?php
Route::group(['prefix' => 'settings'], function () {
    Route::group(['prefix' => 'emails'], function () {
        Route::get('/','EmailController@index');
        Route::post('/add','EmailController@addEmail');
        Route::get('/template/{email}','EmailController@editTemplate');
        Route::post('/template/{email}','EmailController@saveTemplate');
        Route::get('/delete/{email}','EmailController@deleteEmail');
        Route::get('/send','EmailController@sendEmails');
    });
});