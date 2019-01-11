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

Route::resource('forms', 'FormController');

//TODO add route prefix to group
Route::group(['middleware' => 'auth'], function () {

    Route::get('forms/{form}/preview', 'FormPreviewController@show');

    Route::patch('forms/{form}/order', 'FormOrderController@update');

    Route::get('forms/{form}/select-questions', 'SelectQuestionsController@index');

    Route::resource('forms/{form}/questions', 'QuestionsController');

    Route::delete('forms/{form}/questions/{question}/options/{option}', 'SelectOptionsController@destroy');

});
