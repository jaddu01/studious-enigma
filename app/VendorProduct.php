<?php

namespace App;

use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorProduct extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'id',
        'user_id',
        'product_id',
        'qty',
        'price','best_price',
        'offer_id',
        'status',
        'per_order',
        'memebership_p_price',
        
    ];
    protected $table = 'vendor_products';
    protected $appends=['is_offer','offer_price'];


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
                    return [
                        'user_id' => 'required',
                        'product_id' => 'required',
                        'qty' => 'required',
                        'price' => 'required',
                        'best_price' => 'required',
                        'per_order' => 'required',
                    ];


                }
            case 'PUT':
                return [
                    'user_id' => 'required',
                    'product_id' => 'required',
                    'qty' => 'required',
                    'price' => 'required',
                     'best_price' => 'required',
                    'per_order' => 'required',
                ];
            case 'PATCH':
                {
                    return [
                        'user_id' => 'required',
                        'product_id' => 'required',
                        'qty' => 'required',
                        'price' => 'required',
                         'best_price' => 'required',
                        'per_order' => 'required',
                    ];
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

    public function getIsOfferAttribute($value)
    {

        if($this->offer){
         return true;
        }
        return false;
    }
    public function getOfferPriceAttribute($value)
    {
        if($this->offer){
           if($this->offer->offer_type=='amount'){
               return ($this->price)-($this->offer->offer_value);
           }else{
               return $this->price-(($this->offer->offer_value*$this->price)/100);
           }
        }
        return $this->price;
    }


    public function User()
    {
        return $this->belongsTo('App\User');
    }
    public function Offer()
    {   $current_time = date("Y-m-d");

        return $this->belongsTo('App\Offer')->select(['id','offer_type','offer_value','to_time','from_time'])
            ->whereRaw('from_time <= CAST( "'.$current_time.'" AS DATE ) and to_time >= CAST( "'.$current_time.'" AS DATE ) ');
    }

    public function Product()
    {
        return $this->belongsTo('App\Product');
    }
    public function NewProduct()
    {
        return $this->belongsTo('App\Product','product_id');
    }


    public function cart(){
        return $this->hasOne('App\Cart');
    }
    public function ProductOrderItem(){
        return $this->hasMany('App\ProductOrderItem');
    }
    public function wishList(){
        return $this->hasOne('App\WishLish');
    }

    //relationship to varients
    public function varients(){
        return $this->hasMany(Variant::class,'product_id');
    }


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StatusScope());
    }

}
