<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class Region extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\RegionTranslation';
    use SoftDeletes;
    protected $fillable = [
        'city_id','lat','lng','status',
    ];

    public $translatedAttributes = ['name'];
    public $locales;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->locales = config('translatable.locales');
    }
    /**
     * @param $method
     * @return array
     */
    public function passes($attribute, $value)
    {
        return preg_match("/^[-]?((([0-8]?[0-9])(\.(\d{1,8}))?)|(90(\.0+)?)),\s?[-]?((((1[0-7][0-9])|([0-9]?[0-9]))(\.(\d{1,8}))?)|180(\.0+)?)$/", $value);
    }
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
                $page= [
                    'city_id'=>'required',
                    'lat'=>'numeric|between:0,99.99',
                    'lng'=>'numeric|between:0,99.99',
                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required'
                        ];
                }
                return $page + $trans;

            }
            case 'PUT':
                $page= [
                    'city_id'=>'required',
                    'lat'=>'numeric|between:0,99.99',
                    'lng'=>'numeric|between:0,99.99',
                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required'
                        ];
                }
                return $page + $trans;
            case 'PATCH':
            {
                $page= [
                    'city_id'=>'required',
                    'lat'=>'numeric|between:0,99.99',
                    'lng'=>'numeric|between:0,99.99',
                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required'
                        ];
                }
                return $page + $trans;
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

    public function City()
    {
        return $this->belongsTo('App\City', 'city_id', 'id');
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }



}
