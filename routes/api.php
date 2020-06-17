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
});

Route::group(['prefix' => 'v1/chapter'], function () {
    Route::post('create', ['as' => 'chapter.create', 'uses' => 'ChapterController@store']);
    Route::get('list/{pId}', ['as' => 'chapter.index', 'uses' => 'ChapterController@index']);
    Route::get('details/{id}', ['as' => 'chapter.details', 'uses' => 'ChapterController@show']);
    Route::post('update/{id}', ['as' => 'chapter.update', 'uses' => 'ChapterController@update']);
    Route::get('delete/{id}', ['as' => 'chapter.delete', 'uses' => 'ChapterController@destroy']);
    Route::get('content/show/{id}', ['as' => 'chapter.showcontent', 'uses' => 'ChapterController@showContent']);
    Route::post('content/update/{id}', ['as' => 'chapter.updatecontent', 'uses' => 'ChapterController@updateContent']);
});
