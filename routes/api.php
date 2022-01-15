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
    Route::delete('delete-event', 'Api\EventsApiController@deleteEvent');
    Route::delete('delete-sub-event', 'Api\EventsApiController@deleteSubEvent');
    Route::get('get-allevent-list', 'Api\EventsApiController@getAllEventList');
    Route::get('get-allactiveevent-list', 'Api\EventsApiController@getAllActiveEventList');
    Route::get('get-pastevent-list', 'Api\EventsApiController@getPastEventList');
    Route::get('get-futureevent-list', 'Api\EventsApiController@getFutureEventList');
    Route::get('get-runningevent-list', 'Api\EventsApiController@getRunningEventList');
    Route::get('event/{id}', 'Api\EventsApiController@showEventDetails');

    Route::put('event-update/{id}', 'Api\EventsApiController@eventUpdate');

    Route::get('get-event-creators-event-list/{user_id}', 'Api\EventsApiController@getEventCreatorsEventList');

    Route::post('create-subevents', 'Api\EventsApiController@createSubEvent');
    Route::get('subevent/{subeventid}', 'Api\EventsApiController@showSubEventDetails');
    Route::get('get-subevent-list/{eventid}', 'Api\EventsApiController@getSubEventList');

    Route::post('save-help-support', 'Api\PagesApiController@saveHelpSupport');
    Route::get('get-support-list', 'Api\PagesApiController@getSupportList');

    Route::post('save-terms-conditions', 'Api\RegisterController@saveTermCondition');

    Route::get('get-referees-list', 'Api\RegisterController@getRefereesList');

    Route::get('referee-allocate-events/{refereeid}', 'Api\EventsApiController@refereeAllocatedEvents');
    
    Route::post('assign-event-referees', 'Api\EventsApiController@assignEventReferees');

    Route::post('update-event-player-limit', 'Api\EventsApiController@updateEventPlayerLimit');

    Route::post('join-user-event', 'Api\UserJoinedEventsApiController@joinUserEvent');
    
    Route::post('save-event-amount', 'Api\EventPaymentsApiController@saveEventAmount');

    Route::get('get-participants-list-by-eventId/{eventid}', 'Api\UserJoinedEventsApiController@getParticipantsListByEventId');

    Route::get('get-referee-list-by-eventId/{eventid}', 'Api\UserJoinedEventsApiController@getRefereeListByEventId');
    
    Route::post('check-user-joined-events', 'Api\UserJoinedEventsApiController@checkUserJoinedEvents');

    Route::get('get-event-timelines/{event_id}', 'Api\EventsApiController@getEventTimelines');

    Route::get('get-subevent-category-lists', 'Api\EventsApiController@getSubEventCategoryLists');

    Route::get('get-event-leaderboard/{event_id}', 'Api\LeaderBoardsApiController@getEventLeaderBoard');

    Route::get('get-user-wallet/{user_id}', 'Api\UserWalletsApiController@getUserWallet');

    Route::get('event-joined-participant-lists/{event_id}', 'Api\UserWalletsApiController@eventJoinedParticipantLists');

    Route::post('user-wallet-deposite-amount', 'Api\UserWalletsApiController@userWalletDepositeAmount');

    Route::post('user-wallet-withdraw-amount', 'Api\UserWalletsApiController@userWalletWithDrawAmount');

    Route::post('referee-get-subevent-details', 'Api\RefereesApiController@refereeGetSubeventDetails');

    Route::post('add-user-score-by-referee', 'Api\RefereesApiController@addUserScoreByReferee');

    Route::post('submit-final-user-score-by-referee', 'Api\RefereesApiController@submitFinalUserScoreByReferee');

    Route::post('user-transaction-details', 'Api\UserWalletsApiController@userTransactionDetails');

    Route::post('user-event-leaderboard', 'Api\LeaderBoardsApiController@userEventLeaderboard');
});

