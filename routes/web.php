<?php

Auth::routes();

Route::post('/message', 'MessagesController@store')->name('message.store');
Route::delete('/message/{message}', 'MessagesController@destory')->name('message.destroy');
Route::get('/message/{message}/favorite', 'MessagesController@favorite')->name('message.favorite');

Route::get('/', 'ProfileController@index')->name('user.home');
Route::get('/{name}', 'ProfileController@show')->name('user.show');
Route::get('/{user}/follow', 'ProfileController@followUser')->name('user.follow');