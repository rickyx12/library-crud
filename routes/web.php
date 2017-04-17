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

Route::get('/', function () {
    return view('welcome');
});

Route::get('book','BookController@index')->name('homeBook');
Route::get('book/all','BookController@all')->name('all');
Route::post('book/add','BookController@store')->name('addBook');
Route::post('book/update','BookController@update')->name('updateStatus');
Route::post('book/delete','BookController@destroy')->name('delete');
Route::get('book/search/{filterOption}/{searchOption}/{search?}','BookController@search')->name('searchBook');
Route::get('book/filter/page/{filterOption}/{page?}','BookController@filter');