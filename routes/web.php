<?php

Auth::routes();

Route::post('/message', 'TweetsController@store')->name('tweet.store');
Route::delete('/message/{message}', 'TweetsController@destroy')->name('tweet.destroy');
Route::get('/message/{message}/favorite', 'TweetsController@favorite')->name('tweet.favorite');
Route::post('/message/{message}/retweet', 'TweetsController@reTweet')->name('tweet.retweet');

Route::get('/', 'ProfileController@index')->name('user.home');
Route::get('/following', 'ProfileController@following')->name('user.following');
Route::get('/followers', 'ProfileController@followers')->name('user.followers');

Route::get('/settings', 'ProfileController@edit')->name('user.settings');
Route::put('/settings', 'ProfileController@update')->name('users.settings.update');
Route::get('/settings/password', 'ProfileController@editPassword')->name('user.settings.password');
Route::put('/settings/password', 'ProfileController@updatePassword')->name('users.settings.password.update');

Route::get('/{user}', 'ProfileController@show')->name('user.show');
Route::post('/{user}/follow', 'ProfileController@followUser')->name('user.follow');

Route::post('/search', 'ProfileController@search')->name('user.search');