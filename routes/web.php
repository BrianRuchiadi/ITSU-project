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
Route::get('', function () {
    return redirect('login');
});
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register')->name('auth.register');
Route::get('register/verify', 'Auth\RegisterController@showVerifiedRegister')->middleware('signed');
Route::post('register/verify', 'Auth\RegisterController@verifyRegister')->name('auth.register.verify');
Route::get('/api/country/tel-code', 'Utilities\CountryController@getCountriesTelCodeOptions');

// Reset Password Routes...
Route::get('reset-password', 'Auth\ResetPasswordController@showResetPasswordForm');
Route::post('reset-password', 'Auth\ResetPasswordController@sendEmailLink')->name('auth.reset-password');
Route::get('reset-password/verify', 'Auth\ResetPasswordController@showVerifiedReset')->middleware('signed');
Route::post('reset-password/verify', 'Auth\ResetPasswordController@verifyReset')->name('auth.reset.verify');

// Contract Email Verify
Route::get('contract/email/verify', 'PageController@showContractEmailVerifying');
Route::post('contract/email/verify', 'Utilities\ConfirmationController@contractEmailVerification')->name('contract.email.verify');

Route::group(['middleware' => 'auth'], function() {
    
    Route::get('home', 'PageController@index');
    
    Route::get('change-password', 'PageController@showChangePasswordForm');
    Route::post('change-password', 'Auth\ChangePasswordController@changePassword')->name('auth.change.password');
});

Route::group([
    'middleware' => ['ensure.customer.access:1', 'auth'],
], function () {
    Route::prefix('customer')->group(function () {
        
        // Route
        Route::get('apply', 'PageController@showApplicationForm');
        Route::get('contract', 'Customer\CustomerController@showCustomerContractList');
        Route::get('contract/detail/{contract_id}', 'Customer\CustomerController@showCustomerContractDetail')->name('customer.contract.detail');
        Route::get('contract/resubmit/{contract_id}', 'Customer\CustomerController@showResubmitForm')->name('customer.contract.resubmit');

        // API
        Route::get('/api/users', 'Utilities\UserController@getUsers');
        Route::get('/api/check/user', 'Utilities\UserController@checkUser');
        Route::get('/api/items', 'Utilities\ItemController@getItems');
        Route::get('/api/items/rental', 'Utilities\ItemController@getRentalMonthOptions');
        Route::get('/api/items/rental/price', 'Utilities\ItemController@getRentalMonthOptionsPrice');
        Route::get('/api/countries', 'Utilities\CountryController@getCountriesOptions');
        Route::get('/api/country/tel-code', 'Utilities\CountryController@getCountriesTelCodeOptions');
        Route::get('/api/country/states', 'Utilities\CountryController@getStatesOptions');
        Route::get('/api/state/cities', 'Utilities\CountryController@getCitiesOptions');
        Route::post('/api/apply', 'Customer\CustomerController@submitContractForm');
        Route::post('/api/resubmit/{contract_id}', 'Customer\CustomerController@resubmitContractForm');
        
        // SMS APIs
        Route::post('/api/sms/send','Utilities\ConfirmationController@sendSms');
        Route::post('/api/sms/verify','Utilities\ConfirmationController@verifySms');

        Route::group([
            'middleware' => ['ensure.customer.staff.access:0'],
        ], function () {
            // Route
            Route::get('link/referral', 'PageController@showReferralLink');
            Route::get('contract/search', 'Customer\CustomerController@showSearchResult')->name('customer.contract.search');
            
            // API
            Route::get('/api/link/referral', 'Customer\CustomerController@getReferralLink');
        });
    });
});

Route::group([
    'middleware' => ['ensure.contract.access:1', 'auth'],
], function () {
    Route::prefix('contract')->group(function () {
        
        // Route
        Route::get('pending-contract', 'Contract\ContractController@showPendingContractList');
        Route::post('pending-contract/verify-ctos', 'Contract\ContractController@contractVerifyCTOS')->name('verify.ctos');
        
        Route::get('pending-contract/search', 'Contract\ContractController@showSearchResult')->name('pending.contract.search');
        Route::get('pending-contract/detail/{contract_id}', 'Contract\ContractController@showCustomerContractDetail')->name('pending.contract.detail');
        Route::post('pending-contract/detail/{contract_id}', 'Contract\ContractController@customerContractDecision')->name('pending.contract.decision');
    
        Route::get('approved-contract/search/cnh-doc', 'Contract\ContractController@getContractDetailByCnhDocNo');
        
        Route::get('delivery-order', 'Contract\DeliveryController@showDeliveryOrder');
        Route::get('delivery-order/create', 'Contract\DeliveryController@showCreateDeliveryOrder');
        Route::get('delivery-order/detail/{delivery_order_id}', 'Contract\DeliveryController@showDeliveryOrderDetail')->name('delivery.order.detail');
        
        Route::get('invoices', 'Contract\InvoiceController@showInvoicesByGeneratedDate');
        Route::get('invoices/list', 'Contract\InvoiceController@showInvoicesListByDate');
        Route::get('invoices/{invoice}', 'Contract\InvoiceController@showInvoiceDetail');
        
        // API
        Route::get('api/approved-contract/delivery-ready/search', 'Contract\ContractController@showApproveContractUndeliveredSearchResult');

        Route::post('api/delivery-order/create', 'Contract\DeliveryController@createDeliveryOrder');
        Route::post('api/delivery-order/{contractDeliveryOrder}/resubmit', 'Contract\DeliveryController@resubmitDeliveryOrder');
        Route::post('api/invoice/generate', 'Contract\ReserveController@generateInvoice');
    });
});