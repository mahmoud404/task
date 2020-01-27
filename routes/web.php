<?php

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/', 'ApplicantController@index')->name('applicant');
Route::post('/', 'ApplicantController@apply');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/messages','ChatController@messages');
    Route::get('/contacts/{chat}','ChatController@singleContact');
    Route::get('/contacts/{chat}/messages','ChatController@messages');
    Route::post('/contacts/{chat}/messages','ChatController@sendMessage');
});
