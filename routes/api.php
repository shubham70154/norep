<?php

Route::post('register', 'Api\RegisterController@register');
Route::post('login', 'Api\RegisterController@login');
Route::get('logout', 'Api\RegisterController@logout');
Route::get('login-user-details/{id}', 'Api\RegisterController@getAuthUser');
Route::post('send-notification', 'Api\EventsApiController@sendUserNotification');

Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResource('permissions', 'Api\PermissionsApiController');

    Route::post('save-device-token', 'Api\RegisterController@saveDeviceToken');
    
    Route::apiResource('roles', 'Api\RolesApiController');

    Route::apiResource('users', 'Api\UsersApiController');

    // Events API routes
    Route::post('create-events', 'Api\EventsApiController@create');
    Route::get('get-allevent-list', 'Api\EventsApiController@getAllEventList');
    Route::get('get-pastevent-list', 'Api\EventsApiController@getPastEventList');
    Route::get('get-futureevent-list', 'Api\EventsApiController@getFutureEventList');

   // Route::apiResource('events', 'Api\EventsApiController');
   // Route::post('events', 'Api\EventsApiController@create');
    
   // Route::get('events/{id}', 'Api\EventsApiController@showEventDetails');
});

// Route::group(['prefix' => 'v1', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin'], function () {
//     Route::apiResource('permissions', 'PermissionsApiController');

//     Route::apiResource('roles', 'RolesApiController');

//     Route::apiResource('users', 'UsersApiController');

//     Route::apiResource('events', 'EventsApiController');
// });
