<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'AppController@index');

    //Jobs
    Route::resource('jobs', 'JobsController');
    Route::get('jobs/category/{id}', 'JobsController@jobsByCategories');
    Route::post('shareJob', 'JobsController@shareJob')->name('share-job');
    Route::get('jobsAdmin', 'JobsController@jobsAdmin')->name('jobs-admin');
    Route::patch('update-job-status', 'JobsController@updateJobStatus')->name('job-status');
    Route::get('job/{id}/{app}/work','JobsController@work');
    Route::resource('categories', 'CategoriesController');
    Route::post('order-categories', 'CategoriesController@order');

    //Applications
    Route::post('applyJob', 'ApplicationsController@applyJob')->name('apply-job');
    Route::get('job-app-status/{id}', 'ApplicationsController@appStatus');
    Route::post('change-application-status', 'ApplicationsController@changeStatus');
    Route::get('applications', 'ApplicationsController@myApplications')->name('my-applications');
    Route::get('applications/admin','ApplicationsController@applicationsAdmin')->name('applications-admin');
    Route::post('application/post-message', 'ApplicationsController@postMessage');
    Route::post('application/delete-message', 'ApplicationsController@deleteMessage');

    //Bookmarks
    Route::get('my-bookmarks', 'BookmarksController@userBookmarks')->name('my-bookmarks');
    Route::post('bookmark', 'BookmarksController@store')->name('bookmark');
    Route::delete('bookmark', 'BookmarksController@destroy')->name('bookmark-remove');

    Route::group(['prefix' => 'account'], function () {
        Route::get('/', 'AccountController@index');
        Route::patch('/profile', 'AccountController@updateProfile');
        Route::post('/bio', 'AccountController@updateBio');
    });

    //users
    Route::group(['prefix' => 'users'], function () {
        Route::get('/','UserController@users')->name('users');
        Route::get('{id}/view', ['middleware'=>['ability:admin,read-users'],'uses'=>'UserController@user']);
        Route::post('register', ['middleware' => ['ability:admin,create-users'],'uses'=>'UserController@register']);
        Route::post('{id}/update', ['middleware' => ['ability:admin,update-users'],'uses'=>'UserController@updateUser']);
        Route::post('{id}/roles', ['middleware' => ['ability:admin,update-users'],'uses'=>'UserController@updateUserRoles']);
    });

    //Settings
    Route::group(['prefix' => 'admin'], function () {
        Route::get('settings','AdminController@settings');
        Route::post('settings', 'AdminController@updateEnv');
        Route::post('settings/backup','AdminController@backupEnv');
        Route::get('logs', ['middleware'=>['ability:admin,read-logs'],'uses'=>'AdminController@logs']);
        Route::post('logs', ['middleware'=>['ability:admin,delete-logs'],'uses'=>'AdminController@emptyLog'])->name('empty-log');
        Route::get('debug', ['middleware'=>['ability:admin,read-logs'],'uses'=>'AdminController@debug']);
        Route::post('debug', ['middleware'=>['ability:admin,delete-logs'],'uses'=>'AdminController@emptyDebugLog'])->name('empty-debug');
    });

    //Roles
    Route::group(['prefix' => 'roles', 'middleware' => ['role:admin']], function () {
        Route::get('/', 'Auth\AuthController@roles');
        Route::get('/getRoles', 'Auth\AuthController@rolesJson');
        Route::post('/', 'Auth\AuthController@newRole');
    });
    Route::post('role','Auth\AuthController@showRole');
    Route::post('update-role/{id}','Auth\AuthController@updateRole');

    //Skills
    Route::resource('skills', 'SkillsController');
    Route::post('skills/delete/{id}', 'SkillsController@destroy');
    Route::get('skills-json','SkillsController@skillsJson');
    Route::post('delete-my-skill','SkillsController@deleteMySkill');

    //modules
    Route::resource('modules','ModulesController');
    Route::post('update-module/{id}','ModulesController@update');
    Route::get('module-permissions/{role_id}/{module_id}','Auth\AuthController@permissions');
    Route::post('role-permissions','Auth\AuthController@updateRolePermissions');
    Route::get('perms','ModulesController@perms');

    //paypal gateway
    Route::group(['prefix'=>'paypal'],function(){
        Route::post('/pay','PaypalController@pay');
        Route::post('/success','PaypalController@success');
        Route::get('/success','PaypalController@success');
        Route::get('/cancelled','PaypalController@cancelled');
        Route::post('/notify','PaypalController@notify');
    });

    Route::group(['prefix'=>'payouts'],function(){
       Route::get('/','BillingController@payouts');
        Route::post('stripe','StripeController@charge')->name('stripe-charge');
    });
});

