<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\RecentScope;
use App\Scopes\StatusScope;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\SliderTranslation';
    public  $locales;
    use SoftDeletes;
    protected $fillable = [
        'status','link','zone_id','link_type','link_url_type','cat_id','sub_cat_id','vendor_product_id'
    ];


    public $translatedAttributes = ['title','image'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->locales = config('translatable.locales');
    }

    /**
     * @param $method
     * @return array
     */
    public function rules($method,$id=0)
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
                $page= [
                   /* 'zone_id'=>'required',*/
                    'link_type'=>'required',
                    'link'=>'required_if:link_type,external',
                    'cat_id'=>'required_if:link_type,internal',
                    
                ];
                $trans = [
                    //'link'=>'required|url'
                ];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'image:'.$locale.'.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,Webp',
                            'title:'.$locale => "required|unique:slider_translations,title",
                        ];
                }
                return $page + $trans;

            }
            case 'PUT':
                $page= [
                    'link_type'=>'required',
                    'link'=>'required_if:link_type,external',
                    'cat_id'=>'required_if:link_type,internal',
                ];
                $trans = [
                   // 'link'=>'required|url'
                ];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'image:'.$locale.'.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,Webp,svg|max:5120',
                            'title:'.$locale => 'required|unique:slider_translations,title,'.$id.',slider_id',
                        ];
                }
                return $page + $trans;
            case 'PATCH':
            {
                $page= [  
                    'link_type'=>'required',
                    'link'=>'required_if:link_type,external|url',
                    'cat_id'=>'required_if:link_type,internal',];
                $trans = [
                    //'link'=>'required|url'
                ];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'image:'.$locale.'.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,Webp,svg|max:5120',
                            'title:'.$locale => 'required|unique:slider_translations,title,'.$id.',slider_id',
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
                $page= [];
                $trans = [];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'image:'.$locale.'.required' => 'Image files is required',
                             'title:'.$locale.'.required' => 'Title:'.$locale.' is required',
                            'title:'.$locale.'.unique' => 'Title:'.$locale.' is unique',
                        ];
                }
                return $page + $trans;

            }
            case 'POST':
            {
                $page= ['link.required_if'=>'The link field is required when link type is external.',
                'cat_id.required_if'=>'The category field is required when link type is internal.'
            ];
                $trans = [];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'image:'.$locale.'.required' => 'Image files is required',
                             'title:'.$locale.'.required' => 'Title:'.$locale.' is required',
                            'title:'.$locale.'.unique' => 'Title:'.$locale.' must be unique',
                        ];
                }
                return $page + $trans;
            }
            case 'PUT':{
              $page= ['link.required_if'=>'The link field is required when link type is external.',
                'cat_id.required_if'=>'The category field is required when link type is internal.'
            ];
             return $page;
             }
            case 'PATCH':
            {
                  $page= ['link.required_if'=>'The link field is required when link type is external.',
                'cat_id.required_if'=>'The category field is required when link type is internal.'
            ];
                $trans = [];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'image:'.$locale.'.required' => 'Image files is required',
                            'title:'.$locale.'.required' => 'Title:'.$locale.' is required',
                            'title:'.$locale.'.unique' => 'Title:'.$locale.' must be unique',
                        ];
                }
                return $page + $trans;
            }
            default:break;
        }
    }


 public function category()
    {
        return $this->belongsTo('App\Category', 'cat_id', 'id');
    }
    public function sub_category()
    {
        return $this->belongsTo('App\Category', 'sub_cat_id', 'id');
    }
     public function product()
    {
         return $this->belongsTo('App\VendorProduct', 'vendor_product_id', 'id');
    }


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StatusScope());
    }
}
