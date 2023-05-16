<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class Category extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\CategoryTranslation';
    use SoftDeletes;
    protected $fillable = [
        'parent_id','sort_no','status','is_show','slug'
    ];

    public $translatedAttributes = ['name', 'image','banner_image','slug'];

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
                $page= [

                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required',
                            'sort_no' => 'numeric',
                            /*'image:'.$locale => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,WebP',*/
                            'image:'.$locale => 'required',
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
                            /*'image:'.$locale => 'image|mimes:jpeg,png,jpg,gif,svg',
                            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,WebP',*/
                            /*'image:'.$locale => 'required',*/
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
                            /*'image:'.$locale => 'image|mimes:jpeg,png,jpg,gif,svg',
                            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,WebP',*/
                            'image:'.$locale => 'required',
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
                    'image.image' => 'please upload a valid image file',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name.required' => 'this fiels is required',
                    'image.image' => 'please upload a valid image file',
                ];
            }
            default:break;
        }
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }



}
