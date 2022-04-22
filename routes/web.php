<?php

Route::redirect('/', '/login');

Route::redirect('/home', '/admin');

Auth::routes(['register' => false]);
 
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    //Route::resource('bookedevent', 'BookedEventsController');
    Route::get('/bookedevents/list', 'BookedEventsController@index')->name('bookedevents.list');

    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');

    Route::resource('permissions', 'PermissionsController');

    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');

    Route::resource('roles', 'RolesController');

    Route::get('/users/refereelist', 'UsersController@getRefereeList')->name('users.refereelist');

    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');

    Route::resource('users', 'UsersController');

    Route::get('events/runningEventList', 'EventsController@getRunningEventList')->name('events.runningeventlist');

    Route::get('events/upcomingEventList', 'EventsController@getFutureEventList')->name('events.upcomingeventlist');

    Route::get('events/pastEventList', 'EventsController@getPastEventList')->name('events.pasteventlist');

    Route::delete('events/destroy', 'EventsController@massDestroy')->name('events.massDestroy');

    Route::resource('events', 'EventsController');

    Route::get('events/subevent/lists/{id}', 'SubEventsController@subEventList')->name('events.subevent/lists');

    Route::get('events/participant/lists/{id}', 'EventsController@getParticipantsListByEventId')->name('events.participant/lists');

    Route::get('events/referee/lists/{id}', 'EventsController@getRefereeListByEventId')->name('events.referee/lists');

    Route::get('subevent/show/{id}', 'SubEventsController@showSubEvent')->name('subevents.show');

    Route::get('subevent/leaderboard/{id}', 'SubEventsController@getSubEventLeaderBoard')->name('subevents.leaderboard');

    Route::resource('referees', 'RefereesController');

    Route::get('/helpsupports/list', 'HelpSupportsController@index')->name('helpsupports.list');

    Route::get('/manualnotifications/index', 'ManualNotificationsController@index')->name('manualnotifications.index');

    Route::post('/manualnotifications/sendnotification', 'ManualNotificationsController@sendNotifications')->name('manualnotifications.sendnotification');

    Route::get('/helpsupports/show/{id}', 'HelpSupportsController@show')->name('helpsupports.show');

    Route::post('get-states-by-country','RefereesController@getState');
    Route::post('get-cities-by-state','RefereesController@getCity');

    Route::get('/wallet/list/{id}', 'UserWalletsController@index')->name('walletmanagement.list');
});
