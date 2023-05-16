<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'mobile',
        'address','app_name', 'app_env', 'app_debug', 'app_log_level', 'app_url', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name', 'app_url_android', 'app_url_ios', 'under_maintenance', 'app_logo', 'timezone', 'pagination_limit','currency'
    ];

    /**
     * @param $method
     * @return array
     */
    public function rules($method)
    {
        /*$user = User::find($this->users);*/

        switch($method)
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'email' => 'required',
                    'phone' => 'required',
                 //   'mobile' => 'required',
                    'address' => 'required',
                    'app_name' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'email' => 'required',
                    'phone' => 'required',
                //    'mobile' => 'required',
                    'address' => 'required',
                    'app_name' => 'required',
                ];
            }
            default:break;
        }
    }
    public function getAppLogoAttribute($value)
    {
        return Helper::hasImage($value);
    }
}
