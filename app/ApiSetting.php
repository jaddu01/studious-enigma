<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiSetting extends Model
{
    protected $fillable = [
        'facebook_app_id', 'facebook_app_secret_key', 'twitter_app_id', 'twitter_app_secret_key', 'ios_customer_app_google_key', 'android_customer_app_google_key', 'android_shopper_app_google_key', 'android_driver_app_google_key', 'admin_panel_google_key', 'google_analytics_code', 'facebook_analytics_code', 'customer_app_id', 'customer_app_rest_key', 'shopper_app_id', 'shopper_app_rest_key', 'driver_app_id', 'driver_app_rest_key', 'admin_panel_id', 'admin_panel_rest_key', 'all_order_redirect_url', 'admin_notification_redirect_url', 'shopper_notification_redirect_url', 'driver_notification_redirect_url',
    ];

    /**
     * @param $method
     * @return array
     */
    public function rules($method)
    {
        /*$user = User::find($this->users);*/

        switch ($method) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [

                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [


                    ];
                }
            default:
                break;
        }
    }
}
