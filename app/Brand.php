<?php

/**
 * @Author: abhi
 * @Date:   2021-08-30 16:56:29
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-08-30 20:09:18
 */


namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class Brand extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\BrandTranslation';
    use SoftDeletes;
    protected $fillable = [
        'status','slug'
    ];

    public $translatedAttributes = ['name', 'image','slug'];
    /**
     * @param $method
     * @return array
     */
    public function rules($method)
    {
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

                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required',
                            'sort_no' => 'numeric',
                            //'image:'.$locale => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                        ];
                }
                return $page + $trans;

            }
            case 'PUT':
                $page= [

                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required',
                            'sort_no' => 'numeric',
                            //'image:'.$locale => 'image|mimes:jpeg,png,jpg,gif,svg',
                        ];
                }
                return $page + $trans;
            case 'PATCH':
            {
                $page= [

                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required',
                            'sort_no' => 'numeric',
                            //'image:'.$locale => 'image|mimes:jpeg,png,jpg,gif,svg',
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
                return [
                    'name.required' => 'this fiels is required',
                    //'image.image' => 'please upload a valid image file',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name.required' => 'this fiels is required',
                    //'image.image' => 'please upload a valid image file',
                ];
            }
            default:break;
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }
    public function barndTraslation()
    {
        return $this->belongsTo(BrandTranslation::class, 'brand_id');
    }

    //products
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
