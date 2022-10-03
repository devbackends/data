<?php
Route::group(['middleware' => 'web'], function () {
  Route::prefix('/users')->group(function () {

    Route::get('/login', 'Devvly\Core\Http\Controllers\UsersController@login')
      ->defaults('_config', ['view' => 'core::login'])
      ->name('core.login');

    Route::post('/login',
      'Devvly\Core\Http\Controllers\UsersController@loginAction')
      ->defaults('_config', ['redirect' => 'core.home',])
      ->name('core.login-action');

    Route::get('/verify', 'Devvly\Core\Http\Controllers\UsersController@verify')
      ->defaults('_config', ['view' => 'core::verify'])
      ->name('core.verify');

    Route::post('/verify-distributor',
      'Devvly\Core\Http\Controllers\UsersController@verifyDistributor')
      ->defaults('_config', ['redirect' => 'core.home',])
      ->name('core.verify-distributor');

    Route::get('/register',
      'Devvly\Core\Http\Controllers\UsersController@register')
      ->defaults('_config', ['view' => 'core::register'])
      ->name('core.register');


    Route::post('/register',
      'Devvly\Core\Http\Controllers\UsersController@registerAction')
      ->defaults('_config', ['redirect' => 'core.subscription',])
      ->name('core.register-action');

    Route::get('/verify-account/{id}',
      'Devvly\Core\Http\Controllers\UsersController@verifyAccount')
      ->defaults('_config', ['redirect' => 'core.login',])
      ->name('core.verify-account');


    Route::get('/subscription', 'Devvly\Core\Http\Controllers\UsersController@subscription')
      ->defaults('_config', ['view' => 'core::subscription'])
      ->name('core.subscription');

    Route::post('/subscription',
      'Devvly\Core\Http\Controllers\UsersController@subscriptionAction')
      ->defaults('_config', ['redirect' => 'core.home',])
      ->name('core.subscription-action');

    Route::get('/home', 'Devvly\Core\Http\Controllers\UsersController@home')
      ->defaults('_config', ['view' => 'core::home'])
      ->name('core.home')
      ->middleware('Subscription');

    Route::get('/api-keys',
      'Devvly\Core\Http\Controllers\UsersController@apiKeys')
      ->defaults('_config', ['view' => 'core::api-keys'])
      ->name('core.api_keys')
      ->middleware('Subscription');

    Route::post('/change-password',
      'Devvly\Core\Http\Controllers\UsersController@changePassword')
      ->name('core.change-password')
      ->middleware('Subscription');

    Route::get('/logout', 'Devvly\Core\Http\Controllers\UsersController@logout')
      ->defaults('_config', ['redirect' => 'core.login',])
      ->name('core.logout');

    Route::post('/register-api-key',
      'Devvly\Core\Http\Controllers\UsersController@registerApiKey')
      ->name('core.register-api-key')
      ->middleware('Subscription');

    Route::get('/get-api-keys',
      'Devvly\Core\Http\Controllers\UsersController@getApiKeys')
      ->name('core.get-api-keys')
      ->middleware('Subscription');

    Route::get('/delete-api-key/{id}',
      'Devvly\Core\Http\Controllers\UsersController@deleteApiKeys')
      ->name('core.delete-api-keys')
      ->middleware('Subscription');
  });
  Route::get('/account', 'Devvly\Core\Http\Controllers\UsersController@account')
    ->defaults('_config', ['view' => 'core::change-password'])
    ->name('core.change-password');
});