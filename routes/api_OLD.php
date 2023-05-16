<?php

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});*/

Route::group(['prefix' => '/v1/'], function () {
	/*
	|--------------------------------------------------------------------------
	| global  API Routes
	|--------------------------------------------------------------------------
	*/
	Route::get('notification/{user}', 'Api\UserController@getUserNotificationByUserId');

	Route::post('check_user', 'Api\UserController@login');
	Route::post('user/resend_otp', 'Api\UserController@resend_otp');
	//TODO application utility route section
	Route::get('how-it-works', 'Api\UtilityController@getHowItWorks');
	Route::get('check-zone', 'Api\UtilityController@checkZone');
	Route::get('global-setting', 'Api\UtilityController@globalSetting');
	Route::get('app-version', 'Api\UtilityController@appVersion');
	Route::get('phone_code', 'Api\UtilityController@getPhoneCode');
	Route::get('language', 'Api\UtilityController@getLanguage');
	Route::post('user/update-location', 'Api\UserController@updateCurrentLocation');
	// TODO cms page
	Route::get('cms/{page}', 'Api\CmsController@index');

	// TODO global setting
     Route::get('home', 'Api\UtilityController@home');
     //TODO section
		Route::get('product_details', 'Api\ProductController@productDetails');
		Route::resource('product', 'Api\ProductController')->only(['index', 'show']);
		
	Route::post('user/profile', 'Api\UserController@details');
	Route::get('cart', 'Api\CartController@index');
	Route::get('category', 'Api\UtilityController@getCategory');
	Route::get('user/getmembership', 'Api\MembershipController@getmembership');

	Route::post('order/statusUpdate', 'Api\OrderController@statusUpdate');
	Route::resource('order', 'Api\OrderController');

	Route::get('offer-slider', 'Api\UtilityController@getOfferSlider');

	//TODO Utility section routes
		Route::get('subcategory/{category_id}', 'Api\UtilityController@getSubCategoryByCategoryId');
		Route::get('slider', 'Api\UtilityController@getSlider');
		Route::resource('delivery_location', 'Api\DeliveryLocationController');
		Route::post('user/notification', 'Api\UserController@notification');

		//TODO cart section
		Route::any('clear-cart', 'Api\CartController@clearCart');
		Route::post('cart/reorder', 'Api\CartController@reOrder');
		Route::resource('cart', 'Api\CartController');
Route::get('getcouponcodes','Api\CouponController@getcouponcodes');
	
		
	/*
	|--------------------------------------------------------------------------
	| Guest User API Routes
	|--------------------------------------------------------------------------
	*/
	Route::group(['middleware' => 'guest:api'], function () {
		//TODO user Routes
		Route::post('user/change_language_driver', 'Api\UserController@driverChangeLanguage');
		Route::post('user/login', 'Api\UserController@login');
		
		Route::post('user/tokenUpdate', 'Api\UserController@userTokenUpdate');
		Route::post('user/signup', 'Api\UserController@register');
		Route::post('user/driverRegister', 'Api\UserController@driverRegister');
		Route::post('user/driverProfile', 'Api\UserController@driverProfile');
		Route::post('user/driverUpdate', 'Api\UserController@driverUpdate');
		Route::post('user/driverLogin', 'Api\UserController@driverLogin');
		Route::post('user/driverUpdateUserLocation', 'Api\UserController@driverUpdateUserLocation');
		Route::post('user/driverOrderList', 'Api\UserController@driverOrderList');
		Route::post('user/driverOrderDetail', 'Api\UserController@driverOrderDetail');
		Route::post('user/driverOrderCompletedList', 'Api\UserController@driverOrderCompletedList');
		Route::post('user/driverOrderNotificationList', 'Api\UserController@driverOrderNotificationList');
		Route::post('user/driverDiliveryConfirm', 'Api\UserController@driverDiliveryConfirm');
		Route::get('user/locationTracker', 'Api\UserController@locationTracker');
		Route::post('user/driverOrderReturn', 'Api\UserController@driverOrderReturn');
		Route::post('user/driverAssignment', 'Api\UserController@driverAssignment');
		Route::post('user/orderStatusChange', 'Api\UserController@orderStatusChange');
		Route::post('user/shopperLogin', 'Api\UserController@shopperLogin');
		Route::post('user/shopperOrderList', 'Api\UserController@shopperOrderList');
		Route::post('user/shopperOrderDetail', 'Api\UserController@shopperOrderDetail');
		Route::post('user/shopperProfile', 'Api\UserController@shopperProfile');
		Route::post('user/shopperAssignment', 'Api\UserController@shopperAssignment');
		Route::post('user/shopperUpdate', 'Api\UserController@shopperUpdate');
		Route::post('user/shopperUpdateUserLocation', 'Api\UserController@shopperUpdateUserLocation');
		Route::post('user/productStatus', 'Api\UserController@productStatus');
		Route::post('user/newProduct', 'Api\UserController@newProduct');
		Route::post('user/categoryList', 'Api\UserController@categoryList');
		Route::post('user/productList', 'Api\UserController@categoryProductList');
		Route::post('user/updatePrice', 'Api\UserController@updatePrice');
		Route::post('user/manageUpdatePrice', 'Api\UserController@manageUpdatePrice');
		Route::post('user/ManageOutStock', 'Api\UserController@ManageOutStock');
		Route::post('user/update/unavailable', 'Api\UserController@updateUnavailability');
		Route::post('user/update/orderstatus', 'Api\UserController@updateOrderStatus');
		Route::post('user/vendorDetail', 'Api\UserController@getVendorDetail');

Route::get('user/check_membership', 'Api\MembershipController@check_membership');
Route::get('user/wallet_recharge', 'Api\MembershipController@wallet_recharge');


	});

	/*
	|--------------------------------------------------------------------------
	|Authenticated  User API Routes
	|--------------------------------------------------------------------------
	*/

	Route::get('sample-notification', 'Api\OrderController@testNotification');
	Route::get('product-demo', 'Api\ProductController@listDemo');
	Route::group(['middleware' => 'auth:api'], function () {

		
		
		

		
		Route::get('city', 'Api\UtilityController@getCity');
		//TODO user section routes
		//Route::post('user/profile', 'Api\UserController@details');
		Route::post('user/update', 'Api\UserController@update');

		Route::post('user/addmembership', 'Api\MembershipController@addmembership');


		

		
		Route::get('user/wallet_history', 'Api\UserController@getwalletHistories');
		Route::post('user/update_wallet', 'Api\UserController@updateWallet');

		
		Route::get('admin-notification', 'Api\UserController@adminNotification');
		Route::post('user/delete-notification', 'Api\UserController@deleteNotification');
		Route::post('user/mark-as-read-notification', 'Api\UserController@markAsReadNotification');
		Route::post('user/change_language', 'Api\UserController@changeLanguage');

		Route::post('user/login_otp_verify', 'Api\UserController@login_otp_verify');
		Route::get('user/logout', 'Api\UserController@logout');

		//TODO delivery location section routes
		

		

		

		Route::resource('wish_list', 'Api\WishLishController');

		//TODO delivery tims section
		Route::get('delivery-time', 'Api\UtilityController@deliveryDay');
		Route::get('delivery-time_old', 'Api\UtilityController@deliveryDay_old');
		
		//TODO checkout section
		Route::post('checkout', 'Api\OrderController@checkout');
		//TODO  product order section
		
		


	Route::post('checkcoupon','Api\CouponController@checkcoupon');
	Route::resource('coupon','Api\CouponController');

	});

});
