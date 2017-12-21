<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix' => '/api/v1','middleware' => ['api']], function() {
    Route::post('/get/access/token', array('as'=>'GetToken', 'uses'=>'ApiController@getAccessToken'));
    Route::post('/call/log', array('as'=>'Survey campaign', 'uses'=>'ApiController@callHistory'));
    Route::post('/sms/log', array('as'=>'Survey campaign', 'uses'=>'ApiController@SMSHistory'));
    //Login
    Route::post('/app/login', array('as'=>'Survey campaign', 'uses'=>'ApiController@appLogin'));
    Route::post('/app/registration', array('as'=>'Survey campaign', 'uses'=>'ApiController@AppRegistration'));
    Route::get('/app/test', array('as'=>'Survey campaign', 'uses'=>'ApiController@Fos'));
    Route::post('/app/pin/confirm', array('as'=>'Survey campaign', 'uses'=>'ApiController@AppPinConfirm'));
    Route::post('/app/pin/resent', array('as'=>'Survey campaign', 'uses'=>'ApiController@resentPinApp'));
    Route::get('/get/all/campaign', array('as'=>'Survey campaign', 'uses'=>'ApiController@getCampaign'));
    Route::post('/get/user/activities', array('as'=>'Survey campaign', 'uses'=>'ApiController@getUserActivities'));
    Route::post('/app/profile/update', array('as'=>'Survey campaign', 'uses'=>'ApiController@appProfileUpdate'));
    Route::post('/app/change/password', array('as'=>'Survey campaign', 'uses'=>'ApiController@appChangePassword'));
    Route::get('all/campaign', array('as'=>'All Campaign', 'uses'=>'SurveyController@getAllCampaign'));

});


