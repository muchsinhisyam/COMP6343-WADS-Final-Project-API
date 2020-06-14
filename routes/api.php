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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Register and Login Routes
Route::post('register', 'API\RegisterController@register');
Route::post('login', 'API\LoginController@login');

// Products Routes
Route::resource('products', 'API\ProductController');
Route::get('products/category/{id}', 'API\ProductController@view_products_by_category');
Route::get('/product-details/{id}', 'API\ProductController@view_product_details');
Route::get('/admin/products-photo', 'API\ProductController@view_product_photos')
    ->middleware('is_admin');

// Customer Info Routes
Route::resource('customer-info', 'API\CustomerInfoController')
    ->middleware('auth');

// Custom Order Routes
Route::get('/custom-order/{id}', 'API\CustomOrderController@view_customer_info')
    ->middleware('auth', 'is_logged_user');
Route::post('/custom-order', 'API\CustomOrderController@update_and_create')
    ->middleware('auth');
Route::get('/detail-custom-order/{id}', 'API\CustomOrderController@view_custom_order_details')
    ->middleware('auth', 'is_ordered_by_logged_user');
Route::get('/admin/view-custom-orders', 'API\CustomOrderController@view_custom_orders')
    ->middleware('is_admin');
Route::get('/admin/view-custom-orders/{id}/delete', 'API\CustomOrderController@delete_custom_orders')
    ->middleware('is_admin');
Route::get('/admin/view-custom-orders/{id}/download', 'API\CustomOrderController@download_custom_images')
    ->middleware('is_admin');
Route::get('/admin/view-custom-orders/{id}/download-payment', 'API\CustomOrderController@download_payment_images')
    ->middleware('is_admin');
Route::post('/admin/view-custom-orders/{id}/update', 'API\CustomOrderController@update_custom_orders')
    ->middleware('is_admin');
Route::get('/admin/view-custom-orders/{id}/edit', 'API\CustomOrderController@edit_custom_order')
    ->middleware('is_admin');

// Cart Routes
Route::get('/cart', 'API\CartController@view_cart')
    ->middleware('auth');
Route::get('/cart/{id}', 'API\CartController@insertProductToCart')
    ->middleware('auth');
Route::post('/cart/{id}/update', 'API\CartController@update_cart_details')
    ->middleware('auth');
Route::get('/cart/{id}/delete', 'API\CartController@delete_cart_details')
    ->middleware('auth');

// Admin Routes
Route::get('/admin', 'API\AdminController@index')
    ->middleware('is_admin');
Route::get('/admin/products', 'API\AdminController@view_products')
    ->middleware('is_admin');
Route::get('/admin/users', 'API\AdminController@view_users')
    ->middleware('is_admin');
Route::get('/admin/users-info', 'API\AdminController@view_users_info')
    ->middleware('is_admin');
Route::get('/admin/insert-user-form', 'API\AdminController@view_insert_user')
    ->middleware('is_admin');
Route::get('/admin/insert-product-form', 'API\AdminController@view_insert_products')
    ->middleware('is_admin');
Route::get('/admin/insert-product-photo-form', 'API\AdminController@view_insert_product_photo')
    ->middleware('is_admin');
Route::get('/admin/users/{id}/update-user-form', 'API\AdminController@edit_user')
    ->middleware('is_admin');
Route::get('/admin/products/{id}/update-product-form', 'API\AdminController@edit')
    ->middleware('is_admin');
Route::post('/admin/insert-products', 'API\AdminController@create')
    ->middleware('is_admin');
Route::post('/admin/insert-product-photo', 'API\AdminController@insert_product_photo')
    ->middleware('is_admin');
Route::post('/admin/insert-user', 'API\AdminController@insert_user')
    ->middleware('is_admin');
Route::post('/admin/users/{id}/update', 'API\AdminController@update_user')
    ->middleware('is_admin');
Route::post('/admin/products/{id}/update', 'API\AdminController@update')
    ->middleware('is_admin');
Route::get('/admin/users/{id}/delete', 'API\AdminController@delete_user')
    ->middleware('is_admin');
Route::get('/admin/products/{id}/delete', 'API\AdminController@delete')
    ->middleware('is_admin');
Route::get('/admin/products-photo/{id}/delete', 'API\AdminController@delete_product_photo')
    ->middleware('is_admin');
Route::post('/admin/users-info/{id}/update', 'API\AdminController@update_users_info')
    ->middleware('is_admin');
Route::get('/admin/users-info/{id}/update-form', 'API\AdminController@edit_users_info')
    ->middleware('is_admin');
Route::get('/admin/users-info/{id}/delete', 'API\AdminController@delete_users_info')
    ->middleware('is_admin');
Route::get('/admin/view-stock-orders', 'API\AdminController@view_stock_orders')
    ->middleware('is_admin');
Route::get('/admin/view-stock-orders/{id}/delete', 'API\AdminController@delete_stock_orders')
    ->middleware('is_admin');
Route::post('/admin/view-stock-orders/{id}/update', 'API\AdminController@update_stock_orders')
    ->middleware('is_admin');
Route::get('/admin/view-stock-orders/{id}/edit', 'API\AdminController@edit_stock_order')
    ->middleware('is_admin');
Route::get('/admin/view-stock-order-details', 'API\AdminController@view_stock_order_details')
    ->middleware('is_admin');
Route::get('/admin/view-stock-order-details/{id}/delete', 'API\AdminController@delete_stock_order_details')
    ->middleware('is_admin');

// Order Routes
Route::get('/orders/{id}/invoice', 'API\OrderController@view_invoice');
Route::get('/checkout/{id}', 'API\OrderController@view_checkout')
    ->middleware('auth', 'cart_not_empty');
Route::post('/checkout', 'API\OrderController@createTransaction')
    ->middleware('auth', 'cart_not_empty');
Route::get('/orders/{id}', 'API\OrderController@view_orders')
    ->middleware('auth', 'is_logged_user');
Route::get('/orders/{id}/delete', 'API\OrderController@delete_order')
    ->middleware('auth');
Route::get('/pay/{id}', 'API\OrderController@view_pay')
    ->middleware('auth', 'is_ordered_by_logged_user');
Route::post('/pay{id}', 'API\OrderController@insertTransactionPhotos')
    ->middleware('auth', 'is_ordered_by_logged_user');
Route::get('/detail-order/{id}', 'API\OrderController@view_order_details')
    ->middleware('auth', 'is_ordered_by_logged_user');
Route::get('/admin/view-stock-orders/{id}/download-payment', 'API\OrderController@download_payment_images')
    ->middleware('is_admin');
