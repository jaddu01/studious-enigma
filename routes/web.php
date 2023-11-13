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

// Route::get('/', 'Admin\Auth\LoginController@showLoginForm');
// Route::get('/login', 'Admin\Auth\LoginController@showLoginForm');

use App\Http\Controllers\Admin\Inventory\OpeningStockController;
use App\Http\Controllers\Admin\Inventory\StockVerificationController;
use App\User;
use App\UserWallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

Route::get('/test-connection', function () {
    try {
        DB::connection()->getPdo();
        return "Database connection is successful!";
    } catch (\Exception $e) {
        return "Database connection failed: " . $e->getMessage();
    }
});

Route::get('/', 'FrontController@index');
Route::get('/search-product', 'SearchController@searchProduct');
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::post('resendOTP', 'UserController@resendOtp')->name('resendOTP');
Route::post('resendRegisterOTP', 'Auth\RegisterController@resendRegisterOtp')->name('resendRegisterOTP');

Route::post('/mobilelogin', 'Auth\LoginController@mobilelogin')->name('mobile.login');
Route::post('/loginmobile', 'Auth\LoginController@loginmobile')->name('login.mobile');
Route::post('/verifymobileOtp', 'Auth\LoginController@verifymobileOtp')->name('verifymobileOtp');
Route::get('/transferimages', 'FrontController@transferimages');
Route::get('/sendpasswordform', 'FrontController@sendpasswordform');

Route::get('/phpinfo', 'FrontController@phpinfofunc');
Route::post('/sendpassword', 'FrontController@sendpassword')->name('sendpassword');
Route::any('/register', 'Auth\RegisterController@store')->name('register');
Route::any('/createregister', 'Auth\RegisterController@createregister')->name('createregister');
//Route::post('register', 'Auth\RegistrationController@store')->name('register');
Route::get('afterRegister', 'Auth\RegisterController@afterRegister');
Route::get('/customerupdate/{id}', 'FrontController@updatedata');
Route::get('/forgotpassword', 'FrontController@forgotpassword');
Route::get('/profile/update', 'UserController@update');
Route::get('/verifyOtp', 'Auth\RegisterController@verifyOtp');
Route::post('/verifyOtp', 'Auth\RegisterController@verifedOtp')->name('verifyOtp');
Route::get('/profile', 'UserController@show')->middleware('auth')->name('profile');
Route::post('/updateprofile', 'UserController@update')->middleware('auth')->name('updateprofile');
Route::get('/change-password', 'UserController@changePassword')->middleware('auth')->name('change-password');
Route::put('/update-password', 'UserController@updatePassword')->middleware('auth')->name('update-password');
Route::get('/addnewaddress', 'UserController@addnewaddress')->middleware('auth')->name('addnewaddress');
Route::get('/maplocation', 'UserController@maplocation')->middleware('auth');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('/deliverytimes', 'OrderController@deliverytimes');
Route::get('/orderhistory', 'OrderController@orderhistory');
Route::get('/track-order/{id}', 'OrderController@trackorder');
Route::get('/re-order/{id}', 'OrderController@reorder');
Route::get('/invoice/{id}', 'OrderController@invoice');
Route::get('/pdfdownload/{id}', 'OrderController@pdfdownload');
Route::get('/update-order/{id}', 'OrderController@update');


Route::get('/order-payment/{order_id}', 'OrderController@orderPayment');

Route::get('/mycart', 'CartController@mycart');
Route::get('/user/wishlist', 'WishlistController@index');
Route::post('/user/wishlist/store', 'WishlistController@store');
Route::get('/mywallet', 'WalletController@mywallet');
Route::get('/mycoins', 'WalletController@mycoins');
Route::get('/product/{slug}', 'ProductController@productdeatils');
Route::get('/list/{slug}', 'ProductController@productlisting');
Route::get('/api/search', 'Api\SearchController@index');
//Route::get('/update-zone/{id}','FrontController@updateZone');
//Route::get('/get-zone','FrontController@getZone');
Route::get('/update-zone/{id}', 'HomeController@updateZone')->middleware('auth');
Route::get('/get-zone', 'HomeController@getZone')->middleware('auth');
Route::get('/offer/{slug}', 'OfferController@index')->middleware('auth');
Route::get('/import', 'FrontController@importExcel');
Route::get('/importpackage', 'FrontController@importpackage');
Route::get('/zonecode', 'FrontController@zonecode');
Route::get('/category/generate-slug', 'FrontController@categorySlug');
Route::get('/products/generate-slug', 'FrontController@productSlug');
Route::get('/get-home-data', 'FrontController@Home');
Route::get('/privacy-policy', 'CmsController@privacypolicy');
Route::get('/faq', 'CmsController@faq');
Route::get('/contact-us', 'CmsController@contactus');
Route::get('/about-us', 'CmsController@aboutus');
Route::get('/terms-and-condition', 'CmsController@termsandcondition');
Route::get('/support', 'CmsController@support');
Route::get('/joinpatner', 'JoinpartnerController@index');
Route::post('/joinpatner', 'JoinpartnerController@store')->name('joinpartner');

