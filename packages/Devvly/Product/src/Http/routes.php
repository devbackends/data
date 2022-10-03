<?php
Route::group(['prefix' => 'api', 'middleware' => ['Product']], function() {
  Route::get('products', 'Devvly\Product\Http\Controllers\Api\ProductController@getAllProducts')->name('product.get-all');
  Route::get('product/{id}', 'Devvly\Product\Http\Controllers\Api\ProductController@findProductById')->name('product.get-by-id');
  Route::get('product-images/{id}', 'Devvly\Product\Http\Controllers\Api\ProductController@findProductImages')->name('product.get-product-images');
  Route::get('categories', 'Devvly\Product\Http\Controllers\Api\ProductController@getAllCategories')->name('product.get-all-categories');
  Route::get('category/{id}', 'Devvly\Product\Http\Controllers\Api\ProductController@getCategoryById')->name('product.get-category-by-id');
  Route::get('manufacturers', 'Devvly\Product\Http\Controllers\Api\ProductController@getAllManufacturers')->name('product.get-all-manufacturer');
  Route::get('manufacturer/{id}', 'Devvly\Product\Http\Controllers\Api\ProductController@getManufacturerById')->name('product.get-manufacturer-by-id');
  Route::get('stock/{id}', 'Devvly\Product\Http\Controllers\Api\ProductController@getStockOfProduct')->name('product.get-stock-of-product');
  Route::get('stock-sku/{sku}', 'Devvly\Product\Http\Controllers\Api\ProductController@getStockBySkuOfProduct')->name('product.get-stock-of-product-by-sku');
  Route::get('distributors', 'Devvly\Product\Http\Controllers\Api\ProductController@getDistributors')->name('product.get-distributors');

});
Route::group(['middleware' => 'web'], function () {
  Route::prefix('/products')->group(function () {
Route::get('price-changes', 'Devvly\Product\Http\Controllers\ProductController@home')
  ->defaults('_config', ['view' => 'products::products.home']);
Route::get('get-products-price-change', 'Devvly\Product\Http\Controllers\ProductController@getProductsPriceChange');
    Route::get('test-webhook', 'Devvly\Product\Http\Controllers\ProductController@testWebhook');
  });
});
