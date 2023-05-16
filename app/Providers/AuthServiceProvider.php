<?php

namespace App\Providers;

use App\AccessLevel;
use App\AddressSetting;
use App\Ads;
use App\Brand;
use App\Category;
use App\City;
use App\Cms;
use App\DeliveryDay;
use App\DeliveryLocation;
use App\DeliveryTime;
use App\MeasurementClass;
use App\Notification;
use App\Offer;
use App\OfferSlider;
use App\VendorCommission;
use App\PaymentMode;
use App\PermissionAccess;
use App\PermissionModal;
use App\Revenue;
use App\Policies\AccessLevelPolicy;
use App\Policies\AddressSettingPolicy;
use App\Policies\AdsPolicy;
use App\Policies\BrandPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CityPolicy;
use App\Policies\CmsPolicy;
use App\Policies\DeliveryDayPolicy;
use App\Policies\DeliveryLocationPolicy;
use App\Policies\DeliveryTimePolicy;
use App\Policies\LoadSlotZonePolicy;
use App\Policies\MeasurementClassPolicy;
use App\Policies\OfferPolicy;
use App\Policies\PaymentModePolicy;
use App\Policies\PermissionAccessPolicy;
use App\Policies\PermissionModalPolicy;
use App\Policies\ProductOrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RegionPolicy;
use App\Policies\SettingPolicy;
use App\Policies\SiteSettingPolicy;
use App\Policies\SliderPolicy;
use App\Policies\SlotGroupPolicy;
use App\Policies\SlotTimePolicy;
use App\Policies\SupplierPolicy;
use App\Policies\UserPolicy;
use App\Policies\VendorProductPolicy;
use App\Policies\WeekPackagePolicy;
use App\Policies\ZonePolicy;
use App\Policies\OfferBannerPolicy;
use App\Policies\FinancialManagementPolicy;
use App\Policies\NotificationsPolicy;
use App\Policies\ReportsPolicy;
use App\Policies\WalletManagementPolicy;
use App\Product;
use App\ProductOrder;
use App\Region;
use App\Setting;
use App\SiteSetting;
use App\Slider;
use App\SlotGroup;
use App\SlotTime;
use App\Supplier;
use App\User;
use App\VendorProduct;
use App\WeekPackage;
use App\WalletManagement;
use App\Zone;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        AccessLevel::class => AccessLevelPolicy::class,
        AddressSetting::class => AddressSettingPolicy::class,
        Ads::class => AdsPolicy::class,
        Brand::class => BrandPolicy::class,
        Category::class => CategoryPolicy::class,
        City::class => CityPolicy::class,
        Cms::class => CmsPolicy::class,
        DeliveryDay::class => DeliveryDayPolicy::class,
        DeliveryLocation::class => DeliveryLocationPolicy::class,
        DeliveryTime::class => DeliveryTimePolicy::class,
        Zone::class => LoadSlotZonePolicy::class,
        MeasurementClass::class => MeasurementClassPolicy::class,
        Offer::class => OfferPolicy::class,
        PaymentMode::class => PaymentModePolicy::class,
        PermissionAccess::class => PermissionAccessPolicy::class,
        PermissionModal::class => PermissionModalPolicy::class,
        ProductOrder::class => ProductOrderPolicy::class,
        Product::class => ProductPolicy::class,
        Region::class => RegionPolicy::class,
        Setting::class => SettingPolicy::class,
        SiteSetting::class => SiteSettingPolicy::class,
        Slider::class => SliderPolicy::class,
        OfferSlider::class => OfferBannerPolicy::class,
        VendorCommission::class => FinancialManagementPolicy::class,
        Notification::class => NotificationsPolicy::class,
        Revenue::class => ReportsPolicy::class,
        SlotGroup::class => SlotGroupPolicy::class,
        SlotTime::class => SlotTimePolicy::class,
        Supplier::class => SupplierPolicy::class,
        VendorProduct::class => VendorProductPolicy::class,
        WeekPackage::class => WeekPackagePolicy::class,
        WalletManagement::class => WalletManagementPolicy::class,
        Zone::class => ZonePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
