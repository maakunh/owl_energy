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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::match(array('GET', 'POST'),'/', 'HistoryController@index');

Auth::routes();

Route::get('/user', 'HistoryController@user');

Route::get('/owl_setting', 'HistoryController@owl_setting');

Route::post('owl_setting/edit', 'HistoryController@owl_setting_edit');
