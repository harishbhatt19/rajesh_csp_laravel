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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//TATA_CHEMICALS_API_BY_BIZZ_WEBSITE

Route::post('employee_login', 'ApiController@employeeLogin');
Route::post('get_categories', 'ApiController@getCategories');
Route::post('get_ponds', 'ApiController@getPonds');
Route::post('add_userdata', 'ApiController@addUserData');
Route::post('get_userdata', 'ApiController@getUserData');
Route::post('get_daterange_userdata', 'ApiController@getDaterangeUserData');

Route::post('get_daterange_salinity', 'ApiController@getDaterangeSalinity');


Route::post('send_email', 'ApiController@postSendEmail');

Route::get('bbpsuser', 'ApiController@getbbpsuser');
Route::get('bbpsbill', 'ApiController@getbbpsbill');
Route::get('bbpsbillpay', 'ApiController@getbbpsbillpay');

Route::get('add-beneficiaries', 'ApiController@postAddbeneficiaries');
Route::get('view-beneficiaries', 'ApiController@getViewbeneficiaries');
Route::get('moneytransfer', 'ApiController@postMoneytransfer');
Route::get('report-dmt', 'ApiController@getReportdmt');
Route::get('delete-beneficiaries', 'ApiController@postDeletebeneficiaries');