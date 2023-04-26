<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class Offer extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\OfferTranslation';
    use SoftDeletes;
    protected $fillable = [
        'user_id','offer_type','offer_value','sold_product','to_time','from_time','status',
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
                   /* 'sold_product'=>'required|integer',*/
                    'offer_value'=>'required|numeric',
                    'offer_type'=>'required',
                  /*  'user_id'=>'required',*/
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
                   /* 'sold_product'=>'required',*/
                    'offer_value'=>'required',
                    'offer_type'=>'required',
                   /* 'user_id'=>'required',*/
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
                   /* 'sold_product'=>'required',*/
                    'offer_value'=>'required',
                    'offer_type'=>'required',
                   /* 'user_id'=>'required',*/
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
                            'name:'.$locale.'.required' => 'Image files is required',
                        ];
                }
                return $page + $trans;
            }
            default:break;
        }
    }



    public function User()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function images()
    {
        return $this->morphMany('App\Image', 'image');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }



}
