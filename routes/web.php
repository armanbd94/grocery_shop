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

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth']], function () {

    /***** BEGIN :: DASHBOARD RELATED ALL ROUTES *****/
    Route::get('/admin', 'HomeController@index')->name('dashboard')->middleware('verified');
    /***** END :: DASHBOARD RELATED ALL ROUTES *****/

    /***** BEGIN :: DASHBOARD RELATED ALL ROUTES *****/
    Route::get('/error', 'HomeController@error');
    /***** END :: DASHBOARD RELATED ALL ROUTES *****/

    /*================================================================================*
    ---------------------->|| BEGIN :: FIXED ROUTES FOR BASE ||<-----------------------
    *================================================================================*/

    /***** BEGIN :: USER RELATED ALL ROUTES *****/
    Route::get('/user','UserController@index')->middleware('verified');
    Route::post('/user-list','UserController@userList');
    Route::post('/change-user-status','UserController@changeUserStatus');
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register')->middleware('verified');
    Route::get('/user/edit/{id}', 'UserController@edit');
    Route::get('/user/view/{id}', 'UserController@show');
    Route::post('/user/update','UserController@update');
    Route::post('/change-user-password','UserController@changeUserPassword');
    Route::get('/user/delete/{id}', 'UserController@delete');

    Route::get('/profile','UserController@profile')->middleware('verified');
    Route::post('/update-profile','UserController@updateProfile');
    Route::post('/update-password','UserController@updatePassword');
    Route::post('/add-profile-photo','UserController@addProfilePhoto');

    /***** END :: USER RELATED ALL ROUTES *****/

    /***** BEGIN :: ROLE RELATED ALL ROUTES *****/
    Route::resource('/role','RoleController')->middleware('verified');
    Route::post('/role-list', 'RoleController@getRoleList');
    Route::post('/role/update','RoleController@update');
    Route::get('/role/delete/{id}', 'RoleController@destroy');
    /***** END :: ROLE RELATED ALL ROUTES *****/

    /***** BEGIN :: MENU RELATED ALL ROUTES *****/
    Route::resource('/menu','MenuController')->middleware('verified');
    Route::post('/menu-list', 'MenuController@menuList');
    Route::post('/menu/update','MenuController@update');
    Route::get('/menu/delete/{id}', 'MenuController@destroy');
    /***** END :: MENU RELATED ALL ROUTES *****/

    /***** BEGIN :: PAGE RELATED ALL ROUTES *****/
    Route::resource('/page', 'PageController')->middleware('verified');
    Route::post('/page-list', 'PageController@pageList');
    Route::post('/page/update', 'PageController@update');
    Route::get('/page/delete/{id}', 'PageController@destroy');
    /***** END :: PAGE RELATED ALL ROUTES *****/

    /***** BEGIN :: PERMISSION RELATED ALL ROUTES *****/
    Route::get('/permission', 'PermissionController@index')->middleware('verified');
    Route::get('/permission-list/{role}', 'PermissionController@permissionList');
    Route::post('/permission-add', 'PermissionController@savePermission');
    /***** END :: PERMISSION RELATED ALL ROUTES *****/

    /***** BEGIN :: SITE SETTINGS RELATED ALL ROUTES *****/
    Route::get('/settings', 'SettingsController@index')->middleware('verified');
    Route::get('/setting-data', 'SettingsController@settingData');
    Route::post('/store-site-data', 'SettingsController@store_site_data');
    Route::post('/store-mail-data', 'SettingsController@store_mail_data');
    Route::post('/store-about-contents','SettingsController@store_about_contents');
    Route::post('/store-terms-conditions','SettingsController@store_terms_conditions');
    /***** END :: SITE SETTINGS RELATED ALL ROUTES *****/
    

    /*================================================================================*
    ---------------------->|| END :: FIXED ROUTES FOR BASE ||<------------------------
    *================================================================================*/

    /***** BEGIN :: Customer RELATED ALL ROUTES *****/
    Route::resource('/customer','CustomerController')->middleware('verified');
    Route::post('/customer-list', 'CustomerController@customerList');
    Route::post('/customer/update','CustomerController@update');
    Route::get('/customer/delete/{id}', 'CustomerController@destroy');
    Route::get('/customer/view/{id}', 'CustomerController@show');
    Route::post('/change-customer-status','CustomerController@changeCustomerStatus');
    /***** END :: Customer RELATED ALL ROUTES *****/
    
    /***** BEGIN :: PRODUCT CATEGORY RELATED ALL ROUTES *****/
    Route::resource('/product-category','CategoryController')->middleware('verified');
    Route::post('/product-category-list','CategoryController@getList');
    Route::get('/product-category/delete/{id}', 'CategoryController@destroy');
    Route::post('/change-product-category-status','CategoryController@changeStatus');
    Route::get('/product-categories', 'CategoryController@category_list');
    /***** END :: PRODUCT CATEGORY RELATED ALL ROUTES *****/

    /***** BEGIN :: PRODUCT BRAND RELATED ALL ROUTES *****/
    Route::resource('/product-brand','BrandController')->middleware('verified');
    Route::post('/product-brand-list','BrandController@getList');
    Route::get('/product-brand/delete/{id}', 'BrandController@destroy');
    Route::post('/change-product-brand-status','BrandController@changeStatus');
    /***** END :: PRODUCT BRAND RELATED ALL ROUTES *****/


    /***** BEGIN :: Districts RELATED ALL ROUTES *****/
    Route::resource('/district','DistrictsController')->middleware('verified');
    Route::post('/district-list','DistrictsController@getList');
    Route::get('/district/delete/{id}', 'DistrictsController@destroy');
    //Route::post('/change-product-brand-status','BrandController@changeStatus');
    /***** END :: PRODUCT BRAND RELATED ALL ROUTES *****/

    /***** BEGIN :: PRODUCT WEIGHT UNIT RELATED ALL ROUTES *****/
    Route::resource('/product-unit','ProductUnitController');
    Route::post('/product-unit-list', 'ProductUnitController@getList');
    Route::get('/product-unit/delete/{id}', 'ProductUnitController@destroy');
    Route::post('/change-product-unit-status','ProductUnitController@changeStatus');
    /***** END :: PRODUCT WEIGHT UNIT RELATED ALL ROUTES *****/

    /***** BEGIN :: PRODUCT RELATED ALL ROUTES *****/
    Route::resource('/product','ProductController')->middleware('verified');
    Route::post('/product-list','ProductController@getList');
    Route::get('/product-add', 'ProductController@create');
    Route::post('/product-store', 'ProductController@store');
    Route::get('/product/view/{id}', 'ProductController@show');
    Route::post('/product-update', 'ProductController@update');
    Route::post('/product/delete', 'ProductController@destroy');
    Route::post('/change-product-status','ProductController@changeStatus');
    Route::post('/product-price-list','ProductController@product_price_list');
    Route::post('/store-product-price','ProductController@store_product_price');
    Route::post('/update-price-data','ProductController@product_price_update');
    Route::post('/product-image-list','ProductController@product_image_list');
    Route::post('/upload-product-image','ProductController@upload_product_image');
    Route::post('/delete-product-price','ProductController@delete_product_price');
    Route::post('/delete-product-image','ProductController@delete_product_image');
    /***** END :: PRODUCT RELATED ALL ROUTES *****/

     /***** BEGIN :: WEBSITE BANNER RELATED ALL ROUTES *****/
     Route::resource('/banner','BannerController');
     Route::post('/banner-list', 'BannerController@getList');
     Route::post('/banner-delete', 'BannerController@destroy');
     /***** END :: WEBSITE BANNER RELATED ALL ROUTES *****/

     /***** BEGIN :: ORDER RELATED ALL ROUTES *****/
     Route::resource('/order','OrderController');
     Route::post('/order-list', 'OrderController@getList');
     Route::post('/get-ordered-product-list', 'OrderController@get_ordered_product_list');
     Route::get('/order/view/{id}', 'OrderController@show');
     Route::post('/order-delete', 'OrderController@destroy');
     Route::post('/change-status', 'OrderController@change_status');
     Route::post('/change-product-availibilty-status', 'OrderController@change_product_availibilty_status');
     Route::post('/update-ordered-product-qty', 'OrderController@update_ordered_product_qty');
     /***** END :: ORDER RELATED ALL ROUTES *****/
    
     /***** BEGIN :: TIME RELATED ALL ROUTES *****/
     Route::resource('/time-slot','TimeSlotController');
     Route::post('/time-slot-list', 'TimeSlotController@time_slot_list');
     Route::post('/store-time-slot', 'TimeSlotController@store');
     /***** END :: TIME RELATED ALL ROUTES *****/

     /***** BEGIN :: PRODUCT BRAND RELATED ALL ROUTES *****/
    Route::resource('/website-page','WebsitePageController')->middleware('verified');
    Route::post('/website-page-list','WebsitePageController@getList');
    Route::post('/website-page/delete', 'WebsitePageController@destroy');
    /***** END :: PRODUCT BRAND RELATED ALL ROUTES *****/

     /***** BEGIN :: MESSAGE RELATED ALL ROUTES *****/
     Route::resource('/message','MessageController')->middleware('verified');
     Route::post('/message-list','MessageController@getList');
     Route::get('/message/view/{id}', 'MessageController@show')->middleware('verified');
     Route::post('/message-delete', 'MessageController@destroy');
     /***** END :: MESSAGE RELATED ALL ROUTES *****/

     /***** BEGIN :: PRODUCT CATEGORY RELATED ALL ROUTES *****/
    Route::resource('/coupon','CouponController')->middleware('verified');
    Route::post('/coupon-list','CouponController@getList');
    Route::get('/coupon/delete/{id}', 'CouponController@destroy');
    Route::post('/change-coupon-status','CouponController@changeStatus');
    /***** END :: PRODUCT CATEGORY RELATED ALL ROUTES *****/

    Route::get('/get-notification/{id}','HomeController@get_notification');
    Route::post('/get-dashboard-data','HomeController@get_dashboard_data');

    
});


