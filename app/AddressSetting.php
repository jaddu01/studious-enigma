<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressSetting extends Model
{
    protected $fillable = [
        'address_name',
        'lat',
        'long',
        'description'
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
                    'address_name' => 'required',
                    'lat' => 'required',
                    'long' => 'required',
                    'description' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'address_name' => 'required',
                    'lat' => 'required',
                    'long' => 'required',
                    'description' => 'required',
                    
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
