<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\RecentScope;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;
use Illuminate\Support\Facades\Request;
use Log;

class Product extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\ProductTranslation';
    use SoftDeletes;
    protected $fillable = [
        'vendor_id',
        'sku_code',
        'hsn_code',
        'barcode',
        'category_id',
        'brand_id',
        'gst',
        'measurement_class', 
        'measurement_value',
        'related_products',
        'status',
        'expire_date',
        'show_in_cart_page',
        'returnable',
        'price',
        'qty',
        'offer_id',
        'per_order',
        'best_price',
        'memebership_p_price',
        'purchase_price'
    ];
    public $translatedAttributes = ['name', 'description','disclaimer','keywords','self_life','manufacture_details','marketed_by','print_name'];

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
                $trans = [
                    'category_id' => 'required',
                    //'sku_code' => 'required',
                    'measurement_class' => 'required',
                    'measurement_value' => 'required',/*
                    'image' => "required|array|min:1",
                    'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,WebP',*/
                    'image' => "required|array|min:1",
                    //'brand_id' =>'required',
                    'gst' =>'required',
                ];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required',
                            'description:'.$locale => 'required',
                            'keywords:'.$locale => 'required',
                        ];
                }
                return $page + $trans;

            }
            case 'PUT':
                $page= [

                ];
                $trans = [
                    'category_id' => 'required',
                    //'sku_code' => 'required',
                    'measurement_class' => 'required',
                    'measurement_value' => 'required',
                    //'brand_id' =>'required',
                    'gst' =>'required',
                    /*'image' => "required|array|min:1",*/
                   /* 'image' => "required|array|min:1",*/
                    //'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                ];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required',
                            'keywords:'.$locale => 'required',
                            'description:'.$locale => 'required',
                        ];
                }
                return $page + $trans;
            case 'PATCH':
            {
                $page= [

                ];
                $trans = [
                    'category_id' => 'required',
                    //'sku_code' => 'required',
                    'measurement_class' => 'required',
                    'measurement_value' => 'required',
                    //'brand_id' =>'required',
                    'gst' =>'required',
                    /* 'image' => "required|array|min:1",*/
                    //'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                ];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required',
                            'keywords:'.$locale => 'required',
                            'description:'.$locale => 'required',
                            
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

    public function setRelatedProductsAttribute($value)
    {
        // Log::info("setRelatedProductsAttribute ".$value);
        $value = implode(',',$value);

        $this->attributes['related_products'] = $value;
    }

    public function getRelatedProductsAttribute($value)
    {
        // Log::info("getRelatedProductsAttribute ".$value);
        $value = explode(',',$value);

        return $value;
    }

    public function setCategoryIdAttribute($value)
    {
        $value = implode(',',$value);

        $this->attributes['category_id'] = $value;
    }

    public function getCategoryIdAttribute($value)
    {
        $value = explode(',',$value);

        return $value;
    }


    public function images()
    {
        return $this->morphMany('App\Image', 'image');
    }
    public function image()
    {
        return $this->morphOne('App\Image', 'image');
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }
    public function MeasurementClass()
    {
        return $this->belongsTo('App\MeasurementClass', 'measurement_class', 'id');
    }
     public function VendorProduct()
    {
         return $this->hasMany('App\VendorProduct');
    }
    public function varients(){
        return $this->hasMany(Variant::class,'product_id');
    }
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StatusScope());
    }
    public function ProductTranslation()
    {
        return $this->hasMany('App\ProductTranslation');
    }

    public function brand(){
        return $this->belongsTo('App\Brand','brand_id','id');
    }


}
