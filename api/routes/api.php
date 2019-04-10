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
 * Customer Mobile Verification.
 *
 * @parameter mobile 
 *
 * @return true/false
 */
Route::get('customer/{mobile}', 'Auth\CustomerLoginController@VerifyMobile')->name('mobile.verification');

/**
 * Customer OTP VerificationLogin.
 *
 * @parameter mobile , password, OTP
 *
 * @return response bearer access token
 */
Route::post('customer_login/verifyOTP', 'Auth\CustomerLoginController@verifyCustomerOTP')->name('customer.verifyotp');
/*
 *Customer Logout
 * @parameter bearer token
 * @return response
 */
Route::post('customer/logout', 'Auth\CustomerLoginController@handleLogout')->name('customer.logout');
/*
 * Customer Profile
 * @parameter bearer token
 * @return response
 */
Route::post('customer/profile', 'CustomerController@profile')->name('customer.profile');
/*
 * Customer Profile Edit
 * @parameter bearer token,name,mobile,email
 * @return response
 * 
 */
Route::post('customer/profile/edit', 'CustomerController@editprofile')->name('customer_profile.edit');

});

/**
 * Admin Login.
 *
 * @parameter email , password
 *
 * @return response bearer access token
 */
Route::post('login', 'Auth\LoginController@handleLogin');
/*
 *  Admin Logout
 * @parameter bearer token
 * @return response
 */
Route::post('admin/logout', 'Auth\LoginController@handleLogout');

/*
 * Customer Profile
 * @parameter bearer token
 * @return response
 */
Route::post('admin/profile', 'AdminController@profile');
/*
 * Admin Forgot Password Request
 * @param email
 * @return response send OTP to email
 */
Route::post('admin/profile/edit', 'AdminController@editprofile');

/**
 * Request for forgotten-password
 * 
 * @param email
 * 
 */
Route::post('admin/forgot-password','Auth\PasswordController@requestResetPassword');
 
/**
 * 
 * Verify password reset -Token 
 * @param token from reset link
 * @return successCode,email
 * 
 */
Route::get('forgot-password/{token}','Auth\PasswordController@getVerifyToken');

/**
 * Reset the password using parameter 
 *
 * @param  email,token,passwordConfirm
 * @return \Illuminate\Http\Response -confirmation
 */
Route::post('forgot-password/{token}','Auth\PasswordController@resetPassword');
