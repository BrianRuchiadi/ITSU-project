<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register')->name('auth.register');
Route::get('register/verify', 'Auth\RegisterController@showVerifiedRegister')->middleware('signed');
Route::post('register/verify', 'Auth\RegisterController@verifyRegister')->name('auth.register.verify');

// Reset Password Routes...
Route::get('reset-password', 'Auth\ResetPasswordController@showResetPasswordForm');
Route::post('reset-password', 'Auth\ResetPasswordController@sendEmailLink')->name('auth.reset-password');
Route::get('reset-password/verify', 'Auth\ResetPasswordController@showVerifiedReset')->middleware('signed');
Route::post('reset-password/verify', 'Auth\ResetPasswordController@verifyReset')->name('auth.reset.verify');

Route::group(['middleware' => 'auth'], function() {
    Route::get('home', 'PageController@index');

    // Change Password Routes
    Route::get('change-password', 'PageController@showChangePasswordForm');
    Route::post('change-password', 'Auth\ChangePasswordController@changePassword')->name('auth.change.password');

    Route::get('link/referral', 'PageController@showReferralLink');
    Route::get('apply', 'PageController@showApplicationForm');

    // APIs
    Route::get('/api/users', 'Utilities\UserController@getUsers');
    Route::get('/api/items', 'Utilities\ItemController@getItems');
    Route::post('/api/apply', 'Customer\CustomerController@submitContractForm');
    Route::get('/api/link/referral', 'Customer\CustomerController@getReferralLink');
    Route::get('/api/countries', 'Utilities\CountryController@getCountriesOptions');
    Route::get('/api/country/{country}/states', 'Utilities\CountryController@getStatesOptions');
    Route::get('/api/state/{state}/cities', 'Utilities\CountryController@getCitiesOptions');

    // SMS APIs
    Route::post('api/sms/send','Utilities\SmsController@sendSms');
    Route::post('api/sms/verify','Utilities\SmsController@verifySms');

    // Customer Contract List
    Route::get('contract', 'Customer\CustomerController@showCustomerContractList');
    Route::get('contract', 'Customer\CustomerController@showSearchResult')->name('contract.search');
    Route::get('contract/detail/{contract_id}', 'Customer\CustomerController@showCustomerContractDetail')->name('contract.detail');

});
