<?php

use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

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
// home
Route::get('/', 'pages\HomeController@index');
Route::get('home', 'pages\HomeController@index')->name('home');
// end of home

// search 
Route::get('filter/{category_id}/{collection_id?}', 'pages\FilterController@index')->name('filter');
Route::get('search', 'pages\SearchController@index')->name('search');
Route::get('tag/{tag_id}', 'pages\TagController@index')->name('tag');
// end of search

// coupon page
Route::get('/coupon', 'pages\CouponController@index')->name('coupon');
// coupon

// detail product
Route::get('product-details/{id}', 'pages\DetailController@index')->name('product_details');
Route::get('quick-view', 'pages\DetailController@quick_view');

// end of detail

// login and register
Route::get('login', 'auth\LoginController@index')->name('login');
Route::post('login', 'auth\LoginController@doLogin')->name('doLogin');
Route::get('/register', 'auth\LoginController@index');
Route::post('/register', 'auth\RegisterController@doRegister');
// end of login and register

Route::group(['prefix' => 'compare', 'as' => 'compare.'], function () {
    Route::get('/', 'pages\CompareController@index')->name('index');
    Route::get('/add', 'pages\CompareController@add');
    Route::get('/remove', 'pages\CompareController@remove');
    Route::get('/delete', 'pages\CompareController@delete');
});

Route::group(['prefix' => 'information', 'as' => 'info.'], function () {
    Route::get('/search-terms', 'pages\info\SearchTermsController@index')->name('search_terms');
    Route::group(['prefix' => 'advanced-search', 'as' => 'advanced_search.'], function () {
        Route::get('/index', 'pages\info\AdvancedSearchController@index')->name('index');
        Route::get('/result', 'pages\info\AdvancedSearchController@result')->name('result');
    });
    Route::get('/contact-us', 'pages\info\ContactUsController@index')->name('contact_us');
    Route::post('/contact-us', 'pages\info\ContactUsController@doContact');
    Route::get('/about-us', 'pages\info\AboutUsController@index')->name('about_us');
    Route::group(['prefix' => 'site-map', 'as' => 'site_map.'], function () {
        Route::get('/index', 'pages\info\SiteMapController@category')->name('category');
        Route::get('/result', 'pages\info\SiteMapController@product')->name('product');
    });
});
// account
Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('/', 'pages\account\DashboardController@index')->name('dashboard');

    // info
    Route::group(['prefix' => 'information', 'as' => 'info.'], function () {
        Route::get('/', 'pages\account\InformationController@index')->name('index');
        Route::post('/', 'pages\account\InformationController@edit')->name('doEdit');
    });

    // address
    Route::group(['prefix' => 'addresses', 'as' => 'address.'], function () {
        Route::get('/', 'pages\Account\AddressController@index')->name('index');
        Route::get('/add', 'pages\Account\AddressController@add')->name('add');
        Route::post('/add', 'pages\Account\AddressController@do_add')->name('doAdd');
        Route::get('/edit/{id}', 'pages\Account\AddressController@edit')->name('edit');
        Route::post('/edit', 'pages\Account\AddressController@do_edit')->name('doEdit');
        Route::get('/shipping-address/{id}', 'pages\Account\AddressController@set_primary_shipping_address')->name('shipping_address');
        Route::get('/remove/{id}', 'pages\Account\AddressController@remove')->name('remove');
    });

    // logout
    Route::get('/logout', 'auth\LogoutController@index')->name('logout');

    //wish list
    Route::group(['prefix' => 'wish-list'], function () {
        Route::get('/', 'pages\Account\WishlistController@index');
        Route::get('/add', 'pages\Account\WishlistController@add_to_wish_list');
        Route::post('/edit', 'pages\Account\WishlistController@edit_wish_list');
        Route::get('/remove/{id}', 'pages\Account\WishlistController@remove_wish_list');
        Route::get('/all-to-cart', 'pages\Account\WishlistController@wish_list_to_cart');
    });
    
    
    // reivew
    Route::group(['prefix' => 'review'], function () {
        Route::post('/', 'pages\Account\ReviewController@doReview');
        Route::get('/', 'pages\Account\ReviewController@index');
    });

    // order
    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('/', 'pages\Account\OrderController@index')->name('index');
        Route::get('/details/{id}', 'pages\Account\OrderController@detail')->name('detail');
        Route::get('/cancel/{id}', 'pages\Account\OrderController@cancel')->name('cancel');
        Route::get('/re-order/{id}', 'pages\Account\OrderController@re_order')->name('re_order');
    });

    // password
    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::get('/', 'pages\Account\PasswordController@index')->name('index');
        Route::post('/', 'pages\Account\PasswordController@change_password')->name('change_password');
    });
});
// end account

