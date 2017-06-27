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

/*
 * 创建首页 help about 个页面的路由
 */

Route::get('/', 'StaticPagesController@home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

//用户注册
Route::get('/signup','UsersController@create')->name('signup');
Route::resource('users','UsersController');

Route::get('/create','SessionsController@create')->name('create');

Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');
Route::get('/users/{id}/edit', 'UsersController@edit')->name('users.edit');
Route::get('/users','UsersController@index')->name('users.index');

Route::resource('users', 'UsersController');
Route::get('/users/{user_id}', 'UsersController@show')->name('users.show');

//用户激活
Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');


//微博
Route::resource('statuses','StatusesController',['only'=>['store','destroy']]);

