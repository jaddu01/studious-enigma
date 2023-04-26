<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppSetting extends Model
{
    protected $fillable = [
        'phone_number','whatsapp_api_link', 'android_play_store','android_play_store_driver','android_play_store_shopper', 'ios_app_store', 'mim_amount_for_order','mim_amount_for_order_prime', 'mim_amount_for_free_delivery','update_shopper_location','update_driver_location','update_shopper_app','update_driver_app','mim_amount_for_free_delivery_prime'
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
                    'android_play_store' => 'required',
                    'android_play_store_driver' => 'required',
                    'android_play_store_shopper' => 'required',
                    'mim_amount_for_free_delivery' => 'required',
                    'whatsapp_api_link' => 'required',
                    'ios_app_store' => 'required',
                    
                ];
                }
            case 'PUT':
            case 'PATCH':
                {
                     return [
                    'android_play_store' => 'required',
                    'android_play_store_driver' => 'required',
                    'android_play_store_shopper' => 'required',
                    'mim_amount_for_free_delivery' => 'required',
                    'whatsapp_api_link' => 'required',
                    'ios_app_store' => 'required',
                ];
                }
            default:
                break;
        }
    }
}
