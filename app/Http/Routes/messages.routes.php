<?php
Route::group(['prefix' => 'messages'], function () {
    Route::get('unread','MessagesController@getUnread');
    Route::get('room/markread/{id}','MessagesController@readRoomMessage');
    Route::post('/{order}/room/{assign}/send','MessagesController@sendMessage');
    Route::get('/{order}/room/{assign}/messages','MessagesController@getMessages');
    Route::get('/{message}','MessagesController@findRoom');
    Route::any('/ordermessages/{order}','MessagesController@orderMessages');
    Route::any('/ordermessages/{order}/markread','MessagesController@markRead');
});