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

//StateController
Route:: post('/states/store', 'StateController@store');
Route:: get('/states/index', 'StateController@index');

//LocationController
Route:: post('/location/store', 'LocationController@store');
Route:: get('/locations/index', 'LocationController@index');
Route:: get('/locations/showlocationbyid/{state_id}', 'LocationController@show_location_by_id');



//UserController
Route:: get('/users/index', 'UserController@index');

Route:: post('/users/store', 'UserController@store');

Route:: post('/users/update', 'UserController@update_user');

Route:: get('/users/showphone/{phone}', 'UserController@show_user_by_phone');

Route::group(['prefix' => 'user'],function (){
        Route::post('login', 'UserController@login');

        Route::post('logout','UserController@logout') -> middleware(['auth.guard:user-api']);

 });

Route::group(['prefix' => 'user' ,'middleware' => 'auth.guard:user-api'],function (){
    //auth.guard:user-api  handel token to any user or admin it belong
 Route::post('profile',function(){
        return \Auth::user();//return authenticated user data
    
     }) ;
}) ;