// TODO  admin route section
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('login', 'Auth\LoginController@login')->name('admin.login');
    Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');
    Route::get('register', 'Auth\RegisterController@showRegistrationForm');
    Route::post('register', 'Auth\RegisterController@register')->name('admin.register');

    // Password Reset Routes...
    Route::post('password/email', [
        'as' => 'password.email',
        'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
    ]);
    Route::get('password/reset', [
        'as' => 'password.request',
        'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
    ]);
    Route::get('password/reset/{token}', [
        'as' => 'password.resetpass',
        'uses' => 'Auth\ResetPasswordController@showResetForm'
    ]);
    Route::post('password/reset', [
        'as' => 'password.resetpass.store',
        'uses' => 'Auth\ResetPasswordController@reset'
    ]);


    // TODO  auth admin route section
    Route::group(['middleware' => ['admin.auth']], function () {
        Route::get('/checkAuth', 'UserController@checkAuth')->middleware('admin.auth');

        Route::get('dashboard', 'HomeController@index')->name('admin.auth');
        Route::get('profile', 'HomeController@profile');
        Route::put('update-profile/{id}', 'HomeController@profileUpdate');
        Route::get('change-password/{id}', 'UserController@changePassword')->name('user.change-password');
        Route::put('change-password/{id}', 'UserController@updatePassword');
        Route::post('autologout', 'UserController@autoLogout')->name('autologout');
        // TODO admin user section
        Route::any('get-user-by-param', 'UserController@getUserByPhone')->name('get-user-by-param');
        Route::any('user/datatable', 'UserController@anyData')->name('user.datatable');
        Route::any('user/status', 'UserController@changeStatus')->name('admin.user.status');
        Route::resource('user', 'UserController');

        // TODO admin customer section
        Route::get('tracking/customer-heatmap', 'CustomerController@customerAddressHeatmap')->name('customer.heatmap');
        Route::get('customer/address-map/{id}', 'CustomerController@mapview')->name('customer.mapview');

        Route::any('customer/datatable', 'CustomerController@anyData')->name('customer.datatable');
        Route::get('customer/viewcart/{id}', 'CustomerController@viewcart')->name('customer.viewcart');
        Route::any('customer/status', 'CustomerController@changeStatus')->name('admin.customer.status');
        Route::get('customer/wallet/{id}', 'CustomerController@wallethistory');
        Route::get('wallethistory/datatable', 'CustomerController@wallethistoryData')->name('customer.wallethistory.datatable');
        Route::get('customer/darbaar-coin/{id}', 'CustomerController@darbaarCoinHistory');
        Route::get('darbaar-coin-history/datatable', 'CustomerController@darbaarCoinHistoryData')->name('customer.darbaarCoin.datatable');
        Route::resource('customer', 'CustomerController');


        //TODO delivery location section routes
        Route::any('delivery-location-by-id', 'DeliveryLocationController@getDeliveryAddressById')->name('delivery-location-by-id');
        Route::resource('delivery_location', 'DeliveryLocationController');


        // TODO admin category section

        Route::any('sub-category/{id}', 'CategoryController@index')->name('sub-category.index');

        Route::any('category/datatable', 'CategoryController@anyData')->name('category.datatable');
        Route::any('category/status', 'CategoryController@changeStatus')->name('admin.category.status');
        Route::resource('category', 'CategoryController');

        // admin brand section
        Route::any('brand/datatable', 'BrandController@anyData')->name('brand.datatable');
        Route::any('brand/status', 'BrandController@changeStatus')->name('admin.brand.status');
        Route::resource('brand', 'BrandController');

        // admin coin settings section
        Route::any('coin-settings/datatable', 'CoinSettingsController@anyData')->name('coinSettings.datatable');
        Route::any('coin-settings/status', 'CoinSettingsController@changeStatus')->name('admin.coinSettings.status');
        Route::resource('coin-settings', 'CoinSettingsController');

        // TODO admin order section
        Route::any('order/export/{type}', 'OrderController@exportOrder');
        Route::any('order/edit-qty/{item_id}', 'OrderController@editQty');
        Route::any('order/remove-order-item/{item_id}', 'OrderController@removeOrderItem');


        Route::match(['get', 'post'], 'order/add-product/{order_id}', 'OrderController@addProduct');
        Route::match(['get', 'post'], 'order/add-productlist/{order_id}', 'OrderController@addProductlist');
        Route::match(['get', 'post'], 'order/modify-address/{order_id}', 'OrderController@modifyAddress');
        Route::match(['get', 'post'], 'order/modify-delivery-date-or-slot/{order_id}', 'OrderController@modifyDeliveryDateOrSlot');
        Route::match(['get', 'post'], 'order/change-shopper-and-driver/{order_id}', 'OrderController@changeShopperAndDriver');
        Route::match(['get', 'post'], 'order/add-discount/{order_id}', 'OrderController@addDiscount');
        Route::match(['get', 'post'], 'order/invoice/{order_id}', 'OrderController@invoice')->name('order.invoice');
        Route::match(['get', 'post'], 'order/pdfdownload/{order_id}', 'OrderController@pdfdownload');

        Route::get('order/track/{id}', 'OrderController@trackOrder')->name('order.track');
        Route::post('order/track/current', 'OrderController@trackDriverCurrentCoordinates')->name('order.track.current');

        Route::any('order/datatable', 'OrderController@anyData')->name('order.datatable');
        Route::any('order/datatablenew', 'OrderController@anyDataOrderStatus')->name('order.datatablenew');
        Route::any('order/status', 'OrderController@changeStatus')->name('admin.order.status');
        Route::any('order/statuslist/{id}', 'OrderController@statuslist')->name('order.statuslist');
        Route::any('order/showDetail/{id}', 'OrderController@showDetail')->name('order.showDetail');
        Route::resource('order', 'OrderController');

        Route::any('first_order/datatable', 'FirstOrderController@anyData')->name('first_order.datatable');
        Route::any('first_order/changestatus', 'FirstOrderController@changeStatus')->name('admin.first-order.status');
        Route::resource('first_order', 'FirstOrderController');



        // TODO admin notification section
        Route::any('notification/status', 'NotificationController@changeStatus')->name('admin.notification.status');
        Route::any('notification/datatable', 'NotificationController@anyData')->name('notification.datatable');

        Route::any('notification/unavailable/datatable', 'NotificationController@unavailableData')->name('notification.unavailabledata');
        Route::get('notification/unavailable', 'NotificationController@unavailableProductOrders')->name('admin.notification.unavailable');
        Route::any('notification/update/datatable', 'NotificationController@updateproductData')->name('notification.updateproductData');
        Route::get('notification/shopper', 'NotificationController@updateProducts')->name('admin.notification.update');
        Route::any('notification/order/datatable', 'NotificationController@orderStatusData')->name('notification.orderStatusData');
        Route::get('notification/order', 'NotificationController@orderStatus')->name('admin.notification.update');
        Route::any('notification/address/datatable', 'NotificationController@addressUpdateData')->name('notification.addressStatusData');
        Route::get('notification/address', 'NotificationController@addressUpdate')->name('admin.notification.address');
        Route::get('notification/address/details', 'NotificationController@addressDetails')->name('admin.notification.addressDetails');
        Route::resource('notification', 'NotificationController');

        Route::get('product/addvariant', 'ProductController@addVariant')->name('admin.product.addvariant');
        Route::get('product/variantview', 'ProductController@variantview')->name('admin.product.variantview');
        Route::post('product/savevariant', 'ProductController@saveVariant')->name('admin.product.savevariant');
        Route::get('product/editvariant/{id}', 'ProductController@editvariant')->name('admin.product.editvariant');
        Route::post('product/updatevariant/{id}', 'ProductController@updateVariant')->name('admin.product.updatevariant');
        // TODO admin manual order section


        Route::any('get-delivery-day', 'ManualOrderController@deliveryDay')->name('get-delivery-day');
        Route::any('get-vendor-product', 'ManualOrderController@getVendorProduct')->name('get-vendor-product');

        Route::resource('manual-order', 'ManualOrderController');


        // TODO admin offer section
        Route::any('offer/datatable', 'OfferController@anyData')->name('offer.datatable');
        Route::any('offer/status', 'OfferController@changeStatus')->name('admin.offer.status');
        Route::resource('offer', 'OfferController');

        // TODO admin coupon section
        Route::any('coupon/datatable', 'CouponController@anyData')->name('coupon.datatable');
        Route::any('coupon/status', 'CouponController@changeStatus')->name('admin.coupon.status');
        Route::resource('coupon', 'CouponController');



        // TODO admin slider section
        Route::any('slider/datatable', 'SliderController@anyData')->name('slider.datatable');
        Route::any('slider/status', 'SliderController@changeStatus')->name('admin.slider.status');
        Route::resource('slider', 'SliderController');

        // TODO offer slider section

        Route::any('offer-slider/datatable', 'OfferSliderController@anyData')->name('offer-slider.datatable');
        Route::any('offer-slider/status', 'OfferSliderController@changeStatus')->name('admin.offer-slider.status');
        Route::post('offer-slider/getsubcat', 'OfferSliderController@getSubCategory')->name('offer-slider.sub-cat');
        Route::resource('offer-slider', 'OfferSliderController');

        // TODO how it works section

        Route::any('how-it-works/datatable', 'HowItWorksController@anyData')->name('how-it-works.datatable');
        Route::any('how-it-works/status', 'HowItWorksController@changeStatus')->name('admin.how-it-works.status');
        Route::resource('how-it-works', 'HowItWorksController');

        // TODO how it works section

        // Media Section //

        Route::any('medias/datatable', 'MediasController@anyData')->name('medias.datatable');
        Route::any('medias/status', 'MediasController@changeStatus')->name('admin.medias.status');
        Route::any('medias/delete/{id}', 'MediasController@destroy')->name('medias.delete');
        Route::any('medias/prime-membership-images', 'MediasController@primeMembershipImages')->name('admin.media.prime-membership-image');
        Route::any('medias/prime-membership-image/edit/{id}', 'MediasController@primeMembershipEditImage')->name('admin.media.prime-membership-image.edit');
        Route::any('medias/prime-membership-image/update/{id}', 'MediasController@primeMembershipUpdateImage')->name('admin.media.prime-membership-image.update');
        Route::any('medias/refer-images', 'MediasController@referImages')->name('admin.media.refer-image');
        Route::any('medias/refer-image/edit/{id}', 'MediasController@referEditImage')->name('admin.media.refer-image.edit');
        Route::any('medias/refer-image/update/{id}', 'MediasController@referUpdateImage')->name('admin.media.refer-image.update');

        // Media Section //

        // TODO admin admin notification section
        Route::any('admin-notification/datatable', 'AdminNotificationController@anyData')->name('admin-notification.datatable');
        Route::any('admin-notification/orderNotification', 'AdminNotificationController@orderNotification')->name('admin-notification.orderNotification');
        Route::any('admin-notification/unavailable', 'AdminNotificationController@unavailable')->name('admin-notification.unavailable');
        Route::any('admin-notification/unavailableanydata', 'AdminNotificationController@unavailableanydata')->name('admin-notification.unavailableanydata');



        Route::any('admin-notification/status', 'AdminNotificationController@changeStatus')->name('admin.admin-notification.status');
        Route::resource('admin-notification', 'AdminNotificationController');

        // TODO ads slider section
        Route::any('ads/datatable', 'AdsController@anyData')->name('ads.datatable');
        Route::any('ads/status', 'AdsController@changeStatus')->name('admin.ads.status');
        Route::resource('ads', 'AdsController');

        Route::get('category-image-ads/edit/{id}', 'AdsController@edit_category_ads')->name('edit.category-image-ads');
        Route::any('category-image-ads/update/{id}', 'AdsController@update_catgory_image_ads')->name('update.category-image-ads');

        // TODO admin measurement class section
        Route::any('measurement-class/datatable', 'MeasurementClassController@anyData')->name('measurement-class.datatable');
        Route::any('measurement-class/status', 'MeasurementClassController@changeStatus')->name('admin.measurement-class.status');
        Route::resource('measurement-class', 'MeasurementClassController');

        // TODO admin City section
        Route::any('city/datatable', 'CityController@anyData')->name('city.datatable');
        Route::any('city/status', 'CityController@changeStatus')->name('admin.city.status');
        Route::resource('city', 'CityController');

        // TODO admin delivery time section

        Route::any('delivery-time/details/{id}', 'DelivertTimeController@anyDetailsData')->name('delivery-time.details');

        Route::any('delivery-time/datatable', 'DelivertTimeController@anyData')->name('delivery-time.datatable');
        Route::any('delivery-time/status', 'DelivertTimeController@changeStatus')->name('admin.delivery-time.status');
        Route::resource('delivery-time', 'DelivertTimeController');

        // TODO admin slot time section

        Route::any('slot-time/datatable', 'SlotTimeController@anyData')->name('slot-time.datatable');
        Route::any('slot-time/status', 'SlotTimeController@changeStatus')->name('admin.slot-time.status');
        Route::resource('slot-time', 'SlotTimeController');

        // TODO admin slot group section

        Route::any('slot-group/datatable', 'SlotGroupController@anyData')->name('slot-group.datatable');
        Route::any('slot-group/status', 'SlotGroupController@changeStatus')->name('admin.slot-group.status');
        Route::resource('slot-group', 'SlotGroupController');

        // TODO admin slot group section

        Route::any('week-package/datatable', 'WeekPackageController@anyData')->name('week-package.datatable');
        Route::any('week-package/status', 'WeekPackageController@changeStatus')->name('admin.week-package.status');
        Route::resource('week-package', 'WeekPackageController');

        // TODO admin load slot vs zone  group section
        Route::resource('load-slot-zone', 'SlotZoneController');

        // TODO admin Region section
        Route::any('region/datatable', 'RegionController@anyData')->name('region.datatable');
        Route::any('region/status', 'RegionController@changeStatus')->name('admin.region.status');
        Route::resource('region', 'RegionController');



        // TODO zone section

        Route::get('zone-details', 'ZoneController@getZoneDetailsById')->name('zone-details');
        Route::get('load-zone', 'ZoneController@loadZoneByLat')->name('load-zone');
        Route::get('view-tracking', 'OperationController@viewTracking')->name('view-tracking');
        Route::get('tracking/driver', 'TrackingController@driverTracking')->name('view-tracking-driver');
        Route::get('tracking/shopper', 'TrackingController@shopperTracking')->name('view-tracking-shopper');
        Route::get('tracking/heatmap', 'OrderController@orderHeatmap')->name('heatmap');
        Route::any('operation/view-datatable', 'OperationController@operationData')->name('operation_view.datatable');
        Route::any('operation/view', 'OperationController@show')->name('operation_dashboard');
        Route::any('operation/datatable', 'OperationController@anyData')->name('operation.datatable');
        Route::resource('operation', 'OperationController');



        Route::any('zone/datatable', 'ZoneController@anyData')->name('zone.datatable');
        Route::any('zone/status', 'ZoneController@changeStatus')->name('admin.zone.status');
        Route::any('zone/default', 'ZoneController@makeDefault')->name('admin.zone.default');
        Route::resource('zone', 'ZoneController');

        // TODO Access level section
        Route::any('access_level/datatable', 'AccessLevelController@anyData')->name('access_level.datatable');
        Route::any('access_level/status', 'AccessLevelController@changeStatus')->name('admin.access_level.status');
        Route::resource('access_level', 'AccessLevelController');


        // TODO permission level section
        Route::any('permission_access/datatable', 'PermissionAccessController@anyData')->name('permission_access.datatable');
        Route::any('permission_access/ajax', 'PermissionAccessController@getHtml')->name('permission_access.ajax');
        Route::any('permission_access/status', 'PermissionAccessController@changeStatus')->name('admin.permission_access.status');
        Route::resource('permission_access', 'PermissionAccessController');


        // TODO Anaylitics & Report section
        Route::any('anaylitics/driver/datatable', 'AnayliticsController@driverData')->name('anaylitics.datatable.driver');
        Route::any('analytics/driver', 'AnayliticsController@driver')->name('admin.anaylitics.driver');
        Route::any('anaylitics/shopper/datatable', 'AnayliticsController@shopperData')->name('anaylitics.datatable.shopper');
        Route::any('analytics/shopper', 'AnayliticsController@shopper')->name('admin.anaylitics.shopper');
        Route::any('anaylitics/vendor/datatable', 'AnayliticsController@vendorData')->name('anaylitics.datatable.vendor');
        Route::any('analytics/vendor', 'AnayliticsController@vendor')->name('admin.anaylitics.vendor');
        Route::any('anaylitics/zone/datatable', 'AnayliticsController@zoneData')->name('anaylitics.datatable.zone');
        Route::any('analytics/zone', 'AnayliticsController@zone')->name('admin.anaylitics.zone');
        Route::any('anaylitics/slot-times/datatable', 'AnayliticsController@slotTimesData')->name('anaylitics.datatable.slot-times');
        Route::any('analytics/slot-times', 'AnayliticsController@slotTimes')->name('admin.anaylitics.slot-times');
        Route::any('anaylitics/product/datatable', 'AnayliticsController@productData')->name('anaylitics.datatable.product');
        Route::any('analytics/product', 'AnayliticsController@products')->name('admin.anaylitics.product');
        Route::any('anaylitics/customer/datatable', 'AnayliticsController@customerData')->name('anaylitics.datatable.customer');
        Route::any('analytics/customer', 'AnayliticsController@customers')->name('admin.anaylitics.customer');
        Route::any('anaylitics/datatable', 'AnayliticsController@anyData')->name('anaylitics.datatable');
        Route::any('analytics/order', 'AnayliticsController@orders')->name('admin.anaylitics.order');
        Route::any('anaylitics/ajax', 'AnayliticsController@getHtml')->name('admin.anaylitics.ajax');
        Route::any('anaylitics/status', 'AnayliticsController@changeStatus')->name('admin.anaylitics.status');
        Route::resource('anaylitics', 'AnayliticsController');

        Route::any('analytics/payment-history', 'AnayliticsController@paymentHistory')->name('anaylitics.payment-history');
        Route::any('analytics/payment-history-data', 'AnayliticsController@paymentHistoryData')->name('anaylitics.payment-history-data');




        // TODO admin product section

        /*test*/
        Route::any('product/importtest', 'ProductController@importTest');
        Route::post('product/importExcelToUpdateBestPrice', 'ProductController@importExcelToUpdateBestPrice')->name('admin.product.importExcelToUpdateBestPrice');
        Route::get('product/edit-product-data', 'ProductController@editProductData')->name('admin.product.edit-product-data');
        Route::post('product/edit-product-data', 'ProductController@editProductData')->name('admin.product.edit-product-data');
        /*test*/

        Route::any('product/datatable', 'ProductController@anyData')->name('product.datatable');
        Route::any('product/import', 'ProductController@import')->name('admin.product.import');
        // Route::post('product/importExcel', 'ProductController@importExcel')->name('admin.product.importExcel');
        Route::post('product/importExcel', 'ProductController@excelUploadAndImport')->name('admin.product.importExcel');
        //
        Route::any('product/status', 'ProductController@changeStatus')->name('admin.product.status');
        Route::any('product/image', 'ProductController@deleteImage')->name('admin.product.image');
        Route::any('product/image/default', 'ProductController@makeDefaultImage')->name('admin.product.image.make.default');
        Route::resource('product', 'ProductController');

        // TODO admin vendor-product section
        Route::any('user/product/{user_id}', 'VendorProductController@index');
        Route::post('vendor-product/offer', 'VendorProductController@getOfferValue')->name('vendor-product.get-offer');
        Route::post('vendor-product/get-driver-shopper', 'VendorProductController@getDriverShopper')->name('vendor-product.get-driver-shopper');

        Route::get('vendor-product/edit-product-data', 'VendorProductController@editProductData')->name('admin.vendor-product.edit-product-data');

        Route::post('vendor-product/edit-product-data', 'VendorProductController@editProductData')->name('admin.vendor-product.edit-product-data');

        Route::any('vendor-product/datatable', 'VendorProductController@anyData')->name('vendor-product.datatable');
        Route::any('vendor-product/changeShopperAndDriver', 'VendorProductController@changeShopperAndDriver')->name('vendor-product.changeShopperAndDriver');
        Route::any('vendor-product/shopperassignment', 'VendorProductController@shopperassignment')->name('vendor-product.shopperassignment');
        Route::any('vendor-product/driverassignment', 'VendorProductController@driverassignment')->name('vendor-product.driverassignment');
        Route::any('vendor-product/status', 'VendorProductController@changeStatus')->name('admin.vendor-product.status');
        Route::any('vendor-product/map', 'VendorProductController@mapview')->name('admin.vendor-product.map');
        Route::any('vendor-product/ajax-map', 'VendorProductController@ajax_mapview')->name('ajax_mapview');
        Route::any('vendor-product/import', 'VendorProductController@import')->name('admin.vendorproduct.import');
        Route::post('vendor-product/importExcel', 'VendorProductController@importExcel')->name('admin.vendorproduct.importExcel');;
        Route::resource('vendor-product', 'VendorProductController');
        //autocomplete.search
        Route::any('autocomplete/search', 'VendorProductController@search')->name('autocomplete.search');

        // TODO admin vendor-commission section

        Route::any('vendor-commission/datatable', 'VendorCommissionController@anyData')->name('vendor-commission.datatable');
        Route::resource('vendor-commission', 'VendorCommissionController');

        // TODO admin Revenue section
        Route::any('revenue/getdata', 'RevenueController@getRevenueData')->name('revenue.getdata');
        Route::any('revenue/getcomment', 'RevenueController@getCommentData')->name('revenue.getcomment');

        Route::any('revenue/add', 'RevenueController@storeRevenue')->name('revenue.add');
        Route::any('revenue/comment/add', 'RevenueController@storeComment')->name('revenue.comment.add');
        Route::any('revenue/datatable', 'RevenueController@anyData')->name('revenue.datatable');
        /*Route::any('revenue/update', 'RevenueController@update')->name('revenue.update');*/
        Route::resource('revenue', 'RevenueController');

        // TODO admin setting section
        Route::get('setting/general', 'SettingController@general');
        Route::post('setting/general', 'SettingController@generalUpdate');

        Route::get('setting/site_setting', 'SettingController@site_setting');
        Route::post('setting/site_setting', 'SettingController@siteSettingUpdate');

        Route::get('setting/social_media', 'SettingController@socialMedia');
        Route::post('setting/social_media', 'SettingController@socialMediaUpdate');

        Route::get('setting/api_setting', 'SettingController@apiSetting');
        Route::post('setting/api_setting', 'SettingController@apiSettingUpdate');

        Route::get('setting/app_setting', 'SettingController@appSetting');
        Route::post('setting/app_setting', 'SettingController@appSettingUpdate');

        Route::get('setting/payment', 'SettingController@payment');
        Route::post('setting/payment', 'SettingController@paymentUpdate');

        Route::get('setting/app_version', 'SettingController@appVersion');
        Route::post('setting/app_version', 'SettingController@appVersionUpdate');

        Route::get('setting/address_setting', 'SettingController@address_setting');
        Route::post('setting/address_setting', 'SettingController@AddressSettingUpdate');


        Route::post('setting/reboot', 'SettingController@reBoot');

        // TODO admin language management section

        Route::get('language/site/{lang}', 'LanguageController@site');
        Route::post('language/site/{lang}', 'LanguageController@siteUpdate');

        Route::get('language/order/{lang}', 'LanguageController@site');
        Route::post('language/order/{lang}', 'LanguageController@siteUpdate');

        Route::get('language/ads/{lang}', 'LanguageController@site');
        Route::post('language/ads/{lang}', 'LanguageController@siteUpdate');

        Route::get('language/offer-slider/{lang}', 'LanguageController@site');
        Route::post('language/offer-slider/{lang}', 'LanguageController@siteUpdate');


        Route::get('language/pagination/{lang}', 'LanguageController@site');
        Route::post('language/pagination/{lang}', 'LanguageController@siteUpdate');

        Route::get('language/password/{lang}', 'LanguageController@site');
        Route::post('language/password/{lang}', 'LanguageController@siteUpdate');

        Route::get('language/validation/{lang}', 'LanguageController@site');
        Route::post('language/validation/{lang}', 'LanguageController@siteUpdate');

        Route::get('language/slider/{lang}', 'LanguageController@site');
        Route::post('language/slider/{lang}', 'LanguageController@siteUpdate');

        Route::get('language/auth/{lang}', 'LanguageController@site');
        Route::post('language/auth/{lang}', 'LanguageController@siteUpdate');

        Route::get('language/user/{lang}', 'LanguageController@site');
        Route::post('language/user/{lang}', 'LanguageController@siteUpdate');

        // TODO admin cms section
        Route::any('cms/datatable', 'CmsController@anyData')->name('cms.datatable');
        Route::resource('cms', 'CmsController', ['only' => ['index', 'update', 'edit']]);

        //Membership module
        Route::any('membership/changestatus', 'MembershipController@changeStatus')->name('admin.membership.status');
        Route::any('membership/datatable', 'MembershipController@anyData')->name('membership.datatable');
        Route::resource('membership', 'MembershipController');

        // POS section
        Route::any('pos/expenses/datatable', 'Pos\ExpensesController@anyData')->name('expenses.datatable');
        Route::resource('pos/expenses', 'Pos\ExpensesController');
        Route::any('pos/purchase/datatable', 'Pos\PurchaseController@anyData')->name('purchase.datatable');
        Route::any('pos/purchase/get-products', 'Pos\PurchaseController@getProducts')->name('purchase.get-products');
        Route::any('pos/purchase/get-brands', 'Pos\PurchaseController@getBrands')->name('purchase.get-brands');
        Route::get('pos/purchase/supplier_address/{Supplier}', 'Pos\PurchaseController@getSupplierAddress')->name('purchase.get.supplier.address');
        Route::get('pos/purchase/supplier-products', 'Pos\PurchaseController@getSupplierProducts')->name('purchase.get.supply.products');
        Route::get('pos/purchase/supplier-products-info/{product?}', 'Pos\PurchaseController@getSupplierProductsInfo')->name('purchase.get.supplier.products.info');
        Route::post('pos/purchase/supplier-products-save', 'Pos\PurchaseController@SaveSupplierPurchase')->name('purchase.supplier.purchase.save');
        Route::get('pos/purchase/supplier/list','Pos\PurchaseController@SupplierPurchaseList')->name('purchase.supplier.list');

        Route::resource('pos/purchase', 'Pos\PurchaseController');
        Route::get('pos/reports/sales', 'Pos\ReportsController@sales')->name('reports.sales');
        Route::any('pos/reports/sales/data', 'Pos\ReportsController@salesData')->name('reports.sales.data');
        Route::get('pos/reports/expenses', 'Pos\ReportsController@expenses')->name('reports.expenses');
        Route::any('pos/reports/expenses/data', 'Pos\ReportsController@expensesData')->name('reports.expenses.data');
        Route::get('pos/reports/purchase', 'Pos\ReportsController@purchase')->name('reports.purchase');
        Route::any('pos/reports/purchase/data', 'Pos\ReportsController@purchaseData')->name('reports.purchase.data');

        Route::any('pos/orders/create', 'Pos\OrdersController@create')->name('pos.orders.create');
        Route::any('pos/orders/datatable', 'Pos\OrdersController@anyData')->name('pos.orders.datatable');
        //Route::any('order/datatablenew', 'OrderController@anyDataOrderStatus')->name('order.datatablenew');
        //Route::any('order/status', 'OrderController@changeStatus')->name('admin.order.status');
        //Route::any('order/statuslist/{id}', 'OrderController@statuslist')->name('order.statuslist');
        //Route::any('order/showDetail/{id}', 'OrderController@showDetail')->name('order.showDetail');
        Route::resource('pos/orders', 'Pos\OrdersController');

        Route::any('pos/get-vendor-product', 'Pos\OrdersController@getVendorProduct')->name('pos-get-vendor-product');
        Route::any('pos/pos-get-barcode-product', 'Pos\OrdersController@getBarcodeProduct')->name('pos-get-barcode-product');
        Route::any('pos/get-vendor-product-detail', 'Pos\OrdersController@getVendorProductDetail')->name('pos-get-vendor-product-detail');
        Route::any('pos/order/store', 'Pos\OrdersController@store')->name('pos-order.store');
        Route::match(['get', 'post'], 'pos/order/pdfdownload/{order_id}', 'Pos\OrdersController@pdfdownload');
        Route::match(['get', 'post'], 'pos/order/print/{order_id}', 'Pos\OrdersController@print');
        Route::any('pos/order/add-coin', 'Pos\OrdersController@addDarbaarCoin')->name('pos-order.addDarbaarCoin');
        Route::any('pos/pos-get-user-by-param', 'Pos\OrdersController@getUserByParam')->name('pos-get-user-by-param');


        //pos users
        Route::prefix('pos/users')->group(function () {
            Route::get('/', 'Pos\UserController@index')->name('pos.users');
            Route::get('datatable', 'Pos\UserController@anyData')->name('pos.users.datatable');
            Route::get('create', 'Pos\UserController@create')->name('pos.users.create');
            Route::post('store', 'Pos\UserController@store')->name('pos.users.store');
            Route::get('edit/{id}', 'Pos\UserController@edit')->name('pos.users.edit');
            Route::post('update/{id}', 'Pos\UserController@update')->name('pos.users.update');
            Route::get('delete/{id}', 'Pos\UserController@destroy')->name('pos.users.delete');
        });

        // Supplier
        Route::any('supplier/datatable', 'SupplierController@anyData')->name('supplier.datatable');
        Route::any('supplier/status', 'SupplierController@changeStatus')->name('admin.supplier.status');
        Route::get('supplier/view/{Supplier}', 'SupplierController@view')->name('admin.supplier.view');
        Route::get('supplier/supplierviewtabs','SupplierContollers@supplierViewTabs')->name('admin.supplier.tabs');

        Route::resource('supplier', 'SupplierController');


        Route::any('test/update-sku', 'TestController@updateSku');
        Route::any('test/export-product-data', 'TestController@exportProductData');

        // Wallet Management
        Route::any('wallet-management/datatable', 'WalletManagementController@anyData')->name('walletManagement.datatable');
        Route::any('wallet-management/wallet-history/{id}', 'WalletManagementController@walletHistory')->name('wallet-management.wallet-history');
        Route::any('wallet-management/coin-history/{id}', 'WalletManagementController@coinHistory')->name('wallet-management.darbaar-coin-history');
        Route::any('wallet-management/datatable', 'WalletManagementController@anyData')->name('walletManagement.datatable');
        Route::get('wallet-management/wallet-history/datatable', 'WalletManagementController@walletHistoryData')->name('walletManagement.wallet.datatable');
        Route::get('wallet-management/coin-history/datatable', 'WalletManagementController@coinHistoryData')->name('walletManagement.coin.datatable');
        Route::resource('wallet-management', 'WalletManagementController');
        Route::match(['get', 'post'], 'wallet-management/add-wallet-entry/{id}', 'WalletManagementController@addWalletEntry')->name('wallet-management.add-wallet-entry');
        Route::match(['get', 'post'], 'wallet-management/add-coin-entry/{id}', 'WalletManagementController@addCoinEntry')->name('wallet-management.add-coin-entry');
    });
});

