<?php

namespace App;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'image',
        'name',
        'price',
        'duration',
        'offer_id',
        'free_delivery',
        'status',
        'min_order_price'
        
    ];
    protected $appends=['is_offer','offer_price'];


    /**
     * @param $method
     * @return array
     */
    public function rules($method)
    {
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
                        'name' => 'required',
                        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                        'duration' => 'required',
                        'price' => 'required',
                        'duration' => 'required',
                    ];


                }
            case 'PUT':
                return [
                        'name' => 'required',
                        'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                        'duration' => 'required',
                        'price' => 'required',
                        'duration' => 'required',
                ];
            case 'PATCH':
                {
                    return [
                        'name' => 'required',
                        'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                        'duration' => 'required',
                        'price' => 'required',
                        'duration' => 'required',
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
                        'name.required' => 'name is required',
                        'image.image' => 'please upload a valid image file',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'name.required' => 'name is required',
                        'image.image' => 'please upload a valid image file',
                    ];
                }
            default:break;
        }
    }
    public function getImageAttribute($value)
    {
        return Helper::hasImage($value);
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


    public function Offer()
    {   $current_time = date("Y-m-d");

        return $this->belongsTo('App\Offer')->select(['id','offer_type','offer_value','to_time','from_time'])
            ->whereRaw('from_time <= CAST( "'.$current_time.'" AS DATE ) and to_time >= CAST( "'.$current_time.'" AS DATE ) ');
    }

    //user
    public function user()
    {
        return $this->hasOne('App\User','membership');
    }

 

   

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StatusScope());
    }

}
