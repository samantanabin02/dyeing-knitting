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

/*Route::get('/', function () {
return view('welcome');
});*/

Route::get('/',
    array('as' => 'home',
        'uses'     => 'HomeController@index',
    ));
Route::group(['middleware' => 'admin_guest'], function () {
    Route::get('/admin',
        array('as' => 'admin-login',
            'uses'     => 'AdminAuth\LoginController@showLoginForm',
        ));
    Route::get('/admin/login',
        array('as' => 'admin-login',
            'uses'     => 'AdminAuth\LoginController@showLoginForm',
        ));
    Route::post('/admin/login',
        array('as' => 'admin-login',
            'uses'     => 'AdminAuth\LoginController@login',
        ));
    Route::get('/admin/twof-login',
        array('as' => 'admin-twof-login',
            'uses'     => 'AdminAuth\LoginController@twof_login',
        ));
    Route::post('/admin/twof-login',
        array('as' => 'admin-twof-login',
            'uses'     => 'AdminAuth\LoginController@twof_login',
        ));
    Route::post('/admin/password-sent',
        array('as' => 'admin-password-sent',
            'uses'     => 'AdminAuth\LoginController@password_sent',
        ));
});
Route::prefix('admin')->group(function () {
    Route::group(array('namespace' => 'AdminAuth', 'middleware' => 'admin_auth'), function () {
        Route::get('/logout',
            array('as' => 'admin-logout',
                'uses'     => 'LoginController@logout',
            ));
        Route::post('/logout',
            array('as' => 'admin-logout',
                'uses'     => 'LoginController@logout',
            ));
    });
    Route::group(array('namespace' => 'Admin', 'middleware' => 'admin_auth'), function () {

        Route::get('/dashboard',
            array('as' => 'admin-dashboard',
                'uses'     => 'DashboardController@index',
            ));

        Route::get('/change-password',
            array('as' => 'admin-changepassword',
                'uses'     => 'DashboardController@changepassword',
            ));

        Route::post('/change-password',
            array('as' => 'admin-changepassword',
                'uses'     => 'DashboardController@changepassword',
            ));

        Route::get('/change-profile',
            array('as' => 'admin-changeprofile',
                'uses'     => 'DashboardController@changeprofile',
            ));

        Route::post('/change-profile',
            array('as' => 'admin-changeprofile',
                'uses'     => 'DashboardController@changeprofile',
            ));

        Route::resource('users', 'UserController');

        Route::post('users-index',
            array('as' => 'users-index',
                'uses'     => 'UserController@index',
            ));

        Route::post('users-delete',
            array('as' => 'users-delete',
                'uses'     => 'UserController@multi_destroy',
            ));

        Route::get('/site-settings',
            array('as' => 'site-settings',
                'uses'     => 'SitesettingController@index',
            ));

        Route::post('/site-settings',
            array('as' => 'site-settings',
                'uses'     => 'SitesettingController@index',
            ));

        Route::post('company-list',
            array('as' => 'company-index',
                'uses'     => 'CompanyController@index',
            ));

        Route::resource('items', 'ItemController');
        Route::get('items-index',
            array('as' => 'items-index',
                'uses'     => 'ItemController@index',
            ));
        Route::post('items-index',
            array('as' => 'items-index',
                'uses'     => 'ItemController@index',
            ));
        Route::post('items',
            array('as' => 'items.store',
                'uses'     => 'ItemController@store',
            ));
        Route::post('items-delete',
            array('as' => 'items-delete',
                'uses'     => 'ItemController@multi_destroy',
            ));
        //+++++++++++++++++++++++++++++++++++++++++++++++++
        Route::resource('company', 'CompanyController');
        Route::post('company-index',
            array('as' => 'company-index',
                'uses'     => 'CompanyController@index',
            ));
        Route::post('company-delete',
            array('as' => 'company-delete',
                'uses'     => 'CompanyController@multi_destroy',
            ));
        //+++++++++++++++++++++++++++++++++++++++++++++++++
        Route::resource('unittype', 'UnitTypeController');
        Route::post('unittype-index',
            array('as' => 'unittype-index',
                'uses'     => 'UnitTypeController@index',
            ));
        Route::post('unittype-delete',
            array('as' => 'unittype-delete',
                'uses'     => 'UnitTypeController@multi_destroy',
            ));
        //+++++++++++++++++++++++++++++++++++++++++++++++++
        Route::resource('itemtype', 'ItemTypeController');
        Route::post('itemtype-index',
            array('as' => 'itemtype-index',
                'uses'     => 'ItemTypeController@index',
            ));
        Route::post('itemtype-delete',
            array('as' => 'itemtype-delete',
                'uses'     => 'ItemTypeController@multi_destroy',
            ));
        //+++++++++++++++++++++++++++++++++++++++++++++++++
        Route::resource('gsts', 'GstController');
        Route::get('gsts-index',
            array('as' => 'gsts-index',
                'uses'     => 'GstController@index',
            ));
        Route::post('gsts-index',
            array('as' => 'gsts-index',
                'uses'     => 'GstController@index',
            ));
        Route::post('gsts',
            array('as' => 'gsts.store',
                'uses'     => 'GstController@store',
            ));
        Route::post('gsts-delete',
            array('as' => 'gsts-delete',
                'uses'     => 'GstController@multi_destroy',
            ));
        //+++++++++++++++++++++++++++++++++++++++++++++++++
        Route::resource('manufacturings', 'ManufacturingController');
        Route::get('manufacturings-index',
            array('as' => 'manufacturings-index',
                'uses'     => 'ManufacturingController@index',
            ));
        Route::post('manufacturings-index',
            array('as' => 'manufacturings-index',
                'uses'     => 'ManufacturingController@index',
            ));
        Route::post('manufacturings',
            array('as' => 'manufacturings.store',
                'uses'     => 'ManufacturingController@store',
            ));
        Route::post('manufacturings-delete',
            array('as' => 'manufacturings-delete',
                'uses'     => 'ManufacturingController@multi_destroy',
            ));
        //+++++++++++++++++++++++++++++++++++++++++++++++++
        Route::resource('purchase', 'PurchaseController');
        Route::get('purchase-index',
            array('as' => 'purchase-index',
                'uses'     => 'PurchaseController@index',
            ));
        Route::post('purchase-index',
            array('as' => 'purchase-index',
                'uses'     => 'PurchaseController@index',
            ));
        Route::post('purchase',
            array('as' => 'purchase.store',
                'uses'     => 'PurchaseController@store',
            ));
        Route::post('purchase-delete',
            array('as' => 'purchase-delete',
                'uses'     => 'PurchaseController@multi_destroy',
            ));
        //+++++++++++++++++++++++++++++++++++++++++++++++++
        Route::resource('sales', 'SalesController');
        Route::get('sales-index',
            array('as' => 'sales-index',
                'uses'     => 'SalesController@index',
            ));
        Route::post('sales-index',
            array('as' => 'sales-index',
                'uses'     => 'SalesController@index',
            ));
        Route::post('sales',
            array('as' => 'sales.store',
                'uses'     => 'SalesController@store',
            ));
        Route::post('sales-delete',
            array('as' => 'sales-delete',
                'uses'     => 'SalesController@multi_destroy',
            ));
        //+++++++++++++++++++++++++++++++++++++++++++++++++
        Route::resource('deliveries', 'DeliveryController');
        Route::get('deliveries-index',
            array('as' => 'deliveries-index',
                'uses'     => 'DeliveryController@index',
            ));
        Route::post('deliveries-index',
            array('as' => 'deliveries-index',
                'uses'     => 'DeliveryController@index',
            ));
        Route::post('deliveries',
            array('as' => 'deliveries.store',
                'uses'     => 'DeliveryController@store',
            ));
        Route::post('deliveries-delete',
            array('as' => 'deliveries-delete',
                'uses'     => 'DeliveryController@multi_destroy',
            ));
    });
});
