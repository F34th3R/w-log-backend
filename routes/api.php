<?php
Route::group(['prefix' => '/auth'], function () {
    //* Login
    Route::post('login', 'Auth\AccessTokenController@issueToken');
    Route::post('logout', 'Auth\AccessTokenController@logout');
//    Route::post('refresh', 'AuthController@refresh');
});

// fth Public
Route::group(['prefix'  =>  '/public'], function () {
    Route::get('posts','PostController@index');
    Route::get('post/{code}','PostController@show');
});

Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/posts'], function () {
        Route::get('/','PostController@indexByUser');
        Route::get('/{code}', 'PostController@showByUser');
        Route::post('/', 'PostController@store');
        Route::put('/{post}', 'PostController@update');
        Route::delete('/{post}', 'PostController@destroy');
    });
});
