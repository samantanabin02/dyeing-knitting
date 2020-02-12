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
    });
});

