<?php
Route::group(['prefix'=>'api/gmail'],function(){
    $controller = 'GmailController@';
    Route::get('/invite',$controller.'inviteFriends');
    Route::get('/run',$controller.'startInviting');
    Route::get('/',$controller.'fetchContacts');
});