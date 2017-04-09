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
Route::post('book/add','BookController@store')->name('addBook');
Route::post('book/update/{id}','BookController@update')->name('updateStatus');
Route::post('book/delete/{id}','BookController@destroy')->name('delete');
Route::get('book/search','BookController@search')->name('searchText');
Route::get('book/filter','BookController@filter')->name('filter');

