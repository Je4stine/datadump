<?php
Route::group(['prefix' => 'referrals'], function () {
    $controller = "ReferralController@";
    Route::get('/',$controller.'index');
    Route::post('/config',$controller.'updateCommission');
});
