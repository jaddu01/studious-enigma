<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class Coupon extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\CouponTranslation';
    use SoftDeletes;
    protected $fillable = [
        'code','coupon_type','coupon_value','to_time','from_time','status','number_of_use','type'
    ];

    public $translatedAttributes = ['name'];

    /**
     * @param $method
     * @return array
     */
    public function rules($method)
    {
        /*$user = User::find($this->users);*/
        $locales = config('translatable.locales');
        switch($method)
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {

                $trans = [
                    'code'=>'required',
                    'coupon_value'=>'required|numeric',
                    'coupon_type'=>'required',
                    'number_of_use'=>'required|numeric',
                    'from_time'=>'required',
                    'to_time'=>'required',
                 ];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required'
                        ];
                }

                return  $trans;

            }
            case 'PUT':

                $trans = [
                   'code'=>'required',
                    'coupon_value'=>'required|numeric',
                    'coupon_type'=>'required',
                    'number_of_use'=>'required|numeric',
                    'from_time'=>'required',
                    'to_time'=>'required',
                ];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required'
                        ];
                }
                return  $trans;
            case 'PATCH':
            {

                $trans = [
                   'code'=>'required',
                    'coupon_value'=>'required|numeric',
                    'coupon_type'=>'required',
                    'number_of_use'=>'required|numeric',
                    'from_time'=>'required',
                    'to_time'=>'required',
                ];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required'
                        ];
                }
                return  $trans;
            }
            default:break;
        }
    }


    public function messages($method)
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
                $page= [];
                $trans = [];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale.'.required' => 'Name files is required',
                        ];
                }
                return $page + $trans;
            }
            case 'PUT':
            case 'PATCH':
            {
                $page= [];
                $trans = [];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale.'.required' => 'name is required',
                        ];
                }
                return $page + $trans;
            }
            default:break;
        }
    }



    public function images()
    {
        return $this->morphMany('App\Image', 'image');
    }

    public function users()
    {
        return $this->belongsToMany('App\User','coupon_users','coupon_id','user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }



}
