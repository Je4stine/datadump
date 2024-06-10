<?php
Route::group(['prefix' => 'payments'], function () {
    Route::get('/','PaymentController@index');
    Route::get('/orders','PaymentController@orders');
    Route::get('/tips','PaymentController@tips');
    Route::get('/execute','PaymentController@executePayment');
    Route::get('/payouts','PaymentController@payouts');
    Route::get('/payouts/{payout}','PaymentController@renewPayout');
    Route::post('/payouts','PaymentController@processPayout');
});