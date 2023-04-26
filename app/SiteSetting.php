<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSetting extends Model
{
    protected $fillable = [
        'min_price',
        'max_price',
        'free_delivery_charge',
        'phone', 'whats_up', 'facebook', 'twitter', 'instagram', 'linkedin','referral_amount','referred_by_amount','youtube','refer_limit'
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
                   // 'min_price' => 'required|numeric',
                   // 'max_price' => 'required|numeric',
                   // 'free_delivery_charge' => 'required|numeric'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                   // 'min_price' => 'required|numeric',
                   // 'max_price' => 'required|numeric',
                   // 'free_delivery_charge' => 'required|numeric'
                    
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
