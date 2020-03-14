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
Route::get('register/verify', 'Auth\RegisterController@showVerifiedRegister');
Route::post('register/verify', 'Auth\RegisterController@verifyRegister')->name('auth.register.verify');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Email Verification Routes...
Route::get('email/verify', 'Auth\VerificationController@show');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify'); // v6.x
/* Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify'); // v5.x */
Route::get('email/resend', 'Auth\VerificationController@resend');


Route::group(['middleware' => 'auth'], function() {
    Route::get('home', 'PageController@index');

    Route::get('link/referral', 'PageController@showReferralLink');
    Route::get('apply', 'PageController@showApplicationForm');

    // APIs
    Route::get('/api/link/referral', 'Customer\CustomerController@getReferralLink');
    Route::get('/api/countries', 'Utilities\CountryController@getCountriesOptions');
    Route::get('/api/country/{country}/states', 'Utilities\CountryController@getStatesOptions');
    Route::get('/api/state/{state}/cities', 'Utilities\CountryController@getCitiesOptions');
});
