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

Route::group(['prefix' => 'projects/v1'], function(){
    Route::post('create', ['as' => 'project.create', 'uses' => 'ProjectController@store']);
    Route::get('list/{type}', ['as' => 'project.index', 'uses' => 'ProjectController@index']);
    Route::get('delete/{id}', ['as' => 'project.delete', 'uses' => 'ProjectController@destroy']);
    Route::post('addtype', ['as' => 'project.addtype', 'uses' => 'ProjectController@addType']);
});
