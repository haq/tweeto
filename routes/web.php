<?php

Auth::routes();

Route::post('/message', 'MessagesController@store')->name('message.store');
Route::delete('/message/{message}', 'MessagesController@destroy')->name('message.destroy');
Route::get('/message/{message}/favorite', 'MessagesController@favorite')->name('message.favorite');

Route::get('/', 'ProfileController@index')->name('user.home');
Route::get('/{user}/settings', 'ProfileController@showSettings')->name('user.settings');
Route::get('/{name}', 'ProfileController@show')->name('user.show');
Route::get('/{user}/follow', 'ProfileController@followUser')->name('user.follow');