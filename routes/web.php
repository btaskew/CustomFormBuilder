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
})->name('home');

OpenAm::routes();

Route::resource('forms', 'FormController');

Route::post('forms/{form}/responses', 'FormResponseController@store');

Route::group(['middleware' => 'auth'], function () {


    Route::get('questions/bank/search', 'QuestionSearchController@show');

    Route::group(['prefix' => 'forms/{form}'], function () {

        Route::get('preview', 'FormPreviewController@show');

        Route::patch('order', 'FormOrderController@update');

        Route::get('select-questions', 'SelectQuestionsController@index');

        Route::get('questions/bank', 'QuestionBankController@index');
        Route::post('questions/bank', 'QuestionBankController@store');

        Route::resource('questions', 'QuestionsController');

        Route::delete('questions/{question}/options/{option}', 'SelectOptionsController@destroy');

        Route::get('responses', 'FormResponseController@index');
        Route::get('responses/export', 'ResponseExportController@index');

        Route::get('access', 'FormAccessController@index');
        Route::post('access', 'FormAccessController@store');
        Route::delete('access/{formUser}', 'FormAccessController@destroy');

    });


    Route::group(['middleware' => 'auth.admin'], function () {

        Route::resource('folders', 'FolderController');

    });

});
