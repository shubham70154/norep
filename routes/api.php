<?php

Route::post('register', 'Api\RegisterController@register');
Route::post('login', 'Api\RegisterController@login');
Route::get('logout', 'Api\RegisterController@logout');
Route::get('login-user-details/{id}', 'Api\RegisterController@getAuthUser');
Route::post('send-notification', 'Api\EventsApiController@sendUserNotification');
Route::post('forget-password', 'Api\RegisterController@forgetPassword');
Route::post('reset-password', 'Api\RegisterController@resetPassword');
Route::get('get-page-details/{query_title}', 'Api\PagesApiController@getPageDetails');

Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResource('permissions', 'Api\PermissionsApiController');

    Route::post('save-device-token', 'Api\RegisterController@saveDeviceToken');
    
    Route::apiResource('roles', 'Api\RolesApiController');

    Route::apiResource('users', 'Api\UsersApiController');

    // user profile update
    Route::post('user-profile-update/{userid}', 'Api\RegisterController@userProfileUpdate');

    // Events API routes
    Route::post('create-events', 'Api\EventsApiController@create');
    Route::get('get-allevent-list', 'Api\EventsApiController@getAllEventList');
    Route::get('get-allactiveevent-list', 'Api\EventsApiController@getAllActiveEventList');
    Route::get('get-pastevent-list', 'Api\EventsApiController@getPastEventList');
    Route::get('get-futureevent-list', 'Api\EventsApiController@getFutureEventList');
    Route::get('event/{id}', 'Api\EventsApiController@showEventDetails');

    Route::post('create-subevents', 'Api\EventsApiController@createSubEvent');
    Route::get('subevent/{subeventid}', 'Api\EventsApiController@showSubEventDetails');
    Route::get('get-eubevent-list/{eventid}', 'Api\EventsApiController@getSubEventList');

    Route::post('save-help-support', 'Api\PagesApiController@saveHelpSupport');
    Route::get('get-support-list', 'Api\PagesApiController@getSupportList');

    Route::post('save-terms-conditions', 'Api\RegisterController@saveTermCondition');
});