Route::get('/', 'WebsiteHomeController@index');
Route::get('/content/{page_url}', 'WebsiteHomeController@page_content');
Route::get('/error/404', 'WebsiteHomeController@error');
Route::get('/search', 'WebsiteHomeController@searching_data');
Route::get('/search-product', 'WebsiteHomeController@search_product');
Route::get('/contact', 'MessageController@contact');
Route::post('/store-contact-message','MessageController@store_contact_message');


Route::get('/account-login', 'CustomerAuth\CustomerLoginController@showLoginForm')->name('customer.login');
Route::post('/account-login', 'CustomerAuth\CustomerLoginController@login');
Route::post('/account-logout', 'CustomerAuth\CustomerLoginController@logout');
Route::get('/account-register', 'CustomerAuth\CustomerRegisterController@showRegistrationForm');
Route::post('/account-registration', 'CustomerAuth\CustomerRegisterController@register');

Route::get('/account/forgot-password', 'CustomerAuth\CustomerForgotPasswordController@index');
Route::post('/account/send-recover-link', 'CustomerAuth\CustomerForgotPasswordController@sendLink');
Route::get('/verify/reset/{id}/{token}', 'CustomerAuth\CustomerVerificationController@verifyEmail');
Route::post('/account/reset-password', 'CustomerAuth\CustomerResetPasswordController@resetPassword');

