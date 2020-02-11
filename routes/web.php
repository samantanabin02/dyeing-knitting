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

        Route::post('questions-delete',
            array('as' => 'questions-delete',
                'uses'     => 'QuestionController@multi_destroy',
            ));

        Route::get('questions-activate/{id}',
            array('as' => 'questions-activate',
                'uses'     => 'QuestionController@question_activate',
            ));

        Route::get('questions-deactivate/{id}',
            array('as' => 'questions-deactivate',
                'uses'     => 'QuestionController@question_deactivate',
            ));

        Route::get('approved-question',
            array('as' => 'approved-question',
                'uses'     => 'QuestionController@approved_question',
            ));

        Route::get('rejected-question',
            array('as' => 'rejected-question',
                'uses'     => 'QuestionController@rejected_question',
            ));
        Route::resource('questions', 'QuestionController');

        Route::post('company-list',
            array('as' => 'company-index',
                'uses'     => 'CompanyController@index',
            ));

    });
});
Route::group(['middleware' => 'user_guest'], function () {
    Route::get('/user/register',
        array('as' => 'user-register',
            'uses'     => 'UserAuth\RegisterController@showRegistrationForm',
        ));
    Route::post('/user/register',
        array('as' => 'user-register',
            'uses'     => 'UserAuth\RegisterController@register',
        ));
    Route::get('/user/login',
        array('as' => 'user-login',
            'uses'     => 'UserAuth\LoginController@showLoginForm',
        ));

    Route::post('/user/login',
        array('as' => 'user-login',
            'uses'     => 'UserAuth\LoginController@login',
        ));
});
Route::prefix('user')->group(function () {
    Route::group(array('namespace' => 'UserAuth', 'middleware' => 'user_auth'), function () {
        Route::get('/logout',
            array('as' => 'user-logout',
                'uses'     => 'LoginController@logout',
            ));
        Route::post('/logout',
            array('as' => 'user-logout',
                'uses'     => 'LoginController@logout',
            ));
    });
    Route::group(array('namespace' => 'User', 'middleware' => 'user_auth'), function () {
        Route::get('/dashboard',
            array('as' => 'user-dashboard',
                'uses'     => 'DashboardController@index',
            ));
        Route::post('/dashboard',
            array('as' => 'user-dashboard',
                'uses'     => 'DashboardController@index',
            ));
    });
});
