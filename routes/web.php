<?php

Auth::routes();

Route::post('/message', 'MessagesController@store')->name('message.store');
Route::delete('/message/{message}', 'MessagesController@destroy')->name('message.destroy');
Route::get('/message/{message}/favorite', 'MessagesController@favorite')->name('message.favorite');

Route::get('/', 'ProfileController@index')->name('user.home');
Route::get('/following', 'ProfileController@following')->name('user.following');
Route::get('/followers', 'ProfileController@followers')->name('user.followers');

Route::get('/settings', 'ProfileController@edit')->name('user.settings');
Route::put('/settings', 'ProfileController@update')->name('users.settings.update');
Route::get('/settings/password', 'ProfileController@editPassword')->name('user.settings.password');
Route::put('/settings/password', 'ProfileController@updatePassword')->name('users.settings.password.update');

Route::get('/{name}', 'ProfileController@show')->name('user.show');
Route::get('/{user}/follow', 'ProfileController@followUser')->name('user.follow');
Route::post('/search','ProfileController@search')->name('user.search');