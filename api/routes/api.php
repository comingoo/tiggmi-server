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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
/**
 * Admin Login.
 *
 * @parameter email , password
 *
 * @return response bearer access token
 */
Route::post('login', 'Auth\LoginController@handleLogin');
Route::group(['middleware' => 'App\Http\Middleware\CustomerMiddleware'], function()
{
/**
 * Customer Login.
 *
 * @parameter mobile , password
 *
 * @return response OTP
 */
Route::post('customer_login', 'Auth\CustomerLoginController@handleCustomerLogin')->name('customer.login');
/**
 * Customer OTP VerificationLogin.
 *
 * @parameter mobile , password, OTP
 *
 * @return response bearer access token
 */
Route::post('customer_login/verifyOTP', 'Auth\CustomerLoginController@verifyCustomerOTP')->name('customer.verifyotp');
/*
 * Customer Profile
 * @parameter bearer token
 * @return response
 */
Route::post('customer/profile', 'CustomerController@profile')->name('customer.profile');
/*
 * Customer Profile Edit
 * @parameter bearer token
 * @return response
 */
Route::post('customer/profile/edit', 'CustomerController@editprofile')->name('profile.edit');

});

/*
 * Customer Profile
 * @parameter bearer token
 * @return response
 */
Route::post('admin/profile', 'AdminController@profile')->name('admin.profile');
