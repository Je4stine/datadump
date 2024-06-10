<?php
Route::group(['prefix'=>'stud/affiliate'],function(){
    $controller = 'AffiliateController@';
    Route::get('/',$controller.'index');
    Route::post('/',$controller.'saveInvites');
    Route::get('/gmail',$controller.'gmail');
    Route::post('/gmail',$controller.'addEmail');
    Route::get('/support',$controller.'support');
    Route::get('/earnings',$controller.'earnings');
    Route::get('/terms',$controller.'terms');
});