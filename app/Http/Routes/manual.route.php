<?php
    Route::group(['prefix' => 'manual','middleware'=>'auth'], function () {
        $controller = "ManualOrderController";
        Route::get('/',$controller.'@index');
        Route::get('/pending',$controller.'@pending');
        Route::get('/revision',$controller.'@revision');
        Route::get('/bid/{order}',$controller.'@loadBidForm');
        Route::post('/bid/{order}',$controller.'@placeBid');
        Route::get('/approved',$controller.'@approved');
        Route::get('/payments',$controller.'@payments');
        Route::get('/new',$controller.'@newOrder');
        Route::get('/unassigned',$controller.'@newManuals');
        Route::post('/new',$controller.'@createOrder');
        Route::delete('/delete/{order}',$controller.'@deleteOrder');
    });