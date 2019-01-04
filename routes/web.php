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
    return redirect('/forms');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::resource('forms', 'FormController');

    Route::post('forms/{form}/questions', 'QuestionsController@store');
});
