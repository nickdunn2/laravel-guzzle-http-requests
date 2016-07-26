<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('main');
});

Route::get('houses', 'HouseController@getAllHouses', [
    'except' => ['create', 'edit']
]);

Route::get('houses/adddetails/{house_id}', 'HouseController@addDetails');
Route::get('houses/addalldetails', 'HouseController@addAllDetails');
