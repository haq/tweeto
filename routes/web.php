<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::resource('/message', 'MessagesController')->except([
  'index', 'create', 'show', 'update', 'edit'
]);

Route::get('/', 'ProfileController@index')->name('user.home');
Route::get('/{name}', 'ProfileController@show')->name('user.show');
Route::get('/{user}/follow', 'ProfileController@followUser')->name('user.follow');
Route::get('/{user}/unfollow', 'ProfileController@unFollowUser')->name('user.unfollow');
