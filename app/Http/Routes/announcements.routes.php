<?php
Route::get('/order/completed','OrderController@completedOrders');
Route::group(['prefix' => 'announcements'], function () {
    Route::get('view/{role}','AnnouncementController@announcements');
    Route::get('/read/{announcement}','AnnouncementController@viewAnnouncement');
    Route::any('add/{role}','AnnouncementController@createAnnouncement');
    Route::post('publish/{announcement}','AnnouncementController@publish');
    Route::post('unpublish/{announcement}','AnnouncementController@unPublish');
    Route::delete('delete/{announcement}','AnnouncementController@delete');
});