// cart
Route::group(['prefix' => 'cart','as'=>'cart.'], function () {
    Route::get('/', 'pages\CartController@index');
    Route::get('/add', 'pages\CartController@add_to_cart');
    Route::post('/update', 'pages\CartController@update_cart');
    Route::post('/apply-coupon', 'pages\CartController@apply_coupon');
    Route::get('/remove', 'pages\CartController@remove_item_from_cart');
    Route::get('/remove-coupon', 'pages\CartController@remove_coupon')->name('remove_coupon');
    Route::get('/delete', 'pages\CartController@remove_cart')->name('delete');
    Route::get('/check', 'pages\CartController@check');
});
// end cart

// check out
Route::group(['prefix' => 'check-out','as' => 'checkout.'], function () {
    Route::get('/', 'pages\CheckOutController@index');
    Route::post('/shipping-checkout', 'pages\CheckOutController@shipping_check_out')->name('shipping_address_checkout');
    Route::post('/get-shipping-address', 'pages\CheckOutController@get_shipping_address')->name('get_shipping_address');
    Route::post('/payment-checkout', 'pages\CheckOutController@payment_check_out')->name('payment_checkout');
    Route::post('/get-payment', 'pages\CheckOutController@get_payment')->name('get_payment');
    Route::get('/final-check', 'pages\CheckOutController@final_check')->name('final_check');
});
// end check out

// thanks page
Route::get('delivery/{id}', 'pages\DeliveryController@index')->name('delivery');
// end