Route::post('broadcastAuth', 'HomeController@AuthSocket');
Route::any('/broadcasting/auth', function () {
    return Auth::guard('admin')->user();
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/search', 'SearchController@index')->name('search');
Route::post('/addtocart', 'CartController@addtocart')->name('addtocart');
Route::post('/notifyme', 'CartController@notifyme')->name('notifyme');
Route::post('/removeOutStock', 'CartController@removeOutStock')->name('removeOutStock');
Route::post('/addtowishlist', 'WishlistController@store')->name('addtowishlist');

Route::post('/applypromocode', 'CartController@applypromocode')->name('applypromocode');
Route::get('/removepromocode', 'CartController@removepromocode')->name('removepromocode');
Route::post('/order', 'OrderController@order')->name('order');
//Route::get('/getdelievrytime', 'OrderController@getdelievrytime')->name('getdelievrytime');
Route::any('/getaddress', 'OrderController@getaddress')->name('getaddress');
Route::post('/placeorder', 'OrderController@store')->name('placeorder');

Route::any('delivery-location-by-id', 'DeliveryLocationController@getDeliveryAddressById')->name('delivery-location-by-id');
Route::resource('delivery_location', 'DeliveryLocationController');


// Get Route For Show Payment Form
Route::get('paywithrazorpay/{amount}', 'RazorpayController@payWithRazorpay')->name('paywithrazorpay');
// Post Route For Makw Payment Request
Route::post('payment', 'RazorpayController@payment')->name('payment');

// Easebuzz payment 
Route::get('/easebuzz-gateway', 'EasebuzzController@easebuzz_gateway');
Route::get('/paywitheasebuzz/{amount}', 'EasebuzzController@paywitheasebuzz');
Route::post('/easebuzz-payment', 'EasebuzzController@payment')->name('easebuzz-payment');
Route::post('/easebuzz-webhook', 'EasebuzzController@easebuzz_webhook');


Route::any('membership', 'MembershipController@index')->name('membership');
Route::any('membership-success', 'MembershipController@addmembership')->name('membership-success');
Route::any('membership-thank-you', 'MembershipController@index')->name('membership-thank-you');


Route::get('/couponList', 'CartController@couponList');

Route::get('/whats-app/send-file/{id}/{phone_number}', 'WhatsappController@sendFile');
Route::get('/whats-app/send-text/{phone_number}', 'WhatsappController@sendText');
Route::get('/whats-app/get-pdf/{id}', 'WhatsappController@getPdf');

Route::get('/pdf/{file}', function ($file) {
    // file path
    $path = public_path('invoices' . '/' . $file . '#invoice.pdf');
    // header
    $header = [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $file . '"'
    ];
    return response()->file($path, $header);
})->name('pdf');


Route::prefix('inventory')->group(function () {
    Route::get('/', [StockVerificationController::class, 'index'])->name('admin.inventory.list');
    Route::get('create',[StockVerificationController::class,'createStockVerification'])->name('admin.stock.verification.create');
    Route::get('opening-stock',[OpeningStockController::class,'index'])->name('admin.opening.stock');
    Route::get('opening-stock/list',[OpeningStockController::class,'list'])->name('admin.opening.stock.list');
    Route::post('opening-stock/update',[OpeningStockController::class,'updateStock'])->name('admin.opening.stock.update');

});

Route::get('expire-coin',function(){
try{
    DB::beginTransaction();
    function isExpire($date)
    {
        $days =  Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffInDays(now());

        if ($days > 30) {
            return true;
        } else {
            return false;
        }
    }


    $user_wallet = UserWallet::where('status', 1)->where('transaction_type', 'CREDIT')
        ->where('wallet_type', 'coin')->get();
    foreach ($user_wallet as $wallet) {
        if (isExpire($wallet->created_at)) {
            UserWallet::where('id', $wallet->id)->update(['status' => 0]);
            $user_coin=User::where('id',$wallet->user_id)->value('coin_amount');
            if($user_coin>=$wallet->amount){
                User::where('id',$wallet->user_id)->decrement('coin_amount',$wallet->amount);
            }
        }
    }
    DB::commit();
    return "working";

}
catch(Exception $e){
    Log::error($e);
    DB::rollBack();

}
   

});

