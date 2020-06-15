<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('user', function (Request $request) {
    return $request->user();
});

// Register and Login Routes
Route::post('register', 'API\RegisterController@register');
Route::post('login', 'API\LoginController@login');

// Products Routes
Route::resource('products', 'API\ProductController');
Route::get('admin/products-photo', 'API\ProductController@showAllPhotos');
Route::get('products/{id}/photo', 'API\ProductController@showProductPhotos');
Route::get('products/{id}/category', 'API\ProductController@showProductCategory');
Route::get('products/{id}/color', 'API\ProductController@showProductColor');

// User & Customer Info Routes
Route::get('users', 'API\UserController@index');
Route::get('users/{id}', 'API\UserController@show');
Route::put('users/{id}', 'API\UserController@update');
Route::delete('users/{id}', 'API\UserController@destroy');
Route::post('customer-info', 'API\UserController@createCustomerInfo');
Route::get('customer-info', 'API\UserController@showAllCustomerInfo');
Route::get('customer-info/{id}', 'API\UserController@showCustomerInfo');
Route::put('customer-info/{id}', 'API\UserController@updateCustomerInfo');
Route::delete('customer-info/{id}', 'API\UserController@destroyCustomerInfo');

// Cart Routes
Route::get('cart', 'API\CartController@view_cart');
Route::get('cart/{id}', 'API\CartController@insertProductToCart');
Route::post('cart/{id}/update', 'API\CartController@update_cart_details');
Route::get('cart/{id}/delete', 'API\CartController@delete_cart_details');

// Custom Order Routes
Route::get('custom-order/{id}', 'API\CustomOrderController@view_customer_info');
Route::post('custom-order', 'API\CustomOrderController@update_and_create');
Route::get('detail-custom-order/{id}', 'API\CustomOrderController@view_custom_order_details');
Route::get('admin/view-custom-orders', 'API\CustomOrderController@view_custom_orders');
Route::get('admin/view-custom-orders/{id}/delete', 'API\CustomOrderController@delete_custom_orders');
Route::get('admin/view-custom-orders/{id}/download', 'API\CustomOrderController@download_custom_images');
Route::get('admin/view-custom-orders/{id}/download-payment', 'API\CustomOrderController@download_payment_images');
Route::post('admin/view-custom-orders/{id}/update', 'API\CustomOrderController@update_custom_orders');
Route::get('admin/view-custom-orders/{id}/edit', 'API\CustomOrderController@edit_custom_order');

// Cart Routes
Route::get('cart', 'API\CartController@view_cart');
Route::get('cart/{id}', 'API\CartController@insertProductToCart');
Route::post('cart/{id}/update', 'API\CartController@update_cart_details');
Route::get('cart/{id}/delete', 'API\CartController@delete_cart_details');

// Admin Routes
Route::get('admin/products', 'API\AdminController@view_products');
Route::get('admin/users', 'API\AdminController@view_users');
Route::get('admin/users-info', 'API\AdminController@view_users_info');
Route::get('admin/insert-user-form', 'API\AdminController@view_insert_user');
Route::get('admin/insert-product-form', 'API\AdminController@view_insert_products');
Route::get('admin/insert-product-photo-form', 'API\AdminController@view_insert_product_photo');
Route::get('admin/users/{id}/update-user-form', 'API\AdminController@edit_user');
Route::get('admin/products/{id}/update-product-form', 'API\AdminController@edit');
Route::post('admin/insert-products', 'API\AdminController@create');
Route::post('admin/insert-product-photo', 'API\AdminController@insert_product_photo');
Route::post('admin/insert-user', 'API\AdminController@insert_user');
Route::post('admin/users/{id}/update', 'API\AdminController@update_user');
Route::post('admin/products/{id}/update', 'API\AdminController@update');
Route::get('admin/users/{id}/delete', 'API\AdminController@delete_user');
Route::get('admin/products/{id}/delete', 'API\AdminController@delete');
Route::get('admin/products-photo/{id}/delete', 'API\AdminController@delete_product_photo');
Route::post('admin/users-info/{id}/update', 'API\AdminController@update_users_info');
Route::get('admin/users-info/{id}/update-form', 'API\AdminController@edit_users_info');
Route::get('admin/users-info/{id}/delete', 'API\AdminController@delete_users_info');
Route::get('admin/view-stock-orders', 'API\AdminController@view_stock_orders');
Route::get('admin/view-stock-orders/{id}/delete', 'API\AdminController@delete_stock_orders');
Route::post('admin/view-stock-orders/{id}/update', 'API\AdminController@update_stock_orders');
Route::get('admin/view-stock-orders/{id}/edit', 'API\AdminController@edit_stock_order');
Route::get('admin/view-stock-order-details', 'API\AdminController@view_stock_order_details');
Route::get('admin/view-stock-order-details/{id}/delete', 'API\AdminController@delete_stock_order_details');

// Order Routes
Route::get('orders/{id}/invoice', 'API\OrderController@view_invoice');
Route::get('checkout/{id}', 'API\OrderController@view_checkout');
Route::post('checkout', 'API\OrderController@createTransaction');
Route::get('orders/{id}', 'API\OrderController@view_orders');
Route::get('orders/{id}/delete', 'API\OrderController@delete_order');
Route::get('pay/{id}', 'API\OrderController@view_pay');
Route::post('pay{id}', 'API\OrderController@insertTransactionPhotos');
Route::get('detail-order/{id}', 'API\OrderController@view_order_details');
Route::get('admin/view-stock-orders/{id}/download-payment', 'API\OrderController@download_payment_images');
