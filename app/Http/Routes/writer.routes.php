<?php
Route::group(['prefix' => 'writer'], function () {
    Route::get('/','WriterController@index' );
    Route::post('/withdraw','WriterController@withdraw' );
    Route::post('/add_payment','WriterController@addPayment' );
    Route::get('active','WriterController@active' );
    Route::get('completed','WriterController@completed' );
    Route::get('announcements','WriterController@announcements' );
    Route::get('pending','WriterController@pending' );
    Route::get('revision','WriterController@revision' );
    Route::get('payment','WriterController@payment' );
    Route::get('available','WriterController@available' );
    Route::get('order/{order}','WriterController@viewOrder' );
    Route::delete('bid/delete/{bid}','WriterController@deleteBid' );
    Route::get('bid/{bid_mapper}','WriterController@bid' );
    Route::post('bid/{bidMapperid}','WriterController@addBid' );
    Route::any('order/{order}/room/{assign}','WriterController@orderRoom');
    Route::get('/bids','WriterController@bids' );
    Route::get('/get_count','WriterController@getOrderCounts' );
    Route::get('/take/{bidMapperid}','WriterController@takeOrder' );
    Route::post('/take/{bidMapperid}','WriterController@takeOrder' );
    Route::get('test','TestsController@writerTest');
    Route::post('test','TestsController@submitTest');
    Route::post('test/{test}','TestsController@start');
});