Route::group(['prefix' => 'administrator', 'namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/login', 'auth\LoginController@index')->name('login');
        Route::post('/login', 'auth\LoginController@doLogin')->name('doLogin');
        Route::get('/logout', 'auth\LogoutController@index')->name('doLogout');

        Route::get('/profile', 'auth\ProfileController@index')->name('profile');
        Route::post('/profile', 'auth\ProfileController@doEdit')->name('doEdit');
        Route::get('/password','auth\PasswordController@index')->name('password');
        Route::post('/password','auth\PasswordController@change_password')->name('doChangePassword');

    });

    Route::get('/', 'DashboardController@index')->name('index');

    Route::group(['prefix' => 'panel','as'=>'admin.'], function () {
        Route::get('/', 'AdminController@index')->name('index');
        Route::get('/activate/{id}', 'AdminController@doActivate')->name('activate');
        Route::get('/deactivate/{id}', 'AdminController@doDeactivate')->name('deactivate');
        Route::get('/bulk-action', 'AdminController@bulk_action')->name('bulk_action');
        Route::get('/remove/{id}', 'AdminController@doRemove')->name('remove');
        Route::get('/restore/{id}', 'AdminController@doRestore')->name('restore');
        Route::get('/delete/{id}', 'AdminController@doDelete')->name('delete');
        Route::get('/recycle', 'AdminController@recycle')->name('recycle');

    });

    // products
    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::get('/', 'ProductController@index')->name('index'); //
        Route::get('/activate/{id}', 'ProductController@doActivate');
        Route::get('/deactivate/{id}', 'ProductController@doDeactivate');
        Route::get('/bulk-action', 'ProductController@bulk_action')->name('bulk_action');
        Route::get('/add', 'ProductController@add')->name('add');
        Route::post('/add', 'ProductController@doAdd');
        Route::get('/edit/{id}', 'ProductController@edit');
        Route::post('/edit', 'ProductController@doEdit');
        Route::get('/remove/{id}', 'ProductController@doRemove');
        Route::get('/restore/{id}', 'ProductController@doRestore');
        Route::get('/delete/{id}', 'ProductController@doDelete');
        Route::get('/import', 'ProductController@import');
        Route::post('/import', 'ProductController@doImport')->name('import');
        Route::get('/recycle', 'ProductController@recycle')->name('recycle');
        Route::group(['prefix' => '{id}/gallery', 'as' => 'image.'], function () {
            Route::get('/', 'ProductImageController@index')->name('index');
            Route::get('/bulk-action', 'ProductImageController@bulk_action')->name('bulk_action');
            Route::get('/activate/{image_id}', 'ProductImageController@doActivate')->name('activate');
            Route::get('/deactivate/{image_id}', 'ProductImageController@doDeactivate')->name('deactivate');
            Route::get('/remove/{image_id}', 'ProductImageController@doRemove')->name('remove');
            Route::get('/add', 'ProductImageController@add')->name('add');
            Route::post('/add', 'ProductImageController@doAdd')->name('doAdd');
            Route::get('/edit/{image_id}', 'ProductImageController@edit')->name('edit');
            Route::post('/edit', 'ProductImageController@doEdit')->name('doEdit');
            Route::get('/recycle', 'ProductImageController@recycle')->name('recycle');
            Route::get('/restore/{image_id}', 'ProductImageController@doRestore')->name('restore');
            Route::get('/delete/{image_id}', 'ProductImageController@doDelete')->name('delete');
        });
    });

    // category
    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('/', 'CategoryController@index')->name('index');
        Route::get('/activate/{id}', 'CategoryController@doActivate')->name('activate');
        Route::get('/deactivate/{id}', 'CategoryController@doDeactivate')->name('deactivate');
        Route::get('/bulk-action', 'CategoryController@bulk_action')->name('bulk_action');
        Route::get('/add', 'CategoryController@add')->name('add');
        Route::post('/add', 'CategoryController@doAdd')->name('doAdd');
        Route::get('/edit/{id}', 'CategoryController@edit')->name('edit');
        Route::post('/edit', 'CategoryController@doEdit')->name('doEdit');
        Route::get('/remove/{id}', 'CategoryController@doRemove')->name('remove');
        Route::get('/recycle', 'CategoryController@recycle')->name('recycle');
        Route::get('/restore/{id}', 'CategoryController@doRestore')->name('restore');
        Route::get('/delete/{id}', 'CategoryController@doDelete')->name('delete');
    });

    // producer
    Route::group(['prefix' => 'producer', 'as' => 'producer.'], function () {
        Route::get('/', 'ProducerController@index')->name('index');
        Route::get('/activate/{id}', 'ProducerController@doActivate')->name('activate');
        Route::get('/deactivate/{id}', 'ProducerController@doDeactivate')->name('deactivate');
        Route::get('/bulk-action', 'ProducerController@bulk_action')->name('bulk_action');
        Route::get('/add', 'ProducerController@add')->name('add');
        Route::post('/add', 'ProducerController@doAdd')->name('doAdd');
        Route::get('/edit/{id}', 'ProducerController@edit')->name('edit');
        Route::post('/edit', 'ProducerController@doEdit')->name('doEdit');
        Route::get('/remove/{id}', 'ProducerController@doRemove')->name('remove');
        Route::get('/recycle', 'ProducerController@recycle')->name('recycle');
        Route::get('/restore/{id}', 'ProducerController@doRestore')->name('restore');
        Route::get('/delete/{id}', 'ProducerController@doDelete')->name('delete');
    });

    // coupon
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
        Route::get('/', 'CouponController@index')->name('index');
        Route::get('/activate/{id}', 'CouponController@doActivate')->name('activate');
        Route::get('/deactivate/{id}', 'CouponController@doDeactivate')->name('deactivate');
        Route::get('/bulk-action', 'CouponController@bulk_action')->name('bulk_action');
        Route::get('/add', 'CouponController@add')->name('add');
        Route::post('/add', 'CouponController@doAdd')->name('doAdd');
        Route::get('/edit/{id}', 'CouponController@edit')->name('edit');
        Route::post('/edit', 'CouponController@doEdit')->name('doEdit');
        Route::get('/remove/{id}', 'CouponController@doRemove')->name('remove');
        Route::get('/restore/{id}', 'CouponController@doRestore')->name('restore');
        Route::get('/delete/{id}', 'CouponController@doDelete')->name('delete');
        Route::get('/recycle', 'CouponController@recycle')->name('recycle');
    });

    // collection
    Route::group(['prefix' => 'collection', 'as' => 'collection.'], function () {
        Route::get('/', 'CollectionController@index')->name('index');
        Route::get('/activate/{id}', 'CollectionController@doActivate')->name('activate');
        Route::get('/deactivate/{id}', 'CollectionController@doDeactivate')->name('deactivate');
        Route::get('/bulk-action', 'CollectionController@bulk_action')->name('bulk_action');
        Route::get('/add', 'CollectionController@add')->name('add');
        Route::post('/add', 'CollectionController@doAdd')->name('doAdd');
        // Route::post('/add/product', 'CollectionProductController@doAdd');
        // Route::post('/edit/product', 'CollectionProductController@doEdit');
        Route::get('/edit/{id}', 'CollectionController@edit')->name('edit');
        Route::post('/edit', 'CollectionController@doEdit')->name('doEdit');
        Route::get('/remove/{id}', 'CollectionController@doRemove')->name('remove');
        Route::get('/recycle', 'CollectionController@recycle')->name('recycle');
        Route::get('/restore/{id}', 'CollectionController@doRestore')->name('restore');
        Route::get('/delete/{id}', 'CollectionController@doDelete')->name('delete');

        // collection product
        Route::group(['prefix' => '{id}/modify', 'as' => 'product.'], function () {
            Route::get('/', 'CollectionProductController@index')->name('index');
            Route::get('/bulk-action', 'CollectionProductController@bulk_action')->name('bulk_action');
            Route::get('/activate/{product_id}', 'CollectionProductController@doActivate')->name('activate');
            Route::get('/deactivate/{product_id}', 'CollectionProductController@doDeactivate')->name('deactivate');
            Route::get('/remove/{product_id}', 'CollectionProductController@doRemove')->name('remove');
            Route::get('/restore/{product_id}', 'CollectionProductController@doRestore')->name('restore');
            Route::get('/delete/{product_id}', 'CollectionProductController@doDelete')->name('delete');
            Route::get('/add', 'CollectionProductController@add')->name('add');
            Route::post('/add', 'CollectionProductController@doAdd')->name('doAdd');
            Route::get('/edit/{product_id}', 'CollectionProductController@edit')->name('edit');
            Route::post('/edit', 'CollectionProductController@doEdit')->name('doEdit');
            Route::get('/recycle', 'CollectionProductController@recycle')->name('recycle');
        });
    });

    // Review
    Route::group(['prefix' => 'review', 'as' => 'review.'], function () {
        Route::get('/', 'ReviewController@index')->name('index');
        Route::get('/activate/{id}', 'ReviewController@doActivate')->name('activate');
        Route::get('/deactivate/{id}', 'ReviewController@doDeactivate')->name('deactivate');
        Route::get('/bulk-action', 'ReviewController@bulk_action')->name('bulk_action');
        Route::get('/detail/{id}', 'ReviewController@detail')->name('detail');
        Route::get('/remove/{id}', 'ReviewController@doRemove')->name('remove');
        Route::get('/restore/{id}', 'ReviewController@doRestore')->name('restore');
        Route::get('/delete/{id}', 'ReviewController@doDelete')->name('delete');
        Route::get('/recycle', 'ReviewController@recycle')->name('recycle');
    });

    // Order
    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('/', 'OrderController@index')->name('index');
        Route::get('/bulk-action', 'OrderController@bulk_action')->name('bulk_action');
        Route::get('/activate/{id}', 'OrderController@doActivate')->name('activate');
        Route::get('/deactivate/{id}', 'OrderController@doDeactivate')->name('deactivate');
        Route::get('/confirm/{id}', 'OrderController@doConfirm')->name('confirm');
        Route::get('/unconfirm/{id}', 'OrderController@doUnConfirm')->name('unconfirm');
        Route::get('/paid/{id}', 'OrderController@doPaid')->name('paid');
        Route::get('/unpaid/{id}', 'OrderController@doUnPaid')->name('unpaid');
        Route::get('/add', 'OrderController@add')->name('add');
        Route::post('/add', 'OrderController@doAdd')->name('doAdd');
        Route::get('/history', 'OrderController@history')->name('history');
        Route::get('/cancel', 'OrderController@cancel')->name('cancel');
        Route::get('/details/{id}', 'OrderController@detail')->name('detail');
        Route::get('/remove/{id}', 'OrderController@doRemove')->name('remove');
        Route::post('/update', 'OrderController@update')->name('update');
        Route::get('/recycle', 'OrderController@recycle')->name('recycle');
    });

    // users
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/', 'UserController@index')->name('index');
        Route::get('/activate/{id}', 'UserController@doActivate')->name('activate');
        Route::get('/deactivate/{id}', 'UserController@doDeactivate')->name('deactivate');
        Route::get('/promote/{id}', 'UserController@doPromote')->name('promote');
        Route::get('/bulk-action', 'UserController@bulk_action')->name('bulk_action');
        Route::get('/remove/{id}', 'UserController@doRemove')->name('remove');
        Route::get('/restore/{id}', 'UserController@doRestore')->name('restore');
        Route::get('/delete/{id}', 'UserController@doDelete')->name('delete');
        Route::get('/recycle', 'UserController@recycle')->name('recycle');
    });

    // advertise
    Route::group(['prefix' => 'advertise', 'as' => 'advertise.'], function () {
        Route::get('/', 'AdvertiseController@index')->name('index');
        Route::get('/bulk-action', 'AdvertiseController@bulk_action')->name('bulk_action');
        Route::get('/add', 'AdvertiseController@add')->name('add');
        Route::post('/add', 'AdvertiseController@doAdd')->name('doAdd');
        Route::get('/edit/{id}', 'AdvertiseController@edit')->name('edit');
        Route::post('/edit', 'AdvertiseController@doEdit')->name('doEdit');
        Route::get('/activate/{id}', 'AdvertiseController@doActivate')->name('activate');
        Route::get('/deactivate/{id}', 'AdvertiseController@doDeactivate')->name('deactivate');
        Route::get('/remove/{id}', 'AdvertiseController@doRemove')->name('remove');
        Route::get('/restore/{id}', 'AdvertiseController@doRestore')->name('restore');
        Route::get('/delete/{id}', 'AdvertiseController@doDelete')->name('delete');
        Route::get('/recycle', 'AdvertiseController@recycle')->name('recycle');
    });

    //tag
    Route::group(['prefix' => 'tag', 'as' => 'tag.'], function () {
        Route::get('/', 'TagController@index')->name('index');
        Route::get('/bulk-action', 'TagController@bulk_action')->name('bulk_action');
        Route::get('/activate/{id}', 'TagController@doActivate')->name('activate');
        Route::get('/deactivate/{id}', 'TagController@doDeactivate')->name('deactivate');
        Route::get('/add', 'TagController@add')->name('add');
        Route::post('/add', 'TagController@doAdd')->name('doAdd');
        Route::get('/edit/{id}', 'TagController@edit')->name('edit');
        Route::post('/edit', 'TagController@doEdit')->name('doEdit');
        Route::get('/remove/{id}', 'TagController@doRemove')->name('remove');
        Route::get('/recycle', 'TagController@recycle')->name('recycle');
        Route::get('/restore/{id}', 'TagController@doRestore')->name('restore');
        Route::get('/delete/{id}', 'TagController@doDelete')->name('delete');

        // Tag product
        Route::group(['prefix' => '{id}/modify', 'as' => 'product.'], function () {
            Route::get('/', 'TagProductController@index')->name('index');
            Route::get('/bulk-action', 'TagProductController@bulk_action')->name('bulk_action');
            Route::get('/activate/{product_id}', 'TagProductController@doActivate')->name('activate');
            Route::get('/deactivate/{product_id}', 'TagProductController@doDeactivate')->name('deactivate');
            Route::get('/remove/{product_id}', 'TagProductController@doRemove')->name('remove');
            Route::get('/restore/{product_id}', 'TagProductController@doRestore')->name('restore');
            Route::get('/delete/{product_id}', 'TagProductController@doDelete')->name('delete');
            Route::get('/add', 'TagProductController@add')->name('add');
            Route::post('/add', 'TagProductController@doAdd')->name('doAdd');
            Route::get('/edit/{product_id}', 'TagProductController@edit')->name('edit');
            Route::post('/edit', 'TagProductController@doEdit')->name('doEdit');
            Route::get('/recycle', 'TagProductController@recycle')->name('recycle');
        });
    });

    // contact
    Route::group(['prefix' => 'contact', 'as' => 'contact.'], function () {
        Route::get('/', 'ContactController@index')->name('index');
        Route::get('/bulk-action', 'ContactController@bulk_action')->name('bulk_action');
        Route::get('/detail/{id}', 'ContactController@detail')->name('detail');
        Route::get('/read/{id}', 'ContactController@doRead')->name('read');
        Route::get('/unread/{id}', 'ContactController@doUnread')->name('unread');
        Route::get('/remove/{id}', 'ContactController@doRemove')->name('remove');
        Route::get('/restore/{id}', 'ContactController@doRestore')->name('restore');
        Route::get('/delete/{id}', 'ContactController@doDelete')->name('delete');
        Route::get('/recycle', 'ContactController@recycle')->name('recycle');
    });
});
