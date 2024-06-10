<?php
Route::group(['prefix' => 'settings'], function () {
    Route::any('/subjects','SettingsController@subjects');
    Route::any('/documents','SettingsController@documents');
    Route::get('/styles','SettingsController@styles');
    Route::get('/languages','SettingsController@languages');
    Route::any('/add/style','SettingsController@addStyle');
    Route::any('/add/subject','SettingsController@addSubject');
    Route::any('/add/document','SettingsController@addDocument');
    Route::any('/add/language','SettingsController@addLanguage');
    Route::get('/delete/urgency/{urgency}','SettingsController@deleteUrgency');
    Route::get('/add/urgency/{id}','SettingsController@addUrgency');
    Route::any('/add/category/{type}/{id}','SettingsController@addCategory');
    Route::get('/delete/style/{style}','SettingsController@deleteStyle');
    Route::get('/delete/subject/{subject}','SettingsController@deleteSubject');
    Route::get('/delete/document/{document}','SettingsController@deleteDocument');
    Route::get('/delete/language/{language}','SettingsController@deleteLanguage');

    Route::group(['prefix' => 'academic'], function () {
        Route::get('/','AcademicController@index');
        Route::post('/adjust-all','AcademicController@adjustAll');
        Route::get('/add','AcademicController@academicForm');
        Route::post('/add','AcademicController@saveAcademic');
        Route::get('/edit/{academic}','AcademicController@academicForm');
        Route::get('/delete/{academic}','AcademicController@deleteAcademic');
        Route::post('/{academic}/add_rate','AcademicController@saveRate');
        Route::get('/rates/delete/{rate}','AcademicController@deleteRate');
    });

    Route::group(['prefix' => 'tests'], function () {
        Route::get('/','TestsController@settings');
        Route::post('/','TestsController@store');
        Route::post('/status/{test}','TestsController@changeStatus');
    });
});