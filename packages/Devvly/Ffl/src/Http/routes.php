<?php
Route::group(['prefix' => 'api', 'middleware' => ['Ffl']], function() {
  Route::get('ffl', 'Devvly\Ffl\Http\Controllers\Api\FflController@findFfls')->name('ffl.get');
  Route::post('ffl', 'Devvly\Ffl\Http\Controllers\Api\FflController@findFfls')->name('ffl.post');
  Route::get('ffl/{id}', 'Devvly\Ffl\Http\Controllers\Api\FflController@findFflById')->name('ffl.get-by-id');
  Route::post('ffl/{id}', 'Devvly\Ffl\Http\Controllers\Api\FflController@saveFflInfo')->name('ffl.save-info');
  Route::get('ffl-license/{license}', 'Devvly\Ffl\Http\Controllers\Api\FflController@findFflByLicense')->name('ffl.get-by-license');
  Route::get('ffl-partial/{license}', 'Devvly\Ffl\Http\Controllers\Api\FflController@findFflByPartialLicense')->name('ffl.get-by-partial-license');
  Route::get('get-wassabi-keys', 'Devvly\Ffl\Http\Controllers\Api\FflController@getWassabiKeys')->name('ffl.getWassabiKeys');

});

Route::group(['prefix' => 'ffl-signup'], function () {

  Route::get('/', 'Devvly\Ffl\Http\Controllers\FflOnBoardingController@index')->defaults('_config', [
    'view' => 'ffl::fflonboarding.landing',
  ])->name('ffl.onboarding.landing');

  Route::get('finish', 'Devvly\Ffl\Http\Controllers\FflOnBoardingController@finish')->defaults('_config', [
    'view' => 'ffl::fflonboarding.finish',
  ])->name('ffl.onboarding.finish');

  Route::get('/form', 'Devvly\Ffl\Http\Controllers\FflOnBoardingController@form')->defaults('_config', [
    'view' => 'ffl::fflonboarding.form',
  ])->name('ffl.onboarding.form');
  Route::post('/form', 'Devvly\Ffl\Http\Controllers\FflOnBoardingController@store')->name('ffl.onboarding.form.store');
});
Route::get('/check-ffl-exist/{ffl}', 'Devvly\Ffl\Http\Controllers\FflOnBoardingController@checkFflExist')
  ->name('ffl.onboarding.check-ffl-exist');


