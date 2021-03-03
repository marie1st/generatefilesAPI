<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::post('clinicupload', 'ClinicController@store');
    Route::post('covidgen', 'ClinicController@covidgenerate');
    Route::post('fitgen', 'ClinicController@fitgenerate');
    Route::post('torgen', 'ClinicController@tor8generate');
    Route::post('covidfiles', 'ClinicController@requestcovidfile');
    Route::post('fitfiles', 'ClinicController@requestfitfile');
    Route::post('torfiles', 'ClinicController@requesttor8file');
   
    
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});


