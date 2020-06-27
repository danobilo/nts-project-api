<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1/project'], function () {
    Route::post('create', ['as' => 'project.create', 'uses' => 'ProjectController@store']);
    Route::get('list/{type}', ['as' => 'project.index', 'uses' => 'ProjectController@index']);
    Route::get('details/{id}', ['as' => 'project.details', 'uses' => 'ProjectController@show']);
    Route::post('update/{id}', ['as' => 'project.update', 'uses' => 'ProjectController@update']);
    Route::get('delete/{id}', ['as' => 'project.delete', 'uses' => 'ProjectController@destroy']);
    Route::post('addtype', ['as' => 'project.addtype', 'uses' => 'ProjectController@addType']);
});

Route::group(['prefix' => 'v1/document'], function () {
    Route::post('create', ['as' => 'document.create', 'uses' => 'DocumentController@store']);
    Route::get('list/{pId}', ['as' => 'document.index', 'uses' => 'DocumentController@index']);
    Route::get('details/{id}', ['as' => 'document.details', 'uses' => 'DocumentController@show']);
    Route::post('update/{id}', ['as' => 'document.update', 'uses' => 'DocumentController@update']);
    Route::post('delete/{id}', ['as' => 'document.delete', 'uses' => 'DocumentController@destroy']);
    Route::get('content/show/{id}', ['as' => 'document.showcontent', 'uses' => 'DocumentController@showContent']);
    Route::post('editcell', ['as' => 'document.updatecol', 'uses' => 'DocumentController@editCell']);
});

Route::group(['prefix' => 'v1/chapter'], function () {
    Route::post('create', ['as' => 'chapter.create', 'uses' => 'ChapterController@store']);
    Route::get('list/{pId}', ['as' => 'chapter.index', 'uses' => 'ChapterController@index']);
    Route::get('details/{id}', ['as' => 'chapter.details', 'uses' => 'ChapterController@show']);
    Route::post('update/{id}', ['as' => 'chapter.update', 'uses' => 'ChapterController@update']);
    Route::get('delete/{id}', ['as' => 'chapter.delete', 'uses' => 'ChapterController@destroy']);
    Route::get('content/show/{id}', ['as' => 'chapter.showcontent', 'uses' => 'ChapterController@showContent']);
    Route::post('content/update/{id}', ['as' => 'chapter.updatecontent', 'uses' => 'ChapterController@updateContent']);
    Route::post('editcell', ['as' => 'chapter.updatecol', 'uses' => 'ChapterController@editCell']);
});

Route::group(['prefix' => 'v1/event'], function () {
    Route::post('create', ['as' => 'event.create', 'uses' => 'EventController@store']);
    Route::get('list/{pId}/{type}/{id}', ['as' => 'event.index', 'uses' => 'EventController@index']);
    Route::get('details/{id}', ['as' => 'event.details', 'uses' => 'EventController@show']);
    Route::post('update/{id}', ['as' => 'event.update', 'uses' => 'EventController@update']);
    Route::get('delete/{id}', ['as' => 'event.delete', 'uses' => 'EventController@destroy']);
    Route::get('user/list', ['as' => 'event.userlist', 'uses' => 'EventController@getUserList']);
    Route::get('assigned/{event_id}', ['as' => 'event.assigned', 'uses' => 'EventController@getAssignedUsers']);
    Route::get('days/{event_id}', ['as' => 'event.days', 'uses' => 'EventController@getAssignedDays']);
    Route::post('add/user', ['as' => 'event.adduser', 'uses' => 'EventController@attachUser']);
    Route::post('editcell', ['as' => 'event.updatecol', 'uses' => 'EventController@editCell']);
    Route::get('generate/{event_id}', ['as' => 'event.generate', 'uses' => 'EventController@generateEvents']);
    Route::get('reoccurences/{event_id}', ['as' => 'event.reoccurences', 'uses' => 'EventController@getChildEvents']);
});

Route::group(['prefix' => 'v1/file'], function () {
    Route::post('upload/{id}/{type}', ['as' => 'file.upload', 'uses' => 'FileController@store']);
    Route::get('list/{type}/{id}', ['as' => 'file.index', 'uses' => 'FileController@index']);
    Route::get('details/{id}', ['as' => 'file.details', 'uses' => 'FileController@show']);
    Route::post('update/{id}', ['as' => 'file.update', 'uses' => 'FileController@update']);
    Route::get('delete/{id}', ['as' => 'file.delete', 'uses' => 'FileController@destroy']);
});

Route::group(['prefix' => 'v1/media'], function () {
    Route::post('upload/{id}/{type}', ['as' => 'media.upload', 'uses' => 'MediaController@store']);
    Route::get('list/{pId}', ['as' => 'media.index', 'uses' => 'MediaController@index']);
    Route::get('details/{id}', ['as' => 'media.details', 'uses' => 'MediaController@show']);
    Route::post('update/{id}', ['as' => 'media.update', 'uses' => 'MediaController@update']);
    Route::get('delete/{id}', ['as' => 'media.delete', 'uses' => 'MediaController@destroy']);
});
