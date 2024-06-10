<?php
Route::group(['prefix' => 'departments'], function () {
    Route::get('/','DepartmentsController@index');
    Route::get('/messages','DepartmentsController@messages');
    Route::post('/messages','DepartmentsController@newMessage');
    Route::post('/','DepartmentsController@saveDepartment');
    Route::get('/conversation/{department}/{user}','DepartmentsController@conversation');
    Route::get('/conversation/{department}/{user}/send','DepartmentsController@roomMessage');
    Route::get('/delete/{department}','DepartmentsController@delete');
});