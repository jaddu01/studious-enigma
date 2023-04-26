<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppVersion extends Model
{
    protected $fillable = [
        'customer_android_current_version','driver_android_current_version', 'shopper_android_current_version','customer_android_mandatory_update','driver_android_mandatory_update','shopper_android_mandatory_update', 'customer_android_main_tenance_mode', 'driver_android_main_tenance_mode', 'shopper_android_main_tenance_mode', 'ios_current_version', 'ios_mandatory_update', 'ios_main_tenance_mode'
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
