<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\PaymentController;

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
Route::get('/unauthorized', 'Api\LoginController@unauthorized');
Route::get('/v2/info', 'SettingController@info');
Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::group(['middleware' => ['auth:api','verified']], function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['data' => $request->user()]);
    }); 
    Route::get('/peoplelist', 'PeopleController@index');
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/isonline', 'DashboardController@checkConnection');
    Route::get('/cabinate', 'CabinateController@index');
    Route::get('/post', 'PostController@index');
    Route::get('post/details/{id}', 'PostController@details');
    Route::post('/generate', 'PdfController@generate');
    Route::get('/cabinatepeople/{id}', 'CabinateController@cabinatepeople');
    Route::get('/details/{id}', 'PeopleController@details');
    Route::get('/peoplenote', 'NoticeController@people');    
    Route::resource('/profile', 'ProfileController');    
    Route::post('logout', 'Api\LoginController@logout');
});
