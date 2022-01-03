<?php

Route::redirect('/', '/login');

Route::redirect('/home', '/admin');

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');

    Route::resource('permissions', 'PermissionsController');

    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');

    Route::resource('roles', 'RolesController');

    Route::get('/users/refereelist', 'UsersController@getRefereeList')->name('users.refereelist');

    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');

    Route::resource('users', 'UsersController');

    

    Route::delete('events/destroy', 'EventsController@massDestroy')->name('events.massDestroy');

    Route::resource('events', 'EventsController');

    Route::get('events/subevent/lists/{id}', 'SubEventsController@subEventList')->name('events.subevent/lists');

    Route::get('subevent/show/{id}', 'SubEventsController@showSubEvent')->name('subevents.show');

    Route::resource('referees', 'RefereesController');

    Route::post('get-states-by-country','RefereesController@getState');
    Route::post('get-cities-by-state','RefereesController@getCity');
});
