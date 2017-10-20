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

Route::get('all', 'MoviesController@getAllMovies');

Route::get('addAllMovies', 'MoviesController@addAllMovies');

Route::get('create', 'MoviesController@create');

Route::get('get', 'MoviesController@getItem');

Route::get('update', 'MoviesController@update');

Route::get('increment', 'MoviesController@incrementRating');

Route::get('conditionally', 'MoviesController@conditionally');

Route::get('delete', 'MoviesController@deleteItem');

Route::get('get-year', 'QueriesController@yearCondition');

Route::get('get-year2', 'QueriesController@yearCondition1');

Route::get('scan', 'QueriesController@scan');


Route::get('eloquent', 'EloquentDinamoDb@index');
Route::get('eloquent/create', 'EloquentDinamoDb@create');


Route::resource('dynamodb', 'DynamoDbController');