Route::get('/customer-login/{provider}', 'CustomerAuth\CustomerLoginController@redirectToProvider');
Route::get('/customer-login/{provider}/callback','CustomerAuth\CustomerLoginController@handleProviderCallback');

//customer auth group
Route::group(['middleware' => ['auth:customer']], function () {
    // Route::get('/account', 'CustomerAccountController@index');
    Route::get('/account/profile', 'CustomerAccountController@index');
    Route::get('/account/address', 'CustomerAccountController@customerAddress');
    Route::get('/account/order-history', 'CustomerAccountController@orderHistory');
    Route::get('/account/order-view/{id}', 'CustomerAccountController@orderView');
    Route::post('/account/profile-update', 'CustomerAccountController@profileUpdate');
    Route::post('/account/add-address', 'CustomerAccountController@addAddress');
    Route::post('/account/update-address', 'CustomerAccountController@updateAddress');
    Route::post('/account/edit-address', 'CustomerAccountController@editAddress');
    Route::post('/account/delete-address', 'CustomerAccountController@destroyAddress');
    Route::get('/account/password-change', 'CustomerAccountController@passwordChange');
    Route::post('/account/password-update', 'CustomerAccountController@passwordUpdate');
});

Route::post('/active-tab-products', 'WebsiteHomeController@active_tab_products');
Route::get('/single-product/{slug}', 'WebsiteProductController@single_product');
Route::get('/category/{slug}', 'CategorywiseProductController@index');
Route::post('/categorywise-product', 'CategorywiseProductController@categorywise_product');

Route::get('/cart', 'CartController@index');
Route::post('/cart-content', 'CartController@cart_content');
Route::post('/coupon-check','CartController@coupon_check');
Route::post('/add-to-cart', 'CartController@add_to_cart');
Route::post('/update-cart', 'CartController@update_cart');
Route::post('/remove-cart', 'CartController@remove_cart');
Route::post('/total-cart-item', 'CartController@total_cart_item');
Route::post('/sidebar-cart', 'CartController@sidebar_cart');

Route::get('/checkout', 'CheckoutController@index');
Route::post('/user-checkout-type', 'CheckoutController@user_checkout_type');
Route::post('/checkout-option-body', 'CheckoutController@checkout_option_view');
Route::post('/billing-option-body', 'CustomerAddressController@billing_option_view');
Route::post('/delivery-option-body', 'CustomerAddressController@delivery_option_view');
Route::post('/delivery-slot-body', 'CheckoutController@delivery_slot_view');
Route::post('/payment-method-body', 'CheckoutController@payment_method_view');
Route::post('/confirm-option-body', 'CheckoutController@confirm_option_view');


Route::post('/store-address-details', 'CustomerAddressController@store_address_details');
Route::post('/store-delivery-slot', 'CheckoutController@store_delivery_slot');
Route::post('/confirm-order', 'CheckoutController@confirm_order